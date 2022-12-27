<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelos:id,' . $request->atributos_modelo;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginacao(5), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if (is_null($marca)) {
            return response()->json(['error' => 'Marca não existe'], 404);
        };
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if (is_null($marca)) {
            return response()->json(['error' => 'Não foi encontrado o registro para atualização'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($marca->rules(), $marca->feedback());
        } else if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    if ($input === 'nome' || $input == 0) {
                        $regrasDinamicas[$input] = $regra;
                        $regrasDinamicas[0] = Rule::unique('name')->ignore($id);
                        continue;
                    }
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        }

        // remove o arquivo antigo caso um novo arquivo seja enviado no request
        if (!is_null($request->file('imagem'))) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();

        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if (is_null($marca)) {
            return response()->json(['error' => 'Não foi encontrado registro para remoção'], 404);
        }


        // remove o arquivo antigo
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json([], 204);
    }
}
