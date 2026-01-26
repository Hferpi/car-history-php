<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Vehicle;

use function Laravel\Prompts\alert;

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

    //Guardar vehiculo
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
            'regex:/^[0-9]{4}[BCDFGHJKLMNPRSTVWXYZ]{3}$/' //Regex pa matricula
        ],
    ], [
        'matricula.regex' => 'La matrícula debe tener el formato 1234BBB (4 números y 3 letras consonantes).',
        'matricula.unique' => 'Esta matrícula ya está registrada en el sistema.'
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

    return redirect()->route('vehicles.index')->with('success', 'Vehículo creado correctamente');
}

    //Recibe el utimo coche y recibo hacia /home
    public function home()
    {
        $usuario_id = session('usuario_id');

        $ultimoVehiculo = Vehicle::with('modelo')
            ->where('usuario_id', $usuario_id)
            ->latest('id')
            ->first();

        //Hay que cambiarlo cuando se haga la parte de recibos
        $repair = [
            'fecha' => '01/03/2025',
            'precio' => '500',
            'tipo_servicio' => 'Cambio aceite y filtro',
            'km' => '160.590',
        ];

        return view('home', compact('ultimoVehiculo', 'repair'));
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

