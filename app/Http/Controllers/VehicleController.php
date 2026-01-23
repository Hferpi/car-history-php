<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;

use function Laravel\Prompts\alert;

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

use App\Models\VehicleRepair;
use Illuminate\Support\Facades\Storage;

class VehicleRepairController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehiculo_id' => 'required|exists:vehiculo,id',
            'taller_id' => 'required|exists:taller,id',
            'fecha' => 'required|date',
            'precio' => 'nullable|numeric',
            'tipo_servicio' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        // 📸 Guardar imagen
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('repairs', 'public');
        }

        VehicleRepair::create($data);

        return back()->with('success', 'Reparación guardada');
    }
}
