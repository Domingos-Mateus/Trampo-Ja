<?php

namespace App\Http\Controllers;

use App\Models\Curriculos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurriculosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $query = DB::table('curriculos')
        ->join('profissionais', 'profissionais.id', 'curriculos.profissional_id')
            ->select(
                'curriculos.*',
                'profissionais.nome as nome_profissional'
            );
        $curriculos = $query->get();
        $dadosPersonalizados = [];

        foreach ($curriculos as $curriculo) {
            $dadosPersonalizados[] = [
                'id' => $curriculo->id,
                'distrito' => $curriculo->distrito,
                'profissao' => $curriculo->profissao,
                'cidade' => $curriculo->cidade,
                'bairro' => $curriculo->bairro,
                //'foto' => $curriculo->foto,
                'imagem' => $curriculo->imagem ? env('URL_BASE_SERVIDOR') . '/' . $curriculo->imagem : null,
                'video' => $curriculo->video,
                'minimo_hora' => $curriculo->minimo_hora,
                'faixa_etaria' => $curriculo->faixa_etaria,
                'escolaridade' => $curriculo->escolaridade,
                'instituicao_ensino' => $curriculo->instituicao_ensino,
                'sobre' => $curriculo->sobre,
                'habilidades' => $curriculo->habilidades,
                'codigo_postal' => $curriculo->codigo_postal,
                'ligacao' => $curriculo->ligacao,
                'total_views' => $curriculo->total_views,
                'formacao_academica' => $curriculo->formacao_academica,
                'anos_experiencia_profissional' => $curriculo->anos_experiencia_profissional,
                'profissional_id' => $curriculo->profissional_id,
                'nome_profissional' => $curriculo->nome_profissional,
                'permitir_foto' => $curriculo->permitir_foto,
                'permitir_faixa_etaria' => $curriculo->permitir_faixa_etaria,
                'permitir_escolaridade' => $curriculo->permitir_escolaridade,
                'permitir_video' => $curriculo->permitir_video,
                'status' => $curriculo->status,
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
        $curriculo = new Curriculos;

        $curriculo->distrito = $request->distrito;
        $curriculo->profissao = $request->profissao;
        $curriculo->cidade = $request->cidade;
        $curriculo->bairro = $request->bairro;
        $curriculo->bairro = $request->bairro;
        $curriculo->video = $request->video;

        if ($request->hasFile('imagem')) {
            $file = $request->file('imagem');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json(['message' => 'Apenas arquivos JPG, JPEG e PNG são permitidos.'], 400);
            }

            $filename = 'imagem-parceiro-' . '.' . $extension;
            // Move o arquivo para o diretório de destino
            $file->move('uploads/curriculo/', $filename);

            $curriculo->imagem = 'uploads/curriculo/' . $filename;
        }

        $curriculo->minimo_hora = $request->minimo_hora;
        $curriculo->faixa_etaria = $request->faixa_etaria;
        $curriculo->escolaridade = $request->escolaridade;
        $curriculo->instituicao_ensino = $request->instituicao_ensino;
        $curriculo->sobre = $request->sobre;
        $curriculo->habilidades = $request->habilidades;
        $curriculo->codigo_postal = $request->codigo_postal;
        $curriculo->ligacao = $request->ligacao;
        $curriculo->total_views = $request->total_views;
        $curriculo->formacao_academica = $request->formacao_academica;
        $curriculo->anos_experiencia_profissional = $request->anos_experiencia_profissional;
        $curriculo->profissional_id = $request->profissional_id;
        $curriculo->permitir_foto = $request->permitir_foto;
        $curriculo->permitir_faixa_etaria = $request->permitir_faixa_etaria;
        $curriculo->permitir_escolaridade = $request->permitir_escolaridade;
        $curriculo->permitir_video = $request->permitir_video;
        $curriculo->status = $request->status;
        $curriculo->save();
        return response()->json(['message' => 'Currículo feito com sucesso!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Curriculos  $curriculos
     * @return \Illuminate\Http\Response
     */
    public function show(Curriculos $curriculos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Curriculos  $curriculos
     * @return \Illuminate\Http\Response
     */
    public function edit(Curriculos $curriculos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Curriculos  $curriculos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Curriculos $curriculos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Curriculos  $curriculos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Curriculos $curriculos)
    {
        //
    }
}
