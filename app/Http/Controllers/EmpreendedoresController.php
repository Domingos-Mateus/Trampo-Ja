<?php

namespace App\Http\Controllers;

use App\Models\Empreendedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpreendedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('empreendedores')
            ->select(
                'empreendedores.*',
            )
            ->orderBy('empreendedores.nome',);


        $empreendedores = $query->get();
        $dadosPersonalizados = [];

        foreach ($empreendedores as $empreendedor) {
            $dadosPersonalizados[] = [
                'id' => $empreendedor->id,
                'nome' => $empreendedor->nome,
                'nif' => $empreendedor->nif,
                'tipo_empreendedor' => $empreendedor->tipo_empreendedor,
                'foto' => $empreendedor->foto ? env('URL_BASE_SERVIDOR') . '/' . $empreendedor->foto : null,
                'descricao_empreendimento' => $empreendedor->descricao_empreendimento,
                'user_id' => $empreendedor->user_id,
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
        $empreendedor = new Empreendedores;

        $empreendedor->nome = $request->nome;
        $empreendedor->nif = $request->nif;
        $empreendedor->tipo_empreendedor = $request->tipo_empreendedor;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'foto-parceiro-' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/empreendedor/', $filename);

            $empreendedor->foto = 'uploads/empreendedor/' . $filename;
        }
        $empreendedor->descricao_empreendimento = $request->descricao_empreendimento;
        $empreendedor->user_id = $request->user_id;
        $empreendedor->save();
        return response()->json(['message' => 'Empreendedor cadastrado com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empreendedores  $empreendedores
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $empreendedor = Empreendedores::find($id);
        if (!$empreendedor) {
            return response(['message' => 'Empreendedor não encontrado'], 404);
        }
        return $empreendedor;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empreendedores  $empreendedores
     * @return \Illuminate\Http\Response
     */
    public function edit(Empreendedores $empreendedores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empreendedores  $empreendedores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $empreendedor = Empreendedores::find($id);
        if (!$empreendedor) {
            return response(['message' => 'Empreendedor não encontrado'], 404);
        }

        $empreendedor->nome = $request->nome;
        $empreendedor->nif = $request->nif;
        $empreendedor->tipo_empreendedor = $request->tipo_empreendedor;
        $empreendedor->descricao_empreendimento = $request->descricao_empreendimento;
        $empreendedor->user_id = $request->user_id;
        $empreendedor->save();

        return response()->json(['message' => 'Empreendedor Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empreendedores  $empreendedores
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empreendedor = Empreendedores::find($id);

        if (!$empreendedor) {
            return response()->json(['message' => 'Empreendedor não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $empreendedor->delete();
            return response()->json(['message' => 'Empreendedor Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o Empreendedor. Está sendo usado em outra tabela.'], 500);
        }
    }
}
