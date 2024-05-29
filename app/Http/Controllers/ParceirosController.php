<?php

namespace App\Http\Controllers;

use App\Models\Parceiros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParceirosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('parceiros')
            ->select(
                'parceiros.*'
            );
        $parceiros = $query->get();
        $dadosPersonalizados = [];

        foreach ($parceiros as $parceiro) {
            $dadosPersonalizados[] = [
                'id' => $parceiro->id,
                'nome' => $parceiro->nome,
                'descricao' => $parceiro->descricao,
                'imagem' => $parceiro->imagem ? env('URL_BASE_SERVIDOR') . '/' . $parceiro->imagem : null,
            ];
        }
        return response()->json($dadosPersonalizados);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $parceiros = new Parceiros;

        $parceiros->nome = $request->nome;
        $parceiros->descricao = $request->descricao;

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'imagem-parceiro-' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/parceiros/', $filename);

            $parceiros->imagem = 'uploads/parceiros/' . $filename;
        }

        $parceiros->save();
        return response()->json(['message' => 'Parceiro Cadastrado com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Parceiros  $parceiros
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $parceiros = Parceiros::find($id);
        if (!$parceiros) {
            return response(['message' => 'Parceiros não encontrado'], 404);
        }
        return $parceiros;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Parceiros  $parceiros
     * @return \Illuminate\Http\Response
     */
    public function edit(Parceiros $parceiros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Parceiros  $parceiros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $parceiros = Parceiros::find($id);
        if (!$parceiros) {
            return response(['message' => 'Parceiros não encontrado'], 404);
        }

        $parceiros->nome = $request->nome;
        $parceiros->descricao = $request->descricao;

        $parceiros->save();
        return response()->json(['message' => 'Parceiro Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Parceiros  $parceiros
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $parceiros = Parceiros::find($id);

        if (!$parceiros) {
            return response()->json(['message' => 'Parceiro não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $parceiros->delete();
            return response()->json(['message' => 'Parceiro Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o parceiro. Está sendo usado em outra tabela.'], 500);
        }
    }
}
