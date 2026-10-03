<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePessoaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => $this->cpf ? preg_replace('/\D/', '', $this->cpf) : null,
            'telefone' => $this->telefone ? preg_replace('/\D/', '', $this->telefone) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required',
                'digits:'.($this->input('tipo') === 'jurídica' ? 14 : 11),
                Rule::unique('pessoas', 'cpf'),
            ],
            'tipo' => ['required', Rule::in(['física', 'jurídica'])],
            'telefone' => ['nullable', 'digits_between:10,11'],
            'email' => ['required', 'email', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'cpf.required' => 'O campo CPF/CNPJ é obrigatório.',
            'cpf.digits' => 'Informe um CPF ou CNPJ válido.',
            'cpf.unique' => 'Este CPF/CNPJ já está cadastrado para outra pessoa.',
            'tipo.required' => 'Selecione o tipo de pessoa.',
            'tipo.in' => 'O tipo informado deve ser física ou jurídica.',
            'telefone.digits_between' => 'Informe um telefone com DDD e 8 ou 9 dígitos.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
        ];
    }
}
