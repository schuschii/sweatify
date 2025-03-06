<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üdv a Sweatify-ban</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="text-center p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-3xl font-bold text-gray-900">Üdvözlünk a Sweatify-ban! 💪</h1>
    <p class="text-gray-700 mt-2">A legjobb edzéstervező alkalmazás</p>
    <div class="mt-4">
        <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Bejelentkezés</a>
        <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Regisztráció</a>
    </div>
</div>
</body>
</html>

