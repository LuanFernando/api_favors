<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*POST http://localhost/api_favors/public/api/usuarios */
        if ($request->isMethod('post')) {

            $data = ['nome' => $request->nome,
                     'email' => $request->email,
                     'password' => bcrypt($request->password),
                     'perfil' => $request->perfil,
                     'status' => 'Ativo',
                     'remember_token' => 'random_token_teste'];

           Usuario::create($data);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $usuario = Usuario::find($id);

       if($usuario != NULL || $usuario != "")
       {
            if($usuario->status == 'Ativo')
            {
              return response()->json($usuario);
            }else{
              return 'Contate o administrador do sistema.';
            }
       }else{
            //abort(404, 'Unauthorized action.');
            return 'Usuário não encontrado.';
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* Enum Status 'Ativo','Inativo','Bloqueado','Em Análise' */

        $usuario = Usuario::findOrFail($id);
            if($usuario->status != 'Inativo')
            {
             $usuario->status = 'Inativo';
             $usuario->update();    
            }
    }
}
