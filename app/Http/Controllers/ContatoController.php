<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contato;

class ContatoController extends Controller
{
    public function index()
    {
        // Listar todos os contatos
        $contatos = Contato::with('empresa')->get();
        return response()->json($contatos);
    }

    public function show($id)
    {
        // Exibir um contato específico por ID
        $contato = Contato::find($id)::with('empresa')->get();
        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }
        return response()->json($contato);
    }

    public function store(Request $request)
    {
        // Criar um novo contato
        $data = $request->validate([
            'nome' => 'required',
            'sobrenome' => 'required',
            'data_nascimento' => 'string',
            'telefone' => 'string',
            'celular' => 'string',
            'email' => 'email',
            'empresa_id' => 'integer'
        ]);

        $contato = Contato::create($data);
        
        return response()->json($contato, 201);
    }

    public function update(Request $request, $id)
    {
        $contato = Contato::find($id);
        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }

        $data = $request->validate([
            'nome' => 'required',
            'sobrenome' => 'required',
            'data_nascimento' => 'date',
            'telefone' => 'string',
            'celular' => 'string',
            'email' => 'email',
            'empresa_id' => 'integer'
        ]);

        $contato->update($data);
        return response()->json($contato);
    }

    public function destroy($id)
    {
       
        $contato = Contato::find($id);
        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado'], 404);
        }

        $contato->delete();
        return response()->json(['message' => 'Contato excluído']);
    }

}

