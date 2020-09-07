<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Arquivo;
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
        $categoria = Categoria::all();
        $arquivo   = Arquivo::all();

        foreach ($categoria as $categoria_dt) {
            foreach ($arquivo as $arquivo_dt) {
                    if($arquivo_dt->referencia_id == $categoria_dt->id)

                       $dados[$categoria_dt->id] = [ 
                            'categoria'    => $arquivo_dt->nome_original,
                            'url_caminho' =>env('APP_URL').'/'.$arquivo_dt->url_caminho,
                        ];
            }
        }

        return response()->json($dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       /* Se informou o arquivo retorna um boolean, Se é válido retorna um boolean. */
        if(($request->file('arquivo') == true) && ($request->file('arquivo')->isValid() == true))
        {

            $categoria = new Categoria();//Objeto Categoria
            $categoria->nome = $request->nome;
            $categoria->status = 'Bloqueado';
            $categoria->save();//Salva dados

            $arquivo   = new Arquivo();//Objeto Arquivo
            $arquivo->tabela_referencia = 'categorias';
            $arquivo->nome_original = $request->nome;
            $arquivo->referencia_id = $categoria->id;//Passando a referencia

            //Na linha debaixo concatena a url APP_URL que esta no .env
            $arquivo->url_caminho = $request->file('arquivo')->store('categorias/'.$request->nome);
            $arquivo->status = 'Bloqueado';
            $arquivo->token_publico = "000001";

            $arquivo->save();
           

            unset($categoria);//limpa variavel
            unset($arquivo);//limpa variavel
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
        $arquivo   = Arquivo::where('referencia_id', "=" ,$id)->get();
        
        if($categoria != null && $categoria != ""){
          if($categoria->status == 'Ativo')
            {
                $dados = [
                    'dados_categoria' => [    
                        'categoria' => [ $categoria->id,$categoria->nome ],
                        'arquivo'   => env('APP_URL').'/'.$arquivo[0]->url_caminho,//Indice 0 é necessario para capturar os valores dentro do array
                    ],
                ];
                return response()->json($dados);
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
