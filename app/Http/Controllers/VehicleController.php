<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function create()
    {
        // Trae todas las marcas con sus modelos
        $marcas = Marca::with('modelos')->get();

        // Pasa los datos a la vista
        return view('vehicles.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer',
            'modelo_id' => 'required|integer',
            'matricula' => 'required|unique:vehiculo,matricula',
            'kilometros' => 'nullable|integer',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Vehículo creado correctamente');
    }
}
