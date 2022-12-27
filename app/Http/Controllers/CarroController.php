<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CarroController extends Controller
{

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,' . $request->attributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if($request->has('filtro')) {
            $carroRepository->filtro($request->fitro);
        }

        if($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $carro = $this->carro->create($request->all());

        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo.marca')->find($id);

        if(is_null($carro)) {
            return response()->json(['error' => 'Carro não existe'], 404);
        }

        return response()->json($carro, 200);
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
        $carro = $this->carro->find($id);

        if(is_null($carro)) {
            return response()->json(['error' => 'Carro não existe'], 404);
        }

        if($request->method() === 'PUT') {
            $request->validate($carro->rules(), $carro->feedback());
        } else if($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach($carro->rules() as $key => $rule) {
                if(array_key_exists($key, $request->all())) {
                    if($key === 'placa') {
                        $regrasDinamicas['placa'] = $rule;
                        $regrasDinamicas[0] = Rule::unique('carros')->ignore($id);
                    }
                    $regrasDinamicas[$key] = $rule;
                }
            }
            $request->validate($regrasDinamicas, $carro->feedback());
        }

        $carro->update($request->all());

        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if(is_null($carro)) {
            return response()->json(['error' => 'Carro não existe'], 404);
        }

        $carro->delete();

        return response()->json(['msg' => 'Carro deletado com sucesso'], 204);
    }
}
