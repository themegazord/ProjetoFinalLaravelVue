<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('filtro')) {
            $clienteRepository->filtro($request->fitro);
        }

        if($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResult(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente = $this->cliente->create($request->all());

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if(is_null($cliente)) {
            return response()->json(['error' => 'Cliente não existe'], 404);
        }

        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClienteRequest  $request
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if(is_null($cliente)) {
            return response()->json(['error' => 'Cliente não existe'], 404);
        }

        if($request->method() === 'PUT') {
            $request->validate($cliente->rules(), $cliente->feedback());
        } else if($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach($cliente->rules() as $key => $rule) {
                if(array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $rule;
                }
            }
            $request->validate($regrasDinamicas, $cliente->feedback());
        }

        $cliente->update($request->all());

        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if(is_null($cliente)) {
            return response()->json(['error' => 'Cliente não existe'], 404);
        }

        $cliente->delete();

        return response()->json(['msg' => 'Cliente deletado com sucesso'], 204);
    }
}
