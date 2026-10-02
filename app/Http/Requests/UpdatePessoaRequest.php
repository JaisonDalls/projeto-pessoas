<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePessoaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data validations
     * 
    */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => $this->cpf ? preg_replace('/\D/', '', $this->cpf) : null,
            'telefone' => $this->telefone ? preg_replace('/\D/', '', $this->telefone) : null
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $pessoaId = $this->route('pessoa')?->id ?? $this->route('pessoa');

        return [
            'nome' => ['required', 'string', 'min:3','max:265'],
            'cpf' => ['required', 'string', Rule::unique('pessoas', 'cpf')->ignore($pessoaId)],
            'tipo' => ['required', 'in:física,jurídica'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:265'],
        ];
    }

     /**
     * Mensagens de erro personalizadas.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'cpf.required' => 'O campo CPF/CNPJ é obrigatório.',
            'cpf.unique' => 'Este CPF/CNPJ já está cadastrado para outra pessoa.',
            'tipo.required' => 'Selecione o tipo de pessoa.',
            'tipo.in' => 'O tipo informado deve ser física ou jurídica.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
        ];
    }
}
