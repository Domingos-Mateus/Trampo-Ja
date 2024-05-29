<?php

namespace App\Http\Controllers;

use App\Models\Perfils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('perfils')
            ->select(
                'perfils.*'
            );
        $perfils = $query->inRandomOrder()->get();
        $dadosPersonalizados = [];

        foreach ($perfils as $perfil) {
            $dadosPersonalizados[] = [
                'id' => $perfil->id,
                'usuario_id' => $perfil->usuario_id,
                'profissional_id' => $perfil->profissional_id,
                'servico_id' => $perfil->servico_id,
                'nome_usuario' => $perfil->nome_usuario,
                'idade' => $perfil->idade,
                'telefone' => $perfil->telefone,
                'foto' => $perfil->foto ? env('URL_BASE_SERVIDOR') . '/' . $perfil->foto : null,
                'descricao' => $perfil->descricao,
                'provincia_id' => $perfil->provincia_id,
                'cidade_id' => $perfil->cidade_id,
                'bairro_id' => $perfil->bairro_id,
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
        $perfil = new Perfils;

        $perfil->usuario_id = $request->usuario_id;
        $perfil->profissional_id = $request->profissional_id;
        $perfil->servico_id = $request->servico_id;
        $perfil->nome_usuario = $request->nome_usuario;
        $perfil->idade = $request->idade;
        $perfil->telefone = $request->telefone;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'foto-parceiro-' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/perfil/', $filename);

            $perfil->foto = 'uploads/perfil/' . $filename;
        }

        $perfil->descricao = $request->descricao;
        $perfil->provincia_id = $request->provincia_id;
        $perfil->cidade_id = $request->cidade_id;
        $perfil->bairro_id = $request->bairro_id;
        $perfil->save();
        return response()->json(['message' => 'Pesfil Cadastrado com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfils  $perfils
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $perfil = Perfils::find($id);
        if (!$perfil) {
            return response(['message' => 'perfil não encontrado'], 404);
        }
        return $perfil;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfils  $perfils
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfils $perfils)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfils  $perfils
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $perfil = Perfils::find($id);
        if (!$perfil) {
            return response(['message' => 'perfil não encontrado'], 404);
        }
         $perfil->usuario_id = $request->usuario_id;
        $perfil->profissional_id = $request->profissional_id;
        $perfil->servico_id = $request->servico_id;
        $perfil->nome_usuario = $request->nome_usuario;
        $perfil->idade = $request->idade;
        $perfil->telefone = $request->telefone;
        $perfil->descricao = $request->descricao;
        $perfil->provincia_id = $request->provincia_id;
        $perfil->cidade_id = $request->cidade_id;
        $perfil->bairro_id = $request->bairro_id;
        $perfil->save();
        return response()->json(['message' => 'Pesfil Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perfils  $perfils
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $perfil = Perfils::find($id);

        if (!$perfil) {
            return response()->json(['message' => 'Perfil não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $perfil->delete();
            return response()->json(['message' => 'Perfil Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o Perfil. Está sendo usado em outra tabela.'], 500);
        }
    }
    }

