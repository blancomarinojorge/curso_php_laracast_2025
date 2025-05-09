<div>
    <h1>Nuevo trabajo!</h1>
    <p>Has creado el nuevo trabajo: {{ $job->name }}</p>

    <a href="{{ url('/jobs/'.$job->id) }}">Mira os datos aquí!!</a>
</div>
