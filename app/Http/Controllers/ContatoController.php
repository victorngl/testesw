<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contato;

class ContatoController extends Controller
{
    public function index(Request $request)
    {
        // $contatos = Contato::with('empresa')->get();

        $request->validate([
            'empresa' => 'string',
            'nome_sobrenome' => 'string',
            'telefone' => 'string',
            'celular' => 'string',
            'email' => 'email',
        ]);

        $query = Contato::with('empresa');

        if ($request->has('empresa')) {
            $nomeEmpresa = $request->input('empresa');
            $query->whereHas('empresa', function ($q) use ($nomeEmpresa) {
                $q->where('nome', 'LIKE', "%$nomeEmpresa%");
            });
        }

        if ($request->has('nome_sobrenome')) {
            $nomeSobrenome = $request->input('nome_sobrenome');

            $query->where(function ($q) use ($nomeSobrenome) {
                $q->where('nome', 'LIKE', "%$nomeSobrenome%")
                    ->orWhere('sobrenome', 'LIKE', "%$nomeSobrenome%");
            });
        }

        if ($request->has('telefone')) {
            $telefone = $request->input('telefone');
            $query->where('telefone', 'LIKE', "%$telefone%");
        }

        if ($request->has('celular')) {
            $celular = $request->input('celular');
            $query->where('celular', 'LIKE', "%$celular%");
        }

        if ($request->has('email')) {
            $email = $request->input('email');
            $query->where('email', 'LIKE', "%$email%");
        }

        $contatos = $query->get();

        return response()->json($contatos);
    }

    public function show($id)
    {
        $contato = Contato::with('empresa')->find($id);

        if (!$contato) {
            return response()->json(['message' => 'Contato não encontrado.'], 404);
        }

        return response()->json($contato);
    }

    public function store(Request $request)
    {

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
            'data_nascimento' => 'string',
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
