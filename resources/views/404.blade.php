<x-app-layout>

    <!-- 404 Error Text -->
    <div class="text-center">
        <div class="error mx-auto" data-text="404">403</div>
        <p class="lead text-gray-800 mb-5">Usted no cuenta con el permiso necesario para esa acción</p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="{{route('dashboard')}}">&larr; Regresar al dashboard</a>
    </div>


</x-app-layout>