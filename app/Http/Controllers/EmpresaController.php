<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function index()
    {
        // Listar todas as empresas
        $empresas = Empresa::with('contatos')->get();
        return response()->json($empresas);
    }

    public function show($id)
    {
        // Exibir uma empresa específica por ID
        $empresa = Empresa::find($id)::with('contatos')->get();
        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }
        return response()->json($empresa);
    }

    public function store(Request $request)
    {
        // Criar uma nova empresa
        $data = $request->validate([
            'nome' => 'required',
        ]);

        $empresa = Empresa::create($data);

        return response()->json($empresa, 201);
    }

    public function update(Request $request, $id)
    {
        
        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        $data = $request->validate([
            'nome' => 'required',
        ]);

        $empresa->update($data);
        return response()->json($empresa);
    }

    public function destroy($id)
    {

        $empresa = Empresa::find($id);
        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrada'], 404);
        }

        $empresa->delete();
        return response()->json(['message' => 'Empresa excluída']);
    }
}
