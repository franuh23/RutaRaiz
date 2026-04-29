@extends('components.layout')

@section('title', 'Iniciar sesión')
@section('content')
    <h1>Iniciar sesión</h1>

    <form method="POST" action="{{ route('login') }}" style="max-width: 400px;">
        @csrf

        <div>
            <label>Email:</label>
            <input type="email" name="email" required value="{{ old('email') }}" style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-top: 15px;">
            <label>Contraseña:</label>
            <input type="password" name="password" required style="width: 100%; padding: 8px;">
        </div>

        <button type="submit" style="margin-top: 15px; padding: 8px 16px;">Entrar</button>
    </form>

    <p style="margin-top: 15px;">
        Usuario admin: admin@rutaraiz.com / 12345678
    </p>
@endsection
