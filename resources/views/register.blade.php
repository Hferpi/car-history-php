@extends('layouts.app')

@section('content')
    <h1>Registro</h1>
    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="algo@gmail.com" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
@endsection
