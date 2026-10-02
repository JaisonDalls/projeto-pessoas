<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StorePessoaRequest extends FormRequest
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
        return [
            'nome' => ['required', 'string', 'max:265'],
            'cpf' => ['required', 'string', 'unique:pessoas,cpf'],
            'tipo' => ['required', 'in:física,jurídica'],
            'telefone' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:265'],
        ];
    }

    /**
     * Error messages customization
     * 
    */
    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'cpf.required' => 'O campo CPF/CNPJ é obrigatório.',
            'tipo.required' => 'Selecione se a pessoa é Física ou Jurídica.',
            'tipo.in' => 'O tipo informado deve ser física ou jurídica.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.'
        ];
    }
}
