<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image-x/icon" href="{{ asset('img/almacen-logo.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style-login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <div class="login-info-container">
            <h1 class="title">Iniciar Sesión</h1>
            <form class="inputs-container" method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->

                <input class="input" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Correo" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />


                <!-- Password -->

                <div class="password-container">
                    <input class="input" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Contraseña" />
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>


                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <!-- Remember Me -->

                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recuerdame') }}</span>
                </label>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                    <p>¿Olvidaste tu contraseña? <span class="span"><a href="{{ route('password.request') }}">Click aquí</a></span></p>
                    @endif

                </div>
                <button class="btn">Iniciar Sesión</button>
            </form>
        </div>
        <img class="image-container" src="{{ asset('img/almacen-logo1.jpg') }}" alt="">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle the icon
                this.classList.toggle('fa-eye-slash');
            });
        });
    </script>
</body>

</html>