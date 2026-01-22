<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\Auth;

class VehiculoController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'modelo_id'  => 'required',
            'matricula'  => 'required|unique:vehiculo,matricula',
            'kilometros' => 'required|numeric|min:0',
        ]);

        // 2. Guardar en la base de datos
        // Nota: modelo_id aquí recibirá el código de la API.
        // Asegúrate de que tu tabla 'modelo' tenga esos IDs o cambia la lógica.

        $vehiculo = new Vehiculo();
        $vehiculo->usuario_id = Auth::id(); // El ID del usuario logueado
        $vehiculo->matricula  = strtoupper($request->matricula);
        $vehiculo->modelo_id  = $request->modelo_id;
        $vehiculo->kilometros = $request->kilometros;
        $vehiculo->save();

        // 3. Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'Vehículo registrado correctamente.');
    }
}