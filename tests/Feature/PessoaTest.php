<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PessoaTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_pessoa(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('pessoas.store'), [
                'nome' => 'jOÃO dA sILVA',
                'cpf' => '529.982.247-25',
                'tipo' => 'física',
                'telefone' => '(11) 98765-4321',
                'email' => 'joao@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('pessoas.show', Pessoa::query()->firstOrFail()));

        $this->assertDatabaseHas('pessoas', [
            'nome' => 'João Da Silva',
            'cpf' => '52998224725',
            'tipo' => 'física',
            'telefone' => '11987654321',
            'email' => 'joao@example.com',
        ]);
    }

    public function test_authenticated_user_can_create_a_pessoa_with_a_valid_cnpj(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('pessoas.store'), [
                'nome' => 'empresa exemplo',
                'cpf' => '11.222.333/0001-81',
                'tipo' => 'jurídica',
                'telefone' => '(11) 3333-4444',
                'email' => 'empresa@example.com',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pessoas', [
            'cpf' => '11222333000181',
            'tipo' => 'jurídica',
        ]);
    }

    public function test_authenticated_user_cannot_create_a_pessoa_with_invalid_document_check_digits(): void
    {
        $user = User::factory()->create();

        foreach ([
            ['tipo' => 'física', 'cpf' => '529.982.247-26', 'message' => 'Informe um CPF válido.'],
            ['tipo' => 'física', 'cpf' => '123.456.789-0', 'message' => 'Informe um CPF válido.'],
            ['tipo' => 'física', 'cpf' => '111.111.111-11', 'message' => 'Informe um CPF válido.'],
            ['tipo' => 'jurídica', 'cpf' => '11.222.333/0001-80', 'message' => 'Informe um CNPJ válido.'],
            ['tipo' => 'jurídica', 'cpf' => '11.222.333/0001-8', 'message' => 'Informe um CNPJ válido.'],
            ['tipo' => 'jurídica', 'cpf' => '11.111.111/1111-11', 'message' => 'Informe um CNPJ válido.'],
        ] as $document) {
            $this->actingAs($user)
                ->post(route('pessoas.store'), [
                    ...$document,
                    'nome' => 'Pessoa de teste',
                    'telefone' => '',
                    'email' => 'pessoa@example.com',
                ])
                ->assertSessionHasErrors(['cpf' => $document['message']]);
        }

        $this->assertDatabaseCount('pessoas', 0);
    }

    public function test_duplicate_document_error_message_matches_person_type(): void
    {
        $user = User::factory()->create();
        Pessoa::factory()->create([
            'cpf' => '52998224725',
            'tipo' => 'física',
        ]);
        Pessoa::factory()->create([
            'cpf' => '11222333000181',
            'tipo' => 'jurídica',
        ]);

        foreach ([
            ['tipo' => 'física', 'cpf' => '529.982.247-25', 'message' => 'Este CPF já está cadastrado para outra pessoa.'],
            ['tipo' => 'jurídica', 'cpf' => '11.222.333/0001-81', 'message' => 'Este CNPJ já está cadastrado para outra pessoa.'],
        ] as $document) {
            $this->actingAs($user)
                ->post(route('pessoas.store'), [
                    ...$document,
                    'nome' => 'Pessoa de teste',
                    'telefone' => '',
                    'email' => 'pessoa@example.com',
                ])
                ->assertSessionHasErrors(['cpf' => $document['message']]);
        }
    }

    public function test_pessoa_search_is_case_insensitive(): void
    {
        $user = User::factory()->create();
        Pessoa::factory()->create(['nome' => 'ANA SILVA']);
        Pessoa::factory()->create(['nome' => 'João Silva']);

        foreach ([
            ['search' => 'ana', 'name' => 'ANA SILVA'],
            ['search' => 'ANA', 'name' => 'ANA SILVA'],
            ['search' => 'JOÃO', 'name' => 'João Silva'],
        ] as $case) {
            $this->actingAs($user)
                ->get(route('pessoas.index', ['search' => $case['search']]))
                ->assertInertia(fn (Assert $page) => $page
                    ->component('Pessoas/Index')
                    ->has('pessoas.data', 1)
                    ->where('pessoas.data.0.nome', $case['name']));
        }
    }

    public function test_authenticated_user_can_view_update_and_delete_a_pessoa(): void
    {
        $user = User::factory()->create();
        $pessoa = Pessoa::factory()->create();

        $this->actingAs($user)
            ->get(route('pessoas.show', $pessoa))
            ->assertOk();

        $this->actingAs($user)
            ->put(route('pessoas.update', $pessoa), [
                'nome' => 'nOME aTUALIZADO',
                'cpf' => '529.982.247-25',
                'tipo' => 'física',
                'telefone' => '(11) 98765-4321',
                'email' => 'atualizado@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('pessoas.show', $pessoa));

        $this->assertDatabaseHas('pessoas', [
            'id' => $pessoa->id,
            'nome' => 'Nome Atualizado',
            'cpf' => '52998224725',
            'email' => 'atualizado@example.com',
        ]);

        $this->actingAs($user)
            ->delete(route('pessoas.destroy', $pessoa))
            ->assertRedirect(route('pessoas.index'));

        $this->assertDatabaseMissing('pessoas', ['id' => $pessoa->id]);
    }
}