<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
                'nome' => 'João da Silva',
                'cpf' => '123.456.789-01',
                'tipo' => 'física',
                'telefone' => '(11) 98765-4321',
                'email' => 'joao@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('pessoas.index'));

        $this->assertDatabaseHas('pessoas', [
            'nome' => 'João da Silva',
            'cpf' => '12345678901',
            'tipo' => 'física',
            'telefone' => '11987654321',
            'email' => 'joao@example.com',
        ]);
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
                'nome' => 'Nome atualizado',
                'cpf' => $pessoa->cpf,
                'tipo' => $pessoa->tipo,
                'telefone' => '(11) 98765-4321',
                'email' => 'atualizado@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('pessoas.show', $pessoa));

        $this->assertDatabaseHas('pessoas', [
            'id' => $pessoa->id,
            'nome' => 'Nome atualizado',
            'email' => 'atualizado@example.com',
        ]);

        $this->actingAs($user)
            ->delete(route('pessoas.destroy', $pessoa))
            ->assertRedirect(route('pessoas.index'));

        $this->assertDatabaseMissing('pessoas', ['id' => $pessoa->id]);
    }
}