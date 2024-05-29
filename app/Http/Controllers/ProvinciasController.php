<?php

namespace App\Http\Controllers;

use App\Models\Provincias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProvinciasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('provincias')
         ->select('provincias.*')
         ->orderBy('provincias.nome',);


 $provincias = $query->get();
 $dadosPersonalizados = [];

 foreach ($provincias as $provincia) {
     $dadosPersonalizados[] = [
         'id' => $provincia->id,
         'nome' => $provincia->nome,
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
        $provincia = new Provincias();

        $provincia->nome = $request->nome;

        $provincia->save();

        return response()->json(['message' => 'Província Cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $provincia = Provincias::find($id);
        if (!$provincia) {
            return response(['message' => 'Provincia não encontrada'], 404);
        }

        return response()->json($provincia, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function edit(Provincias $provincias)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $provincia = Provincias::find($id);
        if (!$provincia) {
            return response(['message' => 'Provincia não encontrada'], 404);
        }

        $provincia->nome = $request->nome;

        $provincia->save();

        return response()->json(['message' => 'Província actualizada com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provincias  $provincias
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    // Verifica se o registro existe
    $provincia = Provincias::find($id);

    if(!$provincia) {
        return response()->json(['message' => 'Província não encontrada.'], 404);
    }

    try {
        // Tenta excluir o registro
        $provincia->delete();
        return response()->json(['message' => 'Província Eliminada com sucesso!'], 200);
    } catch (\Exception $e) {
        // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
        return response()->json(['message' => 'Não é possível excluir a província. Está sendo usada em outra tabela.'], 500);
    }
}

}
