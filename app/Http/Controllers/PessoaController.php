<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePessoaRequest;
use App\Http\Requests\UpdatePessoaRequest;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PessoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('search', 'tipo');

        $pessoas = Pessoa::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nome', 'like', "%{$search}%")
                        ->orWhere('cpf', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telefone', 'like', "%{$search}%");
                });
            })
            ->when($filters['tipo'] ?? null, fn ($query, $tipo) => $query->where('tipo', $tipo))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Pessoas/Index', [
            'pessoas' => $pessoas,
            'filters' => $filters,
        ]);
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
        $pessoa = Pessoa::create($request->validated());

        return redirect()->route('pessoas.show', $pessoa)->with('success', 'Pessoa cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pessoa $pessoa)
    {
        return Inertia::render('Pessoas/Show', ['pessoa' => $pessoa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pessoa $pessoa)
    {
        return Inertia::render('Pessoas/Edit', ['pessoa' => $pessoa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePessoaRequest $request, Pessoa $pessoa)
    {
        $pessoa->update($request->validated());

        return redirect()->route('pessoas.show', $pessoa)->with('success', 'Pessoa atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        return redirect()->route('pessoas.index')->with('success', 'Pessoa removida com sucesso.');
    }
}
