<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use App\Models\Vagas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VagasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $query = DB::table('vagas')
            ->join('empresas', 'empresas.id', '=', 'vagas.empresa_id')
            ->select(
                'vagas.*',
                'empresas.nome as nome_empresa'
            )
            ->orderBy('vagas.titulo');

        $vagas = $query->get();
        $dadosPersonalizados = [];

        foreach ($vagas as $vaga) {
            $dadosPersonalizados[] = [
                'id' => $vaga->id,
                'empresa_id' => $vaga->empresa_id,
                'nome_empresa' => $vaga->nome_empresa,
                'titulo' => $vaga->titulo,
                'descricao' => $vaga->descricao,
                'requisitos' => $vaga->requisitos,
                'salario' => $vaga->salario,
                'localizacao' => $vaga->localizacao,
                'dias_disponiveis' => $vaga->dias_disponiveis,
                'data_expiracao' => $vaga->data_expiracao,
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
        $empresaExistente = Empresas::find($request->empresa_id);

        if (!$empresaExistente) {
            return response()->json(['message' => 'A empresa com o ID fornecido não existe.'], 404);
        }

        $vaga = new Vagas;
        $vaga->empresa_id = $request->empresa_id;
        $vaga->titulo = $request->titulo;
        $vaga->descricao = $request->descricao;
        $vaga->requisitos = $request->requisitos;
        $vaga->salario = $request->salario;
        $vaga->localizacao = $request->localizacao;
        $vaga->dias_disponiveis = $request->dias_disponiveis;
        $vaga->save();

        return response()->json(['message' => 'Vaga cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vagas  $vagas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $vaga = Vagas::find($id);
        if (!$vaga) {
            return response(['message' => 'Vaga não encontrada'], 404);
        }

        return response()->json($vaga, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vagas  $vagas
     * @return \Illuminate\Http\Response
     */
    public function edit(Vagas $vagas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vagas  $vagas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $vaga = Vagas::find($id);
        if (!$vaga) {
            return response()->json(['message' => 'Vaga não encontrada'], 404);
        }

        $vaga->empresa_id = $request->empresa_id;
        $vaga->titulo = $request->titulo;
        $vaga->descricao = $request->descricao;
        $vaga->requisitos = $request->requisitos;
        $vaga->salario = $request->salario;
        $vaga->localizacao = $request->localizacao;
        $vaga->dias_disponiveis = $request->dias_disponiveis;
        $vaga->save();

        return response()->json(['message' => 'Vaga atualizada com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vagas  $vagas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $vaga = Vagas::find($id);

        if (!$vaga) {
            return response()->json(['message' => 'Vaga não encontrada.'], 404);
        }

        try {
            // Tenta excluir o registro
            $vaga->delete();
            return response()->json(['message' => 'Vaga Eliminada com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir a vaga. Está sendo usada em outra tabela.'], 500);
        }
    }
}
