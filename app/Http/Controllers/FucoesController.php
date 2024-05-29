<?php

namespace App\Http\Controllers;

use App\Models\fucoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FucoesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('fucoes')
            ->select('fucoes.*')
            ->orderBy('fucoes.nome',);


        $funcoes = $query->get();
        $dadosPersonalizados = [];

        foreach ($funcoes as $funcao) {
            $dadosPersonalizados[] = [
                'id' => $funcao->id,
                'nome' => $funcao->nome,
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
        $funcao = new fucoes();

        $funcao->nome = $request->nome;

        $funcao->save();

        return response()->json(['message' => 'Função Cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\fucoes  $fucoes
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $funcao = fucoes::find($id);
        if (!$funcao) {
            return response(['message' => 'funcao não encontrada'], 404);
        }

        return response()->json($funcao, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\fucoes  $fucoes
     * @return \Illuminate\Http\Response
     */
    public function edit(fucoes $fucoes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\fucoes  $fucoes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $funcao = fucoes::find($id);
        if (!$funcao) {
            return response(['message' => 'funcao não encontrada'], 404);
        }
        $funcao->nome = $request->nome;
        $funcao->save();
        return response()->json(['message' => 'Função Actualizada com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\fucoes  $fucoes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // Verifica se o registro existe
    $funcao = fucoes::find($id);

    if(!$funcao) {
        return response()->json(['message' => 'Função não encontrada.'], 404);
    }

    try {
        // Tenta excluir o registro
        $funcao->delete();
        return response()->json(['message' => 'Função Eliminada com sucesso!'], 200);
    } catch (\Exception $e) {
        // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
        return response()->json(['message' => 'Não é possível excluir a Função. Está sendo usada em outra tabela.'], 500);
    }
    }
}
