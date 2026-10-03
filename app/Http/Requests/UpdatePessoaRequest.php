<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdatePessoaRequest extends StorePessoaRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $pessoa = $this->route('pessoa');

        $rules['cpf'][2] = Rule::unique('pessoas', 'cpf')->ignore($pessoa);

        return $rules;
    }
}
