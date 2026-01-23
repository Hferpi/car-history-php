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
        return view('vehicles.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'marca_id'   => 'required|exists:marca,id',
            'modelo_id'  => 'required|exists:modelo,id',
            'matricula'  => 'required|unique:vehiculo,matricula',
            'kilometros' => 'nullable|integer',
            'avatar'     => 'required'
        ]);

        // 1. Obtener el objeto de la marca para sacar el nombre
        $marcaDB = Marca::find($request->marca_id);

        // 2. Crear el vehículo manualmente para mayor seguridad
        Vehicle::create([
            'usuario_id' => session('usuario_id'),      // ID del usuario logueado
            'marca'      => $marcaDB->nombre,  // Guardamos "BMW", no el ID "1"
            'modelo_id'  => $request->modelo_id,
            'matricula'  => $request->matricula,
            'kilometros' => $request->kilometros,
            'avatar'     => $request->avatar,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehículo creado correctamente');
    }
}