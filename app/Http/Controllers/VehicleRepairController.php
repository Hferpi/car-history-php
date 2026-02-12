<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleRepairController extends Controller
{
    /**
     * Mostrar formulario de reparación
     */
    public function create(Vehicle $vehicle)
    {
        $usuarioId = session('usuario_id');
        abort_if($vehicle->usuario_id !== $usuarioId, 403);

        return view('vehicles.repair', compact('vehicle'));
    }

    /**
     * Guardar reparación
     */
   public function store(Request $request, Vehicle $vehicle)
{
    $usuarioId = session('usuario_id');
    abort_if($vehicle->usuario_id !== $usuarioId, 403);

    $data = $request->validate([
        'fecha'         => 'required|date',
        'precio'        => 'nullable|numeric',
        'tipo_servicio' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
        'taller_nombre' => 'nullable|string|max:255',
        'foto'          => 'nullable|image|max:5120',
        'foto_patch'    => 'nullable|string', // El path que viene del OCR
    ]);

    // LÓGICA PARA LA FOTO
    $finalPath = null;

    if ($request->hasFile('foto')) {
        // 1. Si el usuario subió una foto nueva manualmente
        $finalPath = $request->file('foto')->store('receipts', 'public');
    } elseif ($request->filled('foto_patch')) {
        // 2. Si no hay subida manual, pero venía una del OCR
        $finalPath = $request->input('foto_patch');
    }

    // Creamos el registro con el nombre de columna correcto de tu DB
    $vehicle->repairs()->create([
        'fecha'         => $data['fecha'],
        'precio'        => $data['precio'],
        'tipo_servicio' => $data['tipo_servicio'],
        'observaciones' => $data['observaciones'],
        'taller_nombre' => $data['taller_nombre'],
        'foto_patch'     => $finalPath, // Asegúrate que en tu DB se llama 'foto_path'
    ]);

    return redirect()->route('history')->with('success', 'Reparación guardada correctamente');
}
}
