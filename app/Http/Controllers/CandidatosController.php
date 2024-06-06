<?php

namespace App\Http\Controllers;

use App\Models\Candidatos;
use App\Models\Cidades;
use App\Models\Provincias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidatosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('candidatos')
        ->select('candidatos.*')
        ->orderBy('candidatos.nome');

    $candidatos = $query->get();
    $dadosPersonalizados = [];

    foreach ($candidatos as $candidato) {
        $dadosPersonalizados[] = [
            'id' => $candidato->id,
            'nome' => $candidato->nome,
            'email' => $candidato->email,
            'foto_perfil' => $candidato->foto_perfil ? env('URL_BASE_SERVIDOR') . '/' . $candidato->foto_perfil : null,
            'telefone' => $candidato->telefone,
            'status' => $candidato->status,
            'video_apresentacao' => $candidato->video_apresentacao,
            'created_at' => $candidato->created_at,
            'updated_at' => $candidato->updated_at,
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

        $candidato = new Candidatos;

        $candidato->nome = $request->nome;
        $candidato->email = $request->email;
        $candidato->senha = $request->senha;
        $candidato->telefone = $request->telefone;
        $candidato->status = $request->status;
        $candidato->video_apresentacao = $request->video_apresentacao;
        $candidato->save();

        return response()->json(['message' => 'Candidato Cadastrado com sucesso!'], 200);
    }


    public function uploadFotoPerfil(Request $request, $id)
    {
        $candidato = Candidatos::find($id);

        if (!$candidato) {
            return response(['message' => 'candidato não encontrado'], 404);
        }

        if ($request->hasFile('foto_perfil')) {
            $file = $request->file('foto_perfil');

            // Valida a extensão do arquivo
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'foto-perfil-' . $id . '.' . $extension;

            // Move o arquivo para o diretório de destino
            $file->move('uploads/profissional/', $filename);

            $candidato->foto_perfil = 'uploads/profissional/' . $filename;
            $candidato->save();
        }

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Foto do perfil enviada com sucesso'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Candidatos  $candidatos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         //
         $candidato = Candidatos::find($id);
         if (!$candidato) {
             return response(['message' => 'candidato não encontrada'], 404);
         }

         return response()->json($candidato, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Candidatos  $candidatos
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidatos $candidatos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Candidatos  $candidatos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $candidato = Candidatos::find($id);
        if (!$candidato) {
            return response(['message' => 'candidato não encontrado'], 404);
        }

        $candidato->nome = $request->nome;
        $candidato->email = $request->email;
        $candidato->senha = $request->senha;
        $candidato->telefone = $request->telefone;
        $candidato->status = $request->status;
        $candidato->video_apresentacao = $request->video_apresentacao;
        $candidato->save();

        return response()->json(['message' => 'Candidato Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Candidatos  $candidatos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
         //
         $candidato = Candidatos::find($id);

         if (!$candidato) {
             return response()->json(['message' => 'candidato não encontrado.'], 404);
         }

         try {
             // Tenta excluir o registro
             $candidato->delete();
             return response()->json(['message' => 'candidato Eliminado com sucesso!'], 200);
         } catch (\Exception $e) {
             // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
             return response()->json(['message' => 'Não é possível excluir o candidato. Está sendo usada em outra tabela.'], 500);
         }
    }
}
