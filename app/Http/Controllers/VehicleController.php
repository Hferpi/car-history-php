<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Storage;


class VehicleController extends Controller
{
    // =====================
    // GARAJE
    // =====================
    public function index()
    {
        $usuario_id = session('usuario_id');

        $vehiculos = Vehicle::with('modelo')
            ->where('usuario_id', $usuario_id)
            ->get();

        return view('vehicles.garage', compact('vehiculos'));
    }

    // =====================
    // FORM CREAR VEHÍCULO
    // =====================
    public function create()
    {
        $marcas = Marca::with('modelos')->get();
        return view('vehicles.create', compact('marcas'));
    }

    // =====================
    // GUARDAR VEHÍCULO
    // =====================
    public function store(Request $request)
    {
        $request->merge([
            'matricula' => strtoupper(str_replace(' ', '', $request->matricula))
        ]);

        $request->validate([
            'marca_id'   => 'required|exists:marca,id',
            'modelo_id'  => 'required|exists:modelo,id',
            'kilometros' => 'nullable|integer',
            'avatar'     => 'required',
            'matricula'  => [
                'required',
                'unique:vehiculo,matricula',
                'regex:/^[0-9]{4}[BCDFGHJKLMNPRSTVWXYZ]{3}$/'
            ],
        ], [
            'matricula.regex' => 'La matrícula debe tener el formato 1234BBB.',
            'matricula.unique' => 'Esta matrícula ya está registrada.'
        ]);

        $marcaDB = Marca::findOrFail($request->marca_id);

        Vehicle::create([
            'usuario_id' => session('usuario_id'),
            'marca'      => $marcaDB->nombre,
            'modelo_id'  => $request->modelo_id,
            'matricula'  => $request->matricula,
            'kilometros' => $request->kilometros,
            'avatar'     => $request->avatar,
        ]);

        return redirect()
            ->route('garaje')
            ->with('success', 'Vehículo creado correctamente');
    }


    // =====================
    //Seleccionar coche que se vera en /home
    // =====================
    public function select(Request $request)
    {
        $usuarioId = session('usuario_id');

        $vehicle = Vehicle::where('usuario_id', $usuarioId)
            ->where('id', $request->id)
            ->firstOrFail();

        session(['selected_vehicle_id' => $vehicle->id]);

        return response()->json([
            'status' => 'success',
            'selected_vehicle_id' => $vehicle->id
        ]);
    }


    // =====================
    // HOME / DASHBOARD
    // =====================
    public function home()
    {
        $usuario_id = session('usuario_id');

        //Busca vehiculo seleccionado en sesion
        $idSeleccionado = session('selected_vehicle_id');
        $ultimoVehiculo = null;

        if ($idSeleccionado) {
            $ultimoVehiculo = Vehicle::with('modelo')
                ->where('usuario_id', $usuario_id)
                ->where('id', $idSeleccionado)
                ->first();
        }

        //Si no hay seleccióncarga el ultimo añadido
        if (!$ultimoVehiculo) {
            $ultimoVehiculo = Vehicle::with('modelo')
                ->where('usuario_id', $usuario_id)
                ->latest('id')
                ->first();
        }

        $ultimoServicio = $ultimoVehiculo
            ? $ultimoVehiculo->repairs->first()
            : null;

        return view('home', compact('ultimoVehiculo', 'ultimoServicio'));
    }

    public function delete(Vehicle $vehicle)
{
    // 1. Borrar la imagen física del servidor si existe y no es la por defecto
    if ($vehicle->avatar && !str_contains($vehicle->avatar, 'default')) {
        $path = str_replace('storage/', '', $vehicle->avatar);
        Storage::disk('public')->delete($path);
    }

    // 2. Limpiar la sesión si el coche borrado era el seleccionado
    if (session('selected_vehicle_id') == $vehicle->id) {
        session()->forget('selected_vehicle_id');
    }

    $vehicle->delete();

    // 4. Redirigir con mensaje
    return redirect()->route('garaje')->with('success', 'El vehículo ha sido eliminado del garaje.');
}
// =====================
    // HISTORY
    // =====================
    public function history()
    {
        $usuario_id = session('usuario_id');

        // Vehículo seleccionado en sesión
        $idSeleccionado = session('selected_vehicle_id');

        $ultimoVehiculo = null;
        $ultimoServicio = null;

        if ($idSeleccionado) {
            $ultimoVehiculo = Vehicle::with(['modelo', 'repairs' => function ($q) {
                $q->orderByDesc('fecha');
            }])
                ->where('usuario_id', $usuario_id)
                ->where('id', $idSeleccionado)
                ->first();
        }

        // Si no hay seleccionado, coger el último creado
        if (!$ultimoVehiculo) {
            $ultimoVehiculo = Vehicle::with(['modelo', 'repairs' => function ($q) {
                $q->orderByDesc('fecha');
            }])
                ->where('usuario_id', $usuario_id)
                ->latest('id')
                ->first();
        }

        // Última reparación real
        if ($ultimoVehiculo && $ultimoVehiculo->repairs->isNotEmpty()) {
            $ultimoServicio = $ultimoVehiculo->repairs->first();
        }

        return view('history', compact('ultimoVehiculo', 'ultimoServicio'));
    }
}
