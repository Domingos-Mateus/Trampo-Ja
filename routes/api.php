<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BairrosController;
use App\Http\Controllers\CidadesController;
use App\Http\Controllers\CurriculosController;
use App\Http\Controllers\EmpreendedoresController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\FucoesController;
use App\Http\Controllers\ImagensServicosController;
use App\Http\Controllers\ParceirosController;
use App\Http\Controllers\PerfilsController;
use App\Http\Controllers\ProfissionaisController;
use App\Http\Controllers\ProvinciasController;
use App\Http\Controllers\ServicosController;
use App\Http\Controllers\VagasController;
use App\Models\Empreendedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//=================Rota do CRUD província=====================
Route::get('/listar_provincias', [ProvinciasController::class, 'index']);
Route::post('/registar_provincia', [ProvinciasController::class, 'store']);
Route::get('/detalhar_provincia/{id}', [ProvinciasController::class, 'show']);
Route::put('/actualizar_provincia/{id}', [ProvinciasController::class, 'update']);
Route::get('/eliminar_provincia/{id}', [ProvinciasController::class, 'destroy']);


//=================Rota do CRUD Cidade=====================
Route::get('/listar_cidades', [CidadesController::class, 'index']);
Route::post('/registar_cidade', [cidadesController::class, 'store']);
Route::get('/detalhar_cidade/{id}', [cidadesController::class, 'show']);
Route::put('/actualizar_cidade/{id}', [cidadesController::class, 'update']);
Route::get('/eliminar_cidade/{id}', [cidadesController::class, 'destroy']);


//=================Rota do CRUD Serviços=====================
Route::get('/listar_servicos', [ServicosController::class, 'index']);
Route::post('/registar_servico', [servicosController::class, 'store']);
Route::post('/carregar_imagem_servico/{id}', [servicosController::class, 'uploadFotoServico']);
Route::get('/detalhar_servico/{id}', [servicosController::class, 'show']);
Route::put('/actualizar_servico/{id}', [servicosController::class, 'update']);
Route::get('/eliminar_servico/{id}', [servicosController::class, 'destroy']);


//=================Rota do CRUD Profissionais=====================
Route::get('/listar_profissionais', [ProfissionaisController::class, 'index']);
Route::post('/registar_profissional', [profissionaisController::class, 'store']);
Route::post('/carregar_imagem_profissional/{id}', [profissionaisController::class, 'uploadFotoPerfil']);
Route::get('/detalhar_profissional/{id}', [profissionaisController::class, 'show']);
Route::put('/actualizar_profissional/{id}', [profissionaisController::class, 'update']);
Route::get('/eliminar_profissional/{id}', [profissionaisController::class, 'destroy']);


//=================Rota do CRUD Funções=====================
Route::get('/listar_funcoes', [FucoesController::class, 'index']);
Route::post('/registar_funcao', [FucoesController::class, 'store']);
Route::get('/detalhar_funcao/{id}', [FucoesController::class, 'show']);
Route::put('/actualizar_funcao/{id}', [FucoesController::class, 'update']);
Route::get('/eliminar_funcao/{id}', [FucoesController::class, 'destroy']);


//=================Rota do CRUD Bairros=====================
Route::get('/listar_bairros', [BairrosController::class, 'index']);
Route::post('/registar_bairro', [BairrosController::class, 'store']);
Route::get('/detalhar_bairro/{id}', [BairrosController::class, 'show']);
Route::put('/actualizar_bairro/{id}', [BairrosController::class, 'update']);
Route::get('/eliminar_bairro/{id}', [BairrosController::class, 'destroy']);


//=================Rota do CRUD Bairros=====================
Route::get('/listar_imagem_servicos', [ImagensServicosController::class, 'index']);
Route::post('/registar_imagem_servico', [ImagensServicosController::class, 'store']);
Route::get('/detalhar_imagem_servico/{id}', [ImagensServicosController::class, 'show']);
Route::put('/actualizar_imagem_servico/{id}', [ImagensServicosController::class, 'update']);
Route::get('/eliminar_imagem_servico/{id}', [ImagensServicosController::class, 'destroy']);


//=================Rota do CRUD Perfil=====================
Route::get('/listar_perfil', [PerfilsController::class, 'index']);
Route::post('/registar_perfil', [PerfilsController::class, 'store']);
Route::get('/detalhar_perfil/{id}', [PerfilsController::class, 'show']);
Route::put('/actualizar_perfil/{id}', [PerfilsController::class, 'update']);
Route::get('/eliminar_perfil/{id}', [PerfilsController::class, 'destroy']);


//=================Rota do CRUD Empreendedor=====================
Route::get('/listar_empreendedores', [EmpreendedoresController::class, 'index']);
Route::post('/registar_empreendedor', [EmpreendedoresController::class, 'store']);
Route::get('/detalhar_empreendedor/{id}', [EmpreendedoresController::class, 'show']);
Route::put('/actualizar_empreendedor/{id}', [EmpreendedoresController::class, 'update']);
Route::get('/eliminar_empreendedor/{id}', [EmpreendedoresController::class, 'destroy']);



//=================Rota do CRUD Empresa=====================
Route::get('/listar_empresas', [EmpresasController::class, 'index']);
Route::post('/registar_empresa', [EmpresasController::class, 'store']);
Route::get('/detalhar_empresa/{id}', [EmpresasController::class, 'show']);
Route::put('/actualizar_empresa/{id}', [EmpresasController::class, 'update']);
Route::get('/eliminar_empresa/{id}', [EmpresasController::class, 'destroy']);


//=================Rota do CRUD Parceiro=====================
Route::get('/listar_parceiros', [ParceirosController::class, 'index']);
Route::post('/registar_parceiro', [ParceirosController::class, 'store']);
Route::get('/detalhar_parceiro/{id}', [ParceirosController::class, 'show']);
Route::put('/actualizar_parceiro/{id}', [ParceirosController::class, 'update']);
Route::get('/eliminar_parceiro/{id}', [ParceirosController::class, 'destroy']);




//=====================Rota para vagas==============================
Route::get('/listar_vagas', [VagasController::class, 'index']);
Route::post('/registar_vaga', [VagasController::class, 'store']);
Route::get('/detalhe_vaga/{id}', [VagasController::class, 'show']);
Route::put('/actualizar_vaga/{id}', [VagasController::class, 'update']);
Route::get('/eliminar_vaga/{id}', [VagasController::class, 'destroy']);



//=================Rota do CRUD Currículo=====================
Route::get('/listar_curriculo', [CurriculosController::class, 'index']);
Route::post('/registar_listar_curriculo', [CurriculosController::class, 'store']);
Route::get('/detalhar_listar_curriculo/{id}', [CurriculosController::class, 'show']);
Route::put('/actualizar_listar_curriculo/{id}', [CurriculosController::class, 'update']);
Route::get('/eliminar_listar_curriculo/{id}', [CurriculosController::class, 'destroy']);



Route::post('logout', [AuthController::class, 'logout']);



//login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

