<?php

namespace App\Http\Controllers;

use App\Models\Bairros;
use App\Models\Cidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BairrosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('bairros')
        ->join('cidades', 'cidades.id', 'bairros.cidade_id')
        ->select(
            'bairros.*',
            'cidades.nome as nome_cidade'
        )
        ->orderBy('bairros.nome',);


    $bairros = $query->get();
    $dadosPersonalizados = [];

    foreach ($bairros as $bairro) {
        $dadosPersonalizados[] = [
            'id' => $bairro->id,
            'nome' => $bairro->nome,
            'cidade_id' => $bairro->cidade_id,
            'nome_cidade' => $bairro->nome_cidade,
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
        $cidadeExistente = Cidades::find($request->cidade_id);

    if(!$cidadeExistente) {
        return response()->json(['message' => 'A cidade com o ID fornecido não existe.'], 404);
    }

        $bairro = new Bairros();

        $bairro->nome = $request->nome;
        $bairro->cidade_id = $request->cidade_id;

        $bairro->save();

        return response()->json(['message' => 'Bairro Cadastrado com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bairros  $bairros
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $bairro = Bairros::find($id);
        if (!$bairro) {
            return response(['message' => 'bairro não encontrada'], 404);
        }

        //Para Mostrar os nomes dos relacionamentos
        $cidade = Cidades::find($bairro->cidade_id);


        $dadosPersonalizados[] = [
            'id' => $bairro->id,
            'nome' => $bairro->nome,
            'cidade_id' => $bairro->cidade_id,
            'nome_cidade' => $cidade->nome,

        ];

        return response()->json($dadosPersonalizados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bairros  $bairros
     * @return \Illuminate\Http\Response
     */
    public function edit(Bairros $bairros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bairros  $bairros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $bairro = Bairros::find($id);
        if (!$bairro) {
            return response(['message' => 'bairro não encontrada'], 404);
        }

        $bairro->nome = $request->nome;
        $bairro->cidade_id = $request->cidade_id;

        $bairro->save();

        return response()->json(['message' => 'Bairro Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bairros  $bairros
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $bairro = Bairros::find($id);

        if(!$bairro) {
            return response()->json(['message' => 'Bairro não encontrada.'], 404);
        }

        try {
            // Tenta excluir o registro
            $bairro->delete();
            return response()->json(['message' => 'Bairro Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o Bairro. Está sendo usado em outra tabela.'], 500);
        }
    }
}
