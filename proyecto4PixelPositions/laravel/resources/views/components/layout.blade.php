<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    <nav class="flex flex-row justify-between m-5">
        <div>
            <a href="/"><img src="{{ Vite::asset("resources/images/logo.svg") }}"/></a>
        </div>
        <div>links</div>
        <div>post a job</div>
    </nav>

    <main>
        {{ $slot }}
    </main>
</body>
</html>
