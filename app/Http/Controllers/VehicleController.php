<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;

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
    // HOME / DASHBOARD
    // =====================
    public function home()
    {
        $usuario_id = session('usuario_id');

        $ultimoVehiculo = Vehicle::with([
            'modelo',
            'repairs' => function ($q) {
                $q->orderByDesc('fecha');
            }
        ])
            ->where('usuario_id', $usuario_id)
            ->latest('id')
            ->first();

        $ultimoServicio = $ultimoVehiculo
            ? $ultimoVehiculo->repairs->first()
            : null;

        return view('home', compact('ultimoVehiculo', 'ultimoServicio'));
    }
}
