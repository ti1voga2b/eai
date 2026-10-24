<?php

use App\Filament\Resources\CadEndpointResource;
use App\Models\CadEndpoint;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

uses(Tests\UnitTestCase::class);

test('migration keeps existing post endpoints conservative and is reversible', function () {
    Schema::create('cad_endpoints', function (Blueprint $table) {
        $table->id();
        $table->string('metodo');
    });
    $existingId = DB::table('cad_endpoints')->insertGetId(['metodo' => 'POST']);

    $migration = require database_path('migrations/2026_10_24_135500_add_post_read_only_to_cad_endpoints_table.php');
    $migration->up();

    expect(Schema::hasColumn('cad_endpoints', 'post_read_only'))->toBeTrue()
        ->and((int) DB::table('cad_endpoints')->where('id', $existingId)->value('post_read_only'))->toBe(0);

    $migration->down();
    expect(Schema::hasColumn('cad_endpoints', 'post_read_only'))->toBeFalse();
});

test('post read-only classification is fillable and cast to boolean', function () {
    Schema::create('cad_endpoints', function (Blueprint $table) {
        $table->id();
        $table->boolean('post_read_only')->default(false);
        $table->timestamps();
    });

    $endpoint = new CadEndpoint(['post_read_only' => true]);
    $endpoint->save();

    expect($endpoint->post_read_only)->toBeTrue()
        ->and(CadEndpoint::findOrFail($endpoint->id)->post_read_only)->toBeTrue();
});

test('endpoint form exposes the explicit read-only confirmation only for post', function () {
    Schema::create('cad_processos', function (Blueprint $table) {
        $table->id();
        $table->string('name');
    });
    Schema::create('clients', function (Blueprint $table) {
        $table->id();
        $table->string('name');
    });

    $livewire = new class extends Component implements HasForms
    {
        use InteractsWithForms;

        public ?string $metodo = null;

        public ?string $direcao = null;

        public bool $post_read_only = false;

        public function render(): string
        {
            return '';
        }
    };

    $form = CadEndpointResource::form(Form::make($livewire));
    $fields = $form->getFlatFields(withHidden: true, withAbsolutePathKeys: true);
    $form->fill(['metodo' => 'POST', 'direcao' => 'entrada', 'post_read_only' => false]);

    expect($fields)->toHaveKey('post_read_only')
        ->and($fields['post_read_only']->isVisible())->toBeTrue()
        ->and($fields['post_read_only']->getHelperText())->toContain('não altera estado');

    $form->fill(['metodo' => 'GET']);
    expect($fields['post_read_only']->isHidden())->toBeTrue();
});
