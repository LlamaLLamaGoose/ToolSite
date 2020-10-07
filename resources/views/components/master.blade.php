<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Nicholas C</title>

        <link rel="stylesheet" href="build/tailwind.css">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
    </head>

    <body class="bg-gray-900 flex flex-col min-h-screen">
        <header>
            @include('components\_header')
            @include('components\_menu')
        </header>
            {{ $slot }}
        <footer>
			@include('components\_footer')
		</footer>
    </body>
</html>
