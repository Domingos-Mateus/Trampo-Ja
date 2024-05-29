<?php

namespace App\Http\Controllers;

use App\Models\Cidades;
use App\Models\Provincias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('cidades')
            ->join('provincias', 'provincias.id', 'cidades.provincia_id')
            ->select(
                'cidades.*',
                'provincias.nome as nome_provincia'
            )
            ->orderBy('cidades.nome');


        $cidades = $query->get();
        $dadosPersonalizados = [];

        foreach ($cidades as $cidade) {
            $dadosPersonalizados[] = [
                'id' => $cidade->id,
                'nome' => $cidade->nome,
                'provincia_id' => $cidade->provincia_id,
                'nome_provincia' => $cidade->nome_provincia,
                'total_funcionarios' => $cidade->total_funcionarios,
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
        // Verifica se a província com o ID fornecido existe
    $provinciaExistente = Provincias::find($request->provincia_id);

    if(!$provinciaExistente) {
        return response()->json(['message' => 'A província com o ID fornecido não existe.'], 404);
    }

        $cidade = new Cidades();

        $cidade->nome = $request->nome;
        $cidade->provincia_id = $request->provincia_id;

        $cidade->save();

        return response()->json(['message' => 'Cidade Cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cidades  $cidades
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $cidade = Cidades::find($id);
        if (!$cidade) {
            return response(['message' => 'cidade não encontrada'], 404);
        }

        //Para Mostrar os nomes dos relacionamentos
        $provincia = Provincias::find($cidade->provincia_id);


        $dadosPersonalizados[] = [
            'id' => $cidade->id,
            'nome' => $cidade->nome,
            'provincia_id' => $cidade->provincia_id,
            'nome_provincia' => $provincia->nome,
            'total_funcionarios' => $cidade->total_funcionarios,

        ];

        return response()->json($dadosPersonalizados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cidades  $cidades
     * @return \Illuminate\Http\Response
     */
    public function edit(Cidades $cidades)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cidades  $cidades
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $cidade = Cidades::find($id);
        if (!$cidade) {
            return response(['message' => 'cidade não encontrada'], 404);
        }
        $cidade->nome = $request->nome;
        $cidade->provincia_id = $request->provincia_id;

        $cidade->save();

        return response()->json(['message' => 'Cidade Actualizada com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cidades  $cidades
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
         // Verifica se o registro existe
    $cidade = Cidades::find($id);

    if(!$cidade) {
        return response()->json(['message' => 'Cidade não encontrada.'], 404);
    }

    try {
        // Tenta excluir o registro
        $cidade->delete();
        return response()->json(['message' => 'Cidade Eliminada com sucesso!'], 200);
    } catch (\Exception $e) {
        // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
        return response()->json(['message' => 'Não é possível excluir a Cidade. Está sendo usada em outra tabela.'], 500);
    }
    }
}
