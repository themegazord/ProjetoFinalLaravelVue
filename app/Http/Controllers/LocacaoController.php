<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocacaoController extends Controller
{

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);


        if($request->has('filtro')) {
            $locacaoRepository->filtro($request->fitro);
        }

        if($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());

        $locacao = $this->locacao->create($request->all());

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if(is_null($locacao)) {
            return response()->json(['error' => 'Locacao não existe'], 404);
        }

        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCarroRequest  $request
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if(is_null($locacao)) {
            return response()->json(['error' => 'locacao não existe'], 404);
        }

        if($request->method() === 'PUT') {
            $request->validate($locacao->rules(), $locacao->feedback());
        } else if($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach($locacao->rules() as $key => $rule) {
                if(array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $rule;
                }
            }
            $request->validate($regrasDinamicas, $locacao->feedback());
        }

        $locacao->update($request->all());

        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if(is_null($locacao)) {
            return response()->json(['error' => 'Locacao não existe'], 404);
        }

        $locacao->delete();

        return response()->json(['msg' => 'Locacao deletado com sucesso'], 204);
    }
}
