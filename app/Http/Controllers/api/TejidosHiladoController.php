<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\TejidosHiladoRepositoryInterface;
use Illuminate\Http\Request;

class TejidosHiladoController extends Controller
{
    //
    private TejidosHiladoRepositoryInterface $tejidosHiladoRepositoryI;

    public function __construct(TejidosHiladoRepositoryInterface $tejidosHiladoRepositoryI)
    {
        $this->tejidosHiladoRepositoryI = $tejidosHiladoRepositoryI;
    }

    public function index()
    {
        $tejidosHilado = $this->tejidosHiladoRepositoryI->getAll();

        return ApiResponseHelper::sendResponse($tejidosHilado, "Tejidos Hilado retrieved successfully", 200);
    }

    public function show($id)
    {
        $tejidosHilado = $this->tejidosHiladoRepositoryI->getById($id);

        return response()->json($tejidosHilado, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $tejidosHilado = $this->tejidosHiladoRepositoryI->store($data);

        return response()->json($tejidosHilado, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $tejidosHilado = $this->tejidosHiladoRepositoryI->update($id, $data);

        return response()->json($tejidosHilado, 200);
    }

    public function destroy($id)
    {
        $tejidosHilado = $this->tejidosHiladoRepositoryI->destroy($id);

        return response()->json($tejidosHilado, 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $data = $request->all();

        $tejidosHilado = $this->tejidosHiladoRepositoryI->updatePartial($id, $data);

        return response()->json($tejidosHilado, 200);
    }
}
