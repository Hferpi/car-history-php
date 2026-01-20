<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Tu modelo Usuario
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = User::where('email', $request->email)->first();

        if ($usuario) {
            // Existe el usuario, verificamos contraseña
            if (Hash::check($request->password, $usuario->password)) {
                // Guardamos la sesión
                $request->session()->put('usuario_id', $usuario->id);
                $request->session()->put('usuario_nombre', $usuario->nombre);

                // Login exitoso
                return redirect()->route('home');
            } else {
                return back()->withErrors(['password' => 'Contraseña incorrecta']);
            }
        } else {
             return back()->with('error', 'Este usuario no existe. registrate o revias los campos.');
        }
    }


    public function register(Request $request)
    {
        // 1. Validar los datos
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:usuario,email', // <-- CAMBIADO A 'usuario'
            'password' => 'required|min:6', // Quitamos 'confirmed' si no tienes un segundo input de confirmación
        ]);

        // 2. Crear el usuario
        $usuario = User::create([
            'nombre'   => $request->nombre,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Iniciar sesión automáticamente
        $request->session()->put('usuario_id', $usuario->id);
        $request->session()->put('usuario_nombre', $usuario->nombre);

        return redirect()->route('home')->with('success', 'Cuenta creada exitosamente');
    }


    public function logout(Request $request)
    {
        $request->session()->forget(['usuario_id', 'usuario_nombre']);
        return redirect()->route('login');
    }
}
