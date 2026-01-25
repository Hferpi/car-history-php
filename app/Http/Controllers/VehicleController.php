<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    //Ver el Garaje
    public function index()
{
    $usuario_id = session('usuario_id');
    $vehiculos = Vehicle::with('modelo')->where('usuario_id', $usuario_id)->get();

    return view('vehicles.garage', compact('vehiculos'));
}

    // Mostrar formulario de creación
    public function create()
    {
        $marcas = Marca::with('modelos')->get();
        return view('vehicles.create', compact('marcas'));
    }

    //Guardar el nuevo vehículo
    public function store(Request $request)
    {
        $request->validate([
            'marca_id'   => 'required|exists:marca,id',
            'modelo_id'  => 'required|exists:modelo,id',
            'matricula'  => 'required|unique:vehiculo,matricula',
            'kilometros' => 'nullable|integer',
            'avatar'     => 'required'
        ]);

        $marcaDB = Marca::find($request->marca_id);

        Vehicle::create([
            'usuario_id' => session('usuario_id'),
            'marca'      => $marcaDB->nombre,
            'modelo_id'  => $request->modelo_id,
            'matricula'  => $request->matricula,
            'kilometros' => $request->kilometros,
            'avatar'     => $request->avatar,
        ]);

        return redirect()->route('vehicles.index')->with('success', 'Vehículo añadido al garaje');
    }
}
