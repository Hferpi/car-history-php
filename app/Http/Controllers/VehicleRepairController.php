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
        abort_if($vehicle->usuario_id !== $usuarioId, 403);

        return view('vehicles.repair', compact('vehicle'));
    }

    public function store(Request $request, Vehicle $vehicle)
    {
        $usuarioId = session('usuario_id');
        abort_if($vehicle->usuario_id !== $usuarioId, 403);

        $data = $request->validate([
            'fecha' => 'required|date',
            'precio' => 'nullable|numeric',
            'tipo_servicio' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
        ]);

        // Crear reparación asociada al vehículo
        $repair = $vehicle->repairs()->create($data);

        // Guardar recibo si existe
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('receipts', 'public');

            $repair->receipt()->create([
                'path' => $path,
                'disk' => 'public', // muy buena práctica
            ]);
        }

        return back()->with('success', 'Reparación guardada');
    }
}
