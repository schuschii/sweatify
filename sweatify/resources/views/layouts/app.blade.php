<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My Laravel App' }}</title>
    <!-- Add any CSS or JavaScript you want here -->
</head>
<body>
<header>
{{--    TODO Create Navbar--}}
</header>

<main>
    @yield('content') <!-- This is where the content of child views will go -->
</main>

<footer>
    <p>Laravel App Footer</p>
    {{--    TODO Create footer--}}

</footer>
</body>
</html>
