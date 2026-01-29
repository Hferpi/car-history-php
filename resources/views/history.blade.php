@extends('layouts.app')

@section('content')
    <h1>Historial</h1>
    @if ($repair->receipt)
    <img
        src="{{ asset('storage/' . $repair->receipt->path) }}"
        class="w-64 rounded shadow"
    >
@endif

@endsection
