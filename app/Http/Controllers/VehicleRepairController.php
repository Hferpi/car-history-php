<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\VehicleRepair;

class VehicleRepairController extends Controller
{
    public function create(Vehicle $vehicle)
    {
        $usuarioId = session('usuario_id');
        abort_if($vehicle->usuario_id !== auth()->$usuarioId, 403);


        return view('vehicles.repair', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $usuarioId = session('usuario_id');
        abort_if($vehicle->usuario_id !== auth()->$usuarioId, 403);

        $data = $request->validate([
            'taller_id' => 'required|exists:taller,id',
            'fecha' => 'required|date',
            'precio' => 'nullable|numeric',
            'tipo_servicio' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data['vehiculo_id'] = $vehicle->id;

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('repairs', 'public');
        }

        VehicleRepair::create($data);

        return back()->with('success', 'Reparación guardada');
    }
}
