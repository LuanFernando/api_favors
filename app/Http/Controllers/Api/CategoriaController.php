<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json($categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       /* Se informou o arquivo, retorna um boolean
        * Se é válido, retorna um boolean
        */
        if(($request->file('arquivo') == true) && ($request->file('arquivo')->isValid() == true))
        {
            $categoria = new Categoria();

            $categoria->titulo = $request->titulo;
            //Na linha debaixo concatena a url APP_URL que esta no .env
            $categoria->path = env('APP_URL').'/'.$request->file('arquivo')->store('categorias/'.$request->titulo);
            $categoria->status = 'Bloqueado';
            
            $categoria->save();
            unset($categoria);//limpa variavel
        }else{
            return response()->json('Atenção ,arquivo com erro!!');
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
        $categoria = Categoria::find($id);
        
        if($categoria != null && $categoria != ""){
          if($categoria->status == 'Ativo')
            {
                return response()->json($categoria);
            }else{
                return response()->json('Atenção ,Categoria não ativa contate o administrador!!');
            }
        }else{
                return response()->json('Categoria não encontrada.');
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
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

     /* Enum Status 'Ativo','Inativo','Bloqueado' */
     $categoria = Categoria::findOrFail($id);
     if($categoria->status != 'Inativo')
     {
        $categoria->status = 'Inativo';
        $categoria->update();    
     }

    }
}
