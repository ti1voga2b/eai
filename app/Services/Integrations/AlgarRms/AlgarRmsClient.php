<?php

namespace App\Services\Integrations\AlgarRms;

use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class AlgarRmsClient
{
    private const TOKEN_CACHE_PREFIX = 'integrations:algar-rms:access-token:';
    private const TOKEN_LOCK_PREFIX = 'integrations:algar-rms:access-token-lock:';

    /**
     * List one page of subscribers using the RMS contract: page, size, and q.
     * The response envelope (data/meta) is returned without logging subscriber data.
     *
     * @return array{data: array, meta: array}
     */
    public function listSubscribers(int $page = 1, int $size = 50, ?string $q = null): array
    {
        if ($page < 1) {
            throw new InvalidArgumentException('The subscriber page must be at least 1.');
        }

        if ($size < 1) {
            throw new InvalidArgumentException('The subscriber page size must be at least 1.');
        }

        $query = ['page' => $page, 'size' => $size];
        if ($q !== null && trim($q) !== '') {
            $query['q'] = $q;
        }

        $token = $this->accessToken();
        $response = $this->getSubscribersPage($token, $query);

        // A single 401 retry is safe here because this operation is read-only.
        if ($response->status() === 401) {
            $token = $this->accessToken(forceRefresh: true, rejectedToken: $token);
            $response = $this->getSubscribersPage($token, $query);
        }

        if (! $response->successful()) {
            throw new AlgarRmsException('subscriber listing', $response->status());
        }

        $payload = $response->json();
        if (! is_array($payload)
            || ! is_array($payload['data'] ?? null)
            || ! is_array($payload['meta'] ?? null)
            || ! $this->hasSubscriberRecords($payload['data'])
            || ! $this->hasPaginationMetadata($payload['meta'])) {
            throw new AlgarRmsException('subscriber listing response validation', 502);
        }

        return $payload;
    }

    private function accessToken(bool $forceRefresh = false, ?string $rejectedToken = null): string
    {
        $configuration = $this->configuration();
        $cacheKey = self::TOKEN_CACHE_PREFIX . $this->credentialFingerprint($configuration);
        $lockKey = self::TOKEN_LOCK_PREFIX . $this->credentialFingerprint($configuration);

        return Cache::lock($lockKey, 30)->block(20, function () use (
            $configuration,
            $cacheKey,
            $forceRefresh,
            $rejectedToken
        ): string {
            $cached = Cache::get($cacheKey);
            if (is_array($cached) && is_string($cached['token'] ?? null) && $cached['token'] !== '') {
                if (! $forceRefresh || ! hash_equals($rejectedToken ?? '', $cached['token'])) {
                    return $cached['token'];
                }

                Cache::forget($cacheKey);
            }

            try {
                $response = Http::acceptJson()
                    ->asJson()
                    ->timeout(15)
                    ->connectTimeout(5)
                    ->withoutRedirecting()
                    ->post($configuration['base_url'] . '/auth/token', [
                        'client_id' => $configuration['client_id'],
                        'client_secret' => $configuration['client_secret'],
                    ]);
            } catch (ConnectionException) {
                throw new AlgarRmsException('token acquisition transport');
            }

            if (! $response->successful()) {
                throw new AlgarRmsException('token acquisition', $response->status());
            }

            $payload = $response->json();
            if (! is_array($payload)
                || ! is_string($payload['access_token'] ?? null)
                || trim($payload['access_token']) === ''
                || ! is_string($payload['token_type'] ?? null)
                || Str::lower($payload['token_type']) !== 'bearer'
                || ! is_int($payload['expires_in'] ?? null)
                || $payload['expires_in'] < 1) {
                throw new AlgarRmsException('token response validation', 502);
            }

            $configuredSkew = max(0, (int) $configuration['refresh_skew_seconds']);
            $skew = min($configuredSkew, max(0, $payload['expires_in'] - 1));
            $cacheTtl = max(1, $payload['expires_in'] - $skew);
            Cache::put($cacheKey, ['token' => $payload['access_token']], $cacheTtl);

            return $payload['access_token'];
        });
    }

    /** @param array{page:int,size:int,q?:string} $query */
    private function getSubscribersPage(string $token, array $query): Response
    {
        $configuration = $this->configuration();

        try {
            return Http::acceptJson()
                ->withToken($token)
                ->timeout(15)
                ->connectTimeout(5)
                ->withoutRedirecting()
                ->get($configuration['base_url'] . '/v2/subscribers', $query);
        } catch (ConnectionException) {
            throw new AlgarRmsException('subscriber listing transport');
        }
    }

    private function configuration(): array
    {
        $configuration = config('services.algar_rms', []);
        $baseUrl = is_string($configuration['base_url'] ?? null)
            ? rtrim(trim($configuration['base_url']), '/')
            : '';
        $clientId = $configuration['client_id'] ?? null;
        $clientSecret = $configuration['client_secret'] ?? null;
        $parts = $baseUrl !== '' ? parse_url($baseUrl) : false;
        $host = is_array($parts) ? strtolower((string) ($parts['host'] ?? '')) : '';

        if (! is_array($parts)
            || strtolower((string) ($parts['scheme'] ?? '')) !== 'https'
            || $host === ''
            || isset($parts['user'])
            || isset($parts['pass'])
            || isset($parts['query'])
            || isset($parts['fragment'])
            || $host === 'example.com'
            || str_ends_with($host, '.example.com')
            || ! is_string($clientId)
            || trim($clientId) === ''
            || ! is_string($clientSecret)
            || trim($clientSecret) === '') {
            throw new AlgarRmsException('configuration is incomplete or invalid');
        }

        return [
            'base_url' => $baseUrl,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_skew_seconds' => (int) ($configuration['refresh_skew_seconds'] ?? 60),
        ];
    }

    private function credentialFingerprint(array $configuration): string
    {
        return hash('sha256', implode("\0", [
            $configuration['base_url'],
            $configuration['client_id'],
            $configuration['client_secret'],
        ]));
    }

    private function hasPaginationMetadata(array $meta): bool
    {
        foreach (['currentPage', 'pageSize', 'totalItems', 'totalPages'] as $field) {
            if (! isset($meta[$field]) || ! is_int($meta[$field])) {
                return false;
            }
        }

        return true;
    }

    private function hasSubscriberRecords(array $records): bool
    {
        foreach ($records as $record) {
            if (! is_array($record)
                || ! is_string($record['id'] ?? null)
                || ! is_string($record['type'] ?? null)
                || ! is_string($record['name'] ?? null)
                || ! is_string($record['document'] ?? null)) {
                return false;
            }
        }

        return true;
    }
}
