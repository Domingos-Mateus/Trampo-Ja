<?php

namespace App\Http\Controllers;

use App\Models\Empreendedores;
use App\Models\Empresas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('empresas')
        ->join('empreendedores', 'empreendedores.id', 'empresas.empreendedor_id')
            ->select(
                'empresas.*',
                'empreendedores.nome as nome_empreendedor',
            )
            ->orderBy('empresas.nome',);


        $empresas = $query->get();
        $dadosPersonalizados = [];

        foreach ($empresas as $empresa) {
            $dadosPersonalizados[] = [
                'id' => $empresa->id,
                'empreendedor_id' => $empresa->empreendedor_id,
                'nome_empreendedor' => $empresa->nome_empreendedor,
                'nome' => $empresa->nome,
                'foto' => $empresa->foto ? env('URL_BASE_SERVIDOR') . '/' . $empresa->foto : null,
                'video' => $empresa->video ? env('URL_BASE_SERVIDOR') . '/' . $empresa->video : null,
                'ano_criacao' => $empresa->ano_criacao,
                'link_website' => $empresa->link_website,
                'link_facebook' => $empresa->link_facebook,
                'tamanho_empresa' => $empresa->tamanho_empresa,
                'descricao_curta' => $empresa->descricao_curta,
                'descricao_longa' => $empresa->descricao_longa,
                'status' => $empresa->status,
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
        $empreendedorExistente = Empreendedores::find($request->empreendedor_id);

        if(!$empreendedorExistente) {
            return response()->json(['message' => 'O empreendedor com o ID fornecido não existe.'], 404);
        }
        $empresa = new Empresas;

        $empresa->empreendedor_id = $request->empreendedor_id;
        $empresa->nome = $request->nome;


        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'foto-' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/empresa/foto', $filename);

            $empresa->foto = 'uploads/empresa/foto' . $filename;
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $allowedExtensions = ['mp4', 'avi', 'gif'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos MP4, AVI e GIF são permitidos.'], 400);
            }

            $filename = 'video' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/empresa/video', $filename);

            $empresa->video = 'uploads/empresa/video' . $filename;
        }
        $empresa->ano_criacao = $request->ano_criacao;
        $empresa->link_website = $request->link_website;
        $empresa->link_facebook = $request->link_facebook;
        $empresa->tamanho_empresa = $request->tamanho_empresa;
        $empresa->descricao_curta = $request->descricao_curta;
        $empresa->descricao_longa = $request->descricao_longa;
        $empresa->status = $request->status;
        $empresa->save();
        return response()->json(['message' => 'Empresa cadastrada com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empresas  $empresas
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $empresa = Empresas::find($id);
        if (!$empresa) {
            return response(['message' => 'Empresa não encontrado'], 404);
        }
        return $empresa;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empresas  $empresas
     * @return \Illuminate\Http\Response
     */
    public function edit(Empresas $empresas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empresas  $empresas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $empresa = Empresas::find($id);
        if (!$empresa) {
            return response(['message' => 'Empresa não encontrado'], 404);
        }
        $empresa->empreendedor_id = $request->empreendedor_id;
        $empresa->nome = $request->nome;
        $empresa->ano_criacao = $request->ano_criacao;
        $empresa->link_website = $request->link_website;
        $empresa->link_facebook = $request->link_facebook;
        $empresa->tamanho_empresa = $request->tamanho_empresa;
        $empresa->descricao_curta = $request->descricao_curta;
        $empresa->descricao_longa = $request->descricao_longa;
        $empresa->status = $request->status;
        $empresa->save();
        return response()->json(['message' => 'Empresa Actualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empresas  $empresas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empresa = Empresas::find($id);

        if (!$empresa) {
            return response()->json(['message' => 'Empresa não encontrado.'], 404);
        }

        try {
            // Tenta excluir o registro
            $empresa->delete();
            return response()->json(['message' => 'Empresa Eliminada com sucesso!'], 200);
        } catch (\Exception $e) {
            // Se ocorrer um erro ao excluir devido a restrições de chave estrangeira
            return response()->json(['message' => 'Não é possível excluir a Empresa. Está sendo usado em outra tabela.'], 500);
        }
    }
}
