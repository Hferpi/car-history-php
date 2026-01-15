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
            'nombre' => 'required',
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
            // Usuario no existe, lo creamos
            $usuario = User::create([
                'nombre' => $request->nombre,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Guardamos la sesión del nuevo usuario
            $request->session()->put('usuario_id', $usuario->id);
            $request->session()->put('usuario_nombre', $usuario->nombre);

            return redirect()->route('home');
        }
    }

    
    public function logout(Request $request)
    {
        $request->session()->forget(['usuario_id', 'usuario_nombre']);
        return redirect()->route('login');
    }
}
