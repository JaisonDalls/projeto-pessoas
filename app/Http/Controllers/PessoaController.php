<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Models\Pessoa;
use Inertia\Inertia;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Recupera as pessoas da tabela paginadas e com algum filtro ordenadas pelo último registro.
        $pessoas = Pessoa::query()->latest()->paginate(5)->withQueryString();

        return Inertia::render('Pessoas/index', ['pessoas' => $pessoas]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Pessoas/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePessoaRequest $request)
    {
        Pessoa::create($request->validated());

        return redirect()->route('pessoa.index')->with('success', "Pessoa {$request->nome}  cadastrada com sucesso!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Pessoa $pessoa)
    {
        return Inertia::render('Pessoas/Show',['pessoa' => $pessoa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pessoa $pessoa)
    {
        return Inertia::render('Pessoa/Edit', ['pessoa' => $pessoa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePessoaRequest $request, Pessoa $pessoa)
    {
        $pessoa->update($request->validated());

        return redirect()->route('pessoa.index')->with('success', "Pessoa {$pessoa->nome} atualizada com sucesso!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        return redirect()->route('pessoa.index')->with('success', "Pessoa {$pessoa->nome} removida com sucesso!");
    }
}
