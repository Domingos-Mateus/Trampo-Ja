<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request){

    	$data = $request->validate([
    		'name' => 'required|string|max:191',
    		'email' => 'required|email|max:191|unique:users,email',
    		//'telefone' => 'required|max:191|unique:users,telefone',
            'password' => 'required|string',
    	]);


    	$user = User::create([
    		'name' => $data['name'],
            'email' => $data['email'],
            //'telefone' => $data['telefone'],
    		'password' => Hash::make($data['password'])
    	]);

    	$token = $user->createToken('authToken')->plainTextToken;

    	$response = [
    			'user'=>$user,
    			'token'=>$token
    	];

    	return response($response, 201);


    }

	public function logout(Request $request)
{
    $user = $request->user();

    if ($user) {
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }

    return response(['message' => 'Logout realizado com sucesso'], 200);
}

	/*
    public function logout(){
    	 $user = Auth::user();
    	 $user->tokens()->delete();
    	auth()->user()->tokens()->delete();
    	return response(['message'=>'logout feito com sucesso']);
    }
	*/

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|max:191',
            'password' => 'required|string',
        ]);

        // Encontra o usuário pelo número de email
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response(['message' => 'Credencial inválida'], 401);
        } else {
            // Gera o token de autenticação para o usuário
            $token = $user->createToken('authTokenLogin')->plainTextToken;

            // Recupera o ID do publicador associado ao usuário
            //$publicador = $user->publicador()->first();

            // Determina para qual painel redirecionar com base na função do usuário
            //$redirectRoute = $user->funcao === 'admin' ? 'admin.dashboard' : 'user.dashboard';

            // Retorna a resposta com os dados do usuário, token e ID do publicador
            $response = [
                'user' => $user,
                'token' => $token,
                //'publicador_id' => $publicador ? $publicador->id : null, // Retorna o ID do publicador ou null se não houver associação
                //'redirect_route' => $redirectRoute // Rota para redirecionamento
            ];

            return response($response, 200);
        }
    }


    public function teste(){
        return 'testou..';
    }
}
