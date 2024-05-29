<?php

namespace App\Http\Controllers;

use App\Models\Bairros;
use App\Models\Cidades;
use App\Models\Profissionais;
use App\Models\Provincias;
use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfissionaisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('profissionais')
            ->join('servicos', 'servicos.id', 'profissionais.servico_id')
            ->join('provincias', 'provincias.id', 'profissionais.provincia_id')
            ->join('cidades', 'cidades.id', 'profissionais.cidade_id')
            ->join('bairros', 'bairros.id', 'profissionais.bairro_id')
            ->select(
                'profissionais.*',
                'servicos.nome as nome_servico',
                'provincias.nome as nome_provincia',
                'cidades.nome as nome_cidade',
                'bairros.nome as nome_bairro'
            )
            ->orderBy('profissionais.nome',);


        $profissionais = $query->get();
        $dadosPersonalizados = [];

        foreach ($profissionais as $profissional) {
            $dadosPersonalizados[] = [
                'id' => $profissional->id,
                'nome' => $profissional->nome,
                'servico_id' => $profissional->servico_id,
                'nome_servico' => $profissional->nome_servico,
                'provincia_id' => $profissional->provincia_id,
                'nome_provincia' => $profissional->nome_provincia,
                'cidade_id' => $profissional->cidade_id,
                'nome_cidade' => $profissional->nome_cidade,
                'bairro_id' => $profissional->bairro_id,
                'nome_bairro' => $profissional->nome_bairro,
                'foto_perfil' => $profissional->foto_perfil ? env('URL_BASE_SERVIDOR') . '/' . $profissional->foto_perfil : null,
                'telefone' => $profissional->telefone,
                'usuario_id' => $profissional->usuario_id,
                'status' => $profissional->status,

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
        $servicoExistente = Servicos::find($request->servico_id);

        if (!$servicoExistente) {
            return response()->json(['message' => 'O serviço com o ID fornecido não existe.'], 404);
        }

        $provinciaExistente = Provincias::find($request->provincia_id);

        if (!$provinciaExistente) {
            return response()->json(['message' => 'A província com o ID fornecido não existe.'], 404);
        }

        $cidadeExistente = Cidades::find($request->cidade_id);

        if (!$cidadeExistente) {
            return response()->json(['message' => 'A cidade com o ID fornecido não existe.'], 404);
        }

        $bairroExistente = Bairros::find($request->bairro_id);

        if (!$bairroExistente) {
            return response()->json(['message' => 'O Bairro com o ID fornecido não existe.'], 404);
        }

        $profissional = new Profissionais();

        $profissional->nome = $request->nome;
        $profissional->servico_id = $request->servico_id;
        $profissional->provincia_id = $request->provincia_id;
        $profissional->cidade_id = $request->cidade_id;
        $profissional->bairro_id = $request->bairro_id;
        $profissional->telefone = $request->telefone;
        $profissional->usuario_id = $request->usuario_id;
        //$profissional->status = $request->status;

        $profissional->save();

        return response()->json(['message' => 'Profissional Cadastrado com sucesso!'], 200);
    }




    public function uploadFotoPerfil(Request $request, $id)
    {
        $profissional = Profissionais::find($id);

        if (!$profissional) {
            return response(['message' => 'Profissional não encontrado'], 404);
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

            $profissional->foto_perfil = 'uploads/profissional/' . $filename;
            $profissional->save();
        }

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Foto do perfil enviada com sucesso'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Profissionais  $profissionais
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $profissional = Profissionais::find($id);
        if (!$profissional) {
            return response(['message' => 'Profissional não encontrado'], 404);
        }

        //Para Mostrar os nomes dos relacionamentos
        $servico = Servicos::find($profissional->servico_id);
        $provincia = Provincias::find($profissional->provincia_id);
        $cidade = Cidades::find($profissional->cidade_id);
        $bairro = Bairros::find($profissional->bairro_id);


        $dadosPersonalizados[] = [
            'id' => $profissional->id,
            'nome' => $profissional->nome,
            'servico_id' => $profissional->servico_id,
            'nome_servico' => $servico->nome,
            'provincia_id' => $profissional->provincia_id,
            'nome_provincia' => $provincia->nome,
            'cidade_id' => $profissional->cidade_id,
            'nome_cidade' => $cidade->nome,
            'bairro_id' => $profissional->bairro_id,
            'nome_bairro' => $bairro->nome,
            'telefone' => $profissional->telefone,
            'usuario_id' => $profissional->usuario_id,
            'status' => $profissional->status,
            'foto_perfil' => $profissional->foto_perfil ? env('URL_BASE_SERVIDOR') . '/' . $profissional->foto_perfil : null,


        ];

        return response()->json($dadosPersonalizados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Profissionais  $profissionais
     * @return \Illuminate\Http\Response
     */
    public function edit(Profissionais $profissionais)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Profissionais  $profissionais
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $profissional = Profissionais::find($id);
        if (!$profissional) {
            return response(['message' => 'Profissional não encontrado'], 404);
        }

        $profissional->nome = $request->nome;
        $profissional->servico_id = $request->servico_id;
        $profissional->provincia_id = $request->provincia_id;
        $profissional->cidade_id = $request->cidade_id;
        $profissional->bairro_id = $request->bairro_id;
        $profissional->telefone = $request->telefone;
        $profissional->usuario_id = $request->usuario_id;
        $profissional->status = $request->status;

        $profissional->save();

        return response()->json(['message' => 'Profissional Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Profissionais  $profissionais
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $profissional = Profissionais::find($id);

        if (!$profissional) {
            return response()->json(['message' => 'Profissional não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $profissional->delete();
            return response()->json(['message' => 'Profissional Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o profissional. Está sendo usada em outra tabela.'], 500);
        }
    }
}
