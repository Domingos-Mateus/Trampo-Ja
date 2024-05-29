<?php

namespace App\Http\Controllers;

use App\Models\Servicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServicosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicos = DB::table('servicos')
            ->select('servicos.*')
            ->orderBy('servicos.nome')
            ->paginate(25);
        $dadosPersonalizados = $servicos->map(function ($servico) {
            return [
                'id' => $servico->id,
                'nome' => $servico->nome,
                'imagem' => $servico->imagem ? env('URL_BASE_SERVIDOR') . '/' . $servico->imagem : null,
            ];
        });

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
        // Verifica se já existe um serviço com o mesmo nome na base de dados
        $servicoExistente = Servicos::where('nome', $request->nome)->first();

        if ($servicoExistente) {
            return response()->json(['message' => 'Já existe um serviço com esse nome.'], 400);
        }

        // Se não existir, continua com a criação do novo serviço
        $servico = new Servicos();

        $servico->nome = $request->nome;

        $servico->save();

        return response()->json(['message' => 'Serviço Cadastrado com sucesso!'], 200);
    }



    public function uploadFotoServico(Request $request, $id)
    {
        // Encontra o serviço pelo ID
        $servico = Servicos::find($id);

        // Verifica se o serviço existe
        if (!$servico) {
            return response(['message' => 'Serviço não encontrado'], 404);
        }

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');

            // Valida a extensão do arquivo
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'foto-servico-' . $id . '.' . $extension;

            // Move o arquivo para o diretório de destino
            $file->move('uploads/servico/', $filename);

            $servico->imagem = 'uploads/servico/' . $filename;
            $servico->save();
        }

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Foto do serviço enviada com sucesso'], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Servicos  $servicos
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $servico = Servicos::find($id);
        if (!$servico) {
            return response(['message' => 'servico não encontrada'], 404);
        }

        return response()->json($servico, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Servicos  $servicos
     * @return \Illuminate\Http\Response
     */
    public function edit(Servicos $servicos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Servicos  $servicos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Encontra o serviço pelo ID
        $servico = Servicos::find($id);

        if (!$servico) {
            return response(['message' => 'Serviço não encontrado'], 404);
        }

        $nomeExistente = Servicos::where('nome', $request->nome)->where('id', '!=', $id)->first();

        if ($nomeExistente) {
            return response()->json(['message' => 'Já existe um serviço com esse nome.'], 400);
        }

        // Se o nome não estiver sendo usado por outro serviço, continua com a atualização
        $servico->nome = $request->nome;

        $servico->save();

        return response()->json(['message' => 'Serviço Atualizado com sucesso!'], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Servicos  $servicos
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $servico = Servicos::find($id);

        if (!$servico) {
            return response()->json(['message' => 'Serviço não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $servico->delete();
            return response()->json(['message' => 'Serviço Eliminado com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir o Serviço. Está sendo usada em outra tabela.'], 500);
        }
    }
}
