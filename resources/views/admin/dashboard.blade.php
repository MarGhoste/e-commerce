<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | Mi E-commerce</title>

    
</head>

<body>
    {{-- 1. Contenedor de la Aplicación Vue --}}
    <div id="admin-app">
        {{-- Aquí se montará el componente AdminLayout --}}
        <admin-layout></admin-layout>
    </div>

    {{-- 2. Cargar el archivo vite que inicializa Vue --}}
    @vite('resources/js/admin.js')
</body>

</html>
