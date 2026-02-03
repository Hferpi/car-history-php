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

        // Validar datos
        $data = $request->validate([
            'fecha'         => 'required|date',
            'precio'        => 'nullable|numeric',
            'tipo_servicio' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'taller_nombre' => 'nullable|string|max:255',
            'foto'          => 'nullable|image|max:5120',
            'foto_guardada' => 'nullable|string', // para foto ya subida por OCR
        ]);

        // Manejar la imagen: usar la del OCR o la subida manual
        if (!empty($data['foto_guardada'])) {
            $data['foto_path'] = $data['foto_guardada'];
            $data['foto_disk'] = 'public';
        } elseif ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('receipts', 'public');
            $data['foto_path'] = $path;
            $data['foto_disk'] = 'public';
        }

        // Eliminar claves innecesarias que no existen en el modelo
        unset($data['foto_guardada'], $data['foto']);

        // Crear reparación asociada al vehículo
        $repair = $vehicle->repairs()->create($data);

        return back()->with('success', 'Reparación guardada correctamente');
    }
}
