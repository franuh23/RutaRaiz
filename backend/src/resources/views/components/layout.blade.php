<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RutaRaíz - @yield('title', 'Inicio')</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        nav { margin-bottom: 20px; }
        nav a { margin-right: 15px; }
        .success { color: green; padding: 10px; background: #e0ffe0; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #ffe0e0; margin: 10px 0; }
        .error ul { margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        img { max-width: 100px; max-height: 100px; }
        form.inline { display: inline; }
    </style>
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">Inicio</a>
        <a href="{{ route('rutas.index') }}">Rutas</a>

        @auth
            @if(auth()->user()->rol === 'admin')
                <a href="{{ route('rutas.create') }}">Nueva Ruta</a>
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="background:none; border:none; color:blue; cursor:pointer; text-decoration:underline; padding:0;">
                    Cerrar sesión ({{ auth()->user()->nick }})
                </button>
            </form>
        @else
            <a href="{{ route('login') }}">Iniciar sesión</a>
        @endauth
    </nav>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
