<?php

namespace App\Http\Controllers;

use App\Models\ImagensServicos;
use App\Models\Profissionais;
use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImagensServicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('imagens_servicos')
            ->join('servicos', 'servicos.id', 'imagens_servicos.servico_id')
            ->join('profissionais', 'profissionais.id', 'imagens_servicos.profissional_id')
            ->select(
                'imagens_servicos.*',
                'servicos.nome as nome_servico',
                'profissionais.nome as nome_profissional'
            );


        $imagens_servicos = $query->get();
        $dadosPersonalizados = [];

        foreach ($imagens_servicos as $imagens_servico) {
            $dadosPersonalizados[] = [
                'id' => $imagens_servico->id,
                'descricao' => $imagens_servico->descricao,
                'imagem' => $imagens_servico->imagem ? env('URL_BASE_SERVIDOR') . '/' . $imagens_servico->imagem : null,
                'servico_id' => $imagens_servico->servico_id,
                'nome_servico' => $imagens_servico->nome_servico,
                'profissional_id' => $imagens_servico->profissional_id,
                'nome_profissional' => $imagens_servico->nome_profissional,

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

        $profissionalExistente = Profissionais::find($request->profissional_id);

        if (!$profissionalExistente) {
            return response()->json(['message' => 'O profissional com o ID fornecido não existe.'], 404);
        }

        $imagem_servico = new ImagensServicos();

        $imagem_servico->descricao = $request->descricao;


        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');

            // Valida a extensão do arquivo
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'imagem-servico-' . '.' . $extension;

            // Move o arquivo para o diretório de destino
            $file->move('uploads/imagem-servico/', $filename);

            $imagem_servico->imagem = 'uploads/imagem-servico/' . $filename;
           // $imagem_servico->save();
        }

        $imagem_servico->servico_id = $request->servico_id;
        $imagem_servico->profissional_id = $request->profissional_id;

        $imagem_servico->save();

        return response()->json(['message' => 'Imagem do Serviço Cadastrado com sucesso!'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImagensServicos  $imagensServicos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $imagem_servico = ImagensServicos::find($id);
        if (!$imagem_servico) {
            return response(['message' => 'Imagem do Serviço não encontrado'], 404);
        }

        //Para Mostrar os nomes dos relacionamentos
        $servico = Servicos::find($imagem_servico->servico_id);
        $profissional = Profissionais::find($imagem_servico->profissional_id);


        $dadosPersonalizados[] = [
            'id' => $imagem_servico->id,
            'descricao' => $imagem_servico->descricao,
            'foto_perfil' => $imagem_servico->imagem ? env('URL_BASE_SERVIDOR') . '/' . $imagem_servico->imagem : null,
            'servico_id' => $imagem_servico->servico_id,
            'nome_servico' => $servico->nome,
            'profissional_id' => $imagem_servico->profissional_id,
            'nome_profissional' => $profissional->nome,


        ];

        return response()->json($dadosPersonalizados);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImagensServicos  $imagensServicos
     * @return \Illuminate\Http\Response
     */
    public function edit(ImagensServicos $imagensServicos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImagensServicos  $imagensServicos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $imagem_servico = ImagensServicos::find($id);
        if (!$imagem_servico) {
            return response(['message' => 'Imagem do Serviço não encontrado'], 404);
        }

        $imagem_servico->descricao = $request->descricao;


        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');

            // Valida a extensão do arquivo
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'imagem-servico-' . '.' . $extension;

            // Move o arquivo para o diretório de destino
            $file->move('uploads/imagem-servico/', $filename);

            $imagem_servico->imagem = 'uploads/imagem-servico/' . $filename;
           // $imagem_servico->save();
        }

        $imagem_servico->servico_id = $request->servico_id;
        $imagem_servico->profissional_id = $request->profissional_id;

        $imagem_servico->save();

        return response()->json(['message' => 'Imagem do Serviço Actualizada com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImagensServicos  $imagensServicos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $imagem_servico = ImagensServicos::find($id);

        if (!$imagem_servico) {
            return response()->json(['message' => 'Imagem do Serviço não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $imagem_servico->delete();
            return response()->json(['message' => 'Imagem do serviço Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir a imagem do serviço. Está sendo usada em outra tabela.'], 500);
        }
    }
    }

