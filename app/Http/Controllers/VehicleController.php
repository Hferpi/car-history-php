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

        $avatars = [
            'car1' => 'img/cars-icons/blue-rm.png',
            'car2' => 'img/cars-icons/cyan-rm.png',
            'car3' => 'img/cars-icons/jeep-rm.png',
            'car4' => 'img/cars-icons/red-rm.png',
        ];


        $marcas = Marca::with('modelos')->get();
        return view('vehicles.create', compact('marcas', 'avatars'));
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


    // =====================
    // BORRAR VEHICULO
    // =====================

    //TODO: agregar eliminicaion de recibos
    public function delete(Vehicle $vehicle)
    {
        if ($vehicle->avatar && !str_contains($vehicle->avatar, 'default')) {
            $path = str_replace('storage/', '', $vehicle->avatar);
            Storage::disk('public')->delete($path);
        }

        // 2. Limpiar la sesión si el coche borrado era el seleccionado
        if (session('selected_vehicle_id') == $vehicle->id) {
            session()->forget('selected_vehicle_id');
        }

        $vehicle->delete();

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
