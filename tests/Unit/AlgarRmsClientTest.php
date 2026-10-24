<?php

use App\Services\Integrations\AlgarRms\AlgarRmsClient;
use App\Services\Integrations\AlgarRms\AlgarRmsException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(Tests\UnitTestCase::class);

beforeEach(function () {
    Cache::flush();
    config()->set('services.algar_rms', [
        'base_url' => 'https://sandbox.telecom-enabler.test',
        'client_id' => 'simulated-client-id',
        'client_secret' => 'simulated-client-secret',
        'refresh_skew_seconds' => 60,
    ]);
});

afterEach(function () {
    Carbon::setTestNow();
});

function subscribersResponse(array $data = [], int $page = 1, int $size = 50, int $total = 0, int $pages = 1): array
{
    return [
        'data' => $data,
        'meta' => [
            'currentPage' => $page,
            'pageSize' => $size,
            'totalItems' => $total,
            'totalPages' => $pages,
        ],
    ];
}

test('authenticates with JSON and requests the requested subscriber page with bearer token', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::response([
            'access_token' => 'simulated-access-token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]),
        'https://sandbox.telecom-enabler.test/v2/subscribers*' => Http::response(subscribersResponse([
            ['id' => 'subscriber-uuid', 'type' => 'individual', 'name' => 'Test Person', 'document' => 'masked'],
        ], 2, 25, 51, 3)),
    ]);

    $result = app(AlgarRmsClient::class)->listSubscribers(2, 25, 'test');

    expect($result['meta'])->toBe([
        'currentPage' => 2,
        'pageSize' => 25,
        'totalItems' => 51,
        'totalPages' => 3,
    ]);

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sandbox.telecom-enabler.test/auth/token'
            && $request->method() === 'POST'
            && $request->hasHeader('Content-Type', 'application/json')
            && $request['client_id'] === 'simulated-client-id'
            && $request['client_secret'] === 'simulated-client-secret';
    });
    Http::assertSent(function ($request) {
        return str_starts_with($request->url(), 'https://sandbox.telecom-enabler.test/v2/subscribers?')
            && $request->method() === 'GET'
            && $request->hasHeader('Authorization', 'Bearer simulated-access-token')
            && $request['page'] === 2
            && $request['size'] === 25
            && $request['q'] === 'test';
    });
    Http::assertSentCount(2);
});

test('reuses a cached token for multiple subscriber pages', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::response([
            'access_token' => 'cached-access-token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]),
        'https://sandbox.telecom-enabler.test/v2/subscribers*' => Http::response(subscribersResponse()),
    ]);

    $client = app(AlgarRmsClient::class);
    $client->listSubscribers(1, 20);
    $client->listSubscribers(2, 20);

    Http::assertSentCount(3);
    expect(collect(Http::recorded())->filter(function ($recorded) {
        return $recorded[0]->url() === 'https://sandbox.telecom-enabler.test/auth/token';
    })->count())->toBe(1);
});

test('obtains a new token after the cached token reaches its refresh boundary', function () {
    Http::preventStrayRequests();
    config()->set('services.algar_rms.refresh_skew_seconds', 10);
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::sequence()
            ->push(['access_token' => 'first-token', 'token_type' => 'Bearer', 'expires_in' => 30])
            ->push(['access_token' => 'second-token', 'token_type' => 'Bearer', 'expires_in' => 30]),
        'https://sandbox.telecom-enabler.test/v2/subscribers*' => Http::response(subscribersResponse()),
    ]);

    $client = app(AlgarRmsClient::class);
    $client->listSubscribers();
    Carbon::setTestNow(Carbon::now()->addSeconds(21));
    $client->listSubscribers();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://sandbox.telecom-enabler.test/v2/subscribers?page=1&size=50'
            && $request->hasHeader('Authorization', 'Bearer second-token');
    });
    Http::assertSentCount(4);
});

test('renews once after an unauthorized read and does not loop', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::sequence()
            ->push(['access_token' => 'rejected-token', 'token_type' => 'Bearer', 'expires_in' => 3600])
            ->push(['access_token' => 'replacement-token', 'token_type' => 'Bearer', 'expires_in' => 3600]),
        'https://sandbox.telecom-enabler.test/v2/subscribers*' => Http::sequence()
            ->push(['message' => 'denied'], 401)
            ->push(subscribersResponse(), 200),
    ]);

    $result = app(AlgarRmsClient::class)->listSubscribers();

    expect($result['data'])->toBe([]);
    Http::assertSentCount(4);
});

test('does not retry token authentication failures or expose the response body', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::response([
            'message' => 'secret value simulated-access-token and personal@example.test',
        ], 401),
    ]);

    try {
        app(AlgarRmsClient::class)->listSubscribers();
        test()->fail('Expected the token request to fail.');
    } catch (AlgarRmsException $exception) {
        expect($exception->getMessage())->toBe('Algar RMS token acquisition failed (HTTP 401)')
            ->not->toContain('secret value')
            ->not->toContain('personal@example.test');
    }

    Http::assertSentCount(1);
});

test('does not request another token for a forbidden subscriber query', function () {
    Http::preventStrayRequests();
    Http::fake([
        'https://sandbox.telecom-enabler.test/auth/token' => Http::response([
            'access_token' => 'valid-token',
            'token_type' => 'Bearer',
            'expires_in' => 3600,
        ]),
        'https://sandbox.telecom-enabler.test/v2/subscribers*' => Http::response(['message' => 'forbidden'], 403),
    ]);

    expect(fn () => app(AlgarRmsClient::class)->listSubscribers())
        ->toThrow(AlgarRmsException::class, 'HTTP 403');

    Http::assertSentCount(2);
});

test('rejects missing or placeholder host configuration before making a request', function (string $baseUrl) {
    config()->set('services.algar_rms.base_url', $baseUrl);
    Http::preventStrayRequests();
    Http::fake();

    expect(fn () => app(AlgarRmsClient::class)->listSubscribers())
        ->toThrow(AlgarRmsException::class);

    Http::assertNothingSent();
})->with([
    'missing host' => [''],
    'documentation placeholder' => ['https://sandbox-api.example.com'],
]);


test('generic fetch command does not retry a provider-side POST', function () {
    $url = 'https://provider.invalid/provision';
    Http::preventStrayRequests();
    Http::fakeSequence($url)
        ->push(['message' => 'temporary failure'], 503)
        ->push(['message' => 'second response must not be used'], 200);

    $client = new \App\Models\Client(['name' => 'Test', 'code' => 'test']);
    $endpoint = new \App\Models\CadEndpoint([
        'nome' => 'provision',
        'url' => $url,
        'metodo' => 'post',
        'autenticacao' => 'nenhum',
        'headers' => [],
        'payload' => ['order' => 'one'],
        'extensao' => 'json',
        'direcao' => 'entrada',
        'pagination' => [],
        'timeout' => 1,
        'tentativas' => 3,
    ]);
    $endpoint->setRelation('client', $client);

    $command = new \App\Console\Commands\FetchEndpoints();
    $buffer = new \Symfony\Component\Console\Output\BufferedOutput();
    $command->setOutput(new \Illuminate\Console\OutputStyle(
        new \Symfony\Component\Console\Input\ArrayInput([]),
        $buffer
    ));
    $method = new ReflectionMethod($command, 'processEndpointLocked');

    expect($method->invoke($command, $endpoint))->toBeFalse();
    Http::assertSentCount(1);
});
