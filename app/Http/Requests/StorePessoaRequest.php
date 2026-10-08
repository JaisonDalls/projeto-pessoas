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
        $nome = $this->input('nome');

        $this->merge([
            'nome' => is_string($nome) ? mb_convert_case($nome, MB_CASE_TITLE, 'UTF-8') : $nome,
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
                function ($attribute, $value, $fail) {
                    if (! is_string($value) || ! $this->hasValidDocumentCheckDigits($value)) {
                        $fail('Informe um '.$this->documentName().' válido.');
                    }
                },
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
            'cpf.digits' => 'Informe um '.$this->documentName().' válido.',
            'cpf.unique' => 'Este '.$this->documentName().' já está cadastrado para outra pessoa.',
            'tipo.required' => 'Selecione o tipo de pessoa.',
            'tipo.in' => 'O tipo informado deve ser física ou jurídica.',
            'telefone.digits_between' => 'Informe um telefone com DDD e 8 ou 9 dígitos.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
        ];
    }

    private function documentName(): string
    {
        return $this->input('tipo') === 'jurídica' ? 'CNPJ' : 'CPF';
    }

    private function hasValidDocumentCheckDigits(string $document): bool
    {
        if (preg_match('/^(\d)\1+$/', $document)) {
            return false;
        }

        if ($this->input('tipo') === 'jurídica') {
            if (strlen($document) !== 14) {
                return false;
            }

            $firstDigit = $this->calculateCheckDigit(substr($document, 0, 12), [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
            $secondDigit = $this->calculateCheckDigit(substr($document, 0, 13), [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);

            return $document[12] === (string) $firstDigit
                && $document[13] === (string) $secondDigit;
        }

        if (strlen($document) !== 11) {
            return false;
        }

        $firstDigit = $this->calculateCheckDigit(substr($document, 0, 9), range(10, 2));
        $secondDigit = $this->calculateCheckDigit(substr($document, 0, 10), range(11, 2));

        return $document[9] === (string) $firstDigit
            && $document[10] === (string) $secondDigit;
    }

    /**
     * @param  list<int>  $weights
     */
    private function calculateCheckDigit(string $base, array $weights): int
    {
        $sum = 0;

        foreach ($weights as $index => $weight) {
            $sum += (int) $base[$index] * $weight;
        }

        $remainder = $sum % 11;

        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
