<head>
    <style>
        #dropArea {
            border-radius: 20px;
            border-color: black;
            border-bottom: 10px;
            flex: auto;
            align-items: center;
            text-align: center;
            color: black;
            border: 10px;
        }
    </style>
</head>
<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información de Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Actualice la información del perfil y la dirección de correo electrónico de su cuenta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="profileImageForm" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 text-gray-900" novalidate enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="text-center">
            <h2 class="text-lg font-medium text-gray-900 dark:text-dark-100">
                {{ __('Foto de Perfil') }}
            </h2>
            <div class="mt-2 flex justify-center items-center">
                <div>
                    <img id="previewImage" src="{{ asset(Auth::user()->avatar) }}" alt="Foto de perfil" class="img-profile-perfil-update rounded-circle mr-4">
                </div>
                <br>
                <div id="dropArea" class="flex flex-col justify-center items-center text-gray-900 border border-dashed border-gray-400 p-3 text-center">
                    <h4>Arrastra y suelta tu imagen aquí</h4>
                    <h1><i class="fa-regular fa-images"></i></h1>
                    <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none">
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center">
            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <x-input-label for="name" :value="__('Apodo')" />
                </div>
                <div class="w-full md:w-3/4">
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </div>

            <!-- Otros campos de perfil aquí -->

            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <label for="persona_id" class="text font-medium text-gray-700 dark:text-gray-300">{{ __('Persona') }}</label>
                </div>
                <div class="w-full md:w-3/4">
                    <x-text-input id="persona_id" name="persona_id" type="text" class="mt-1 block w-full" :value="old('persona_id', $user->persona->primer_nombre.' '.$user->persona->segundo_nombre.' '.$user->persona->apellido_paterno.' '.$user->persona->apellido_materno)" readonly />
                    <x-input-error class="mt-2" :messages="$errors->get('persona_id')" />
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <label for="unidad_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Unidad') }}</label>
                </div>
                <div class="w-full md:w-3/4">
                    <x-text-input id="unidad_id" name="unidad_id" type="text" class="mt-1 block w-full" :value="old('unidad_id', $user->unidad->descripcion)" readonly />
                    <x-input-error class="mt-2" :messages="$errors->get('unidad_id')" />
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <label for="dependencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dependencia') }}</label>
                </div>
                <div class="w-full md:w-3/4">
                    <x-text-input id="dependencia" name="dependencia" type="text" class="mt-1 block w-full" :value="old('dependencia', $user->unidad->dependencia->nombre)" readonly />
                    <x-input-error class="mt-2" :messages="$errors->get('dependencia')" />
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <label for="roles" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Rol') }}</label>
                </div>
                <div class="w-full md:w-3/4">
                    @foreach ($user->roles as $role)
                    <x-text-input id="roles" name="roles" type="text" class="mt-1 block w-full" value="{{ $role->name }}" readonly />
                    @endforeach
                    <x-input-error class="mt-2" :messages="$errors->get('roles')" />
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center w-full gap-4">
                <div class="flex flex-col items-end w-full md:w-1/4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                </div>
                <div class="w-full md:w-3/4">
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" readonly />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            {{ __('Su dirección de correo electrónico no está verificada.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                {{ __('Haga clic aquí para volver a enviar el correo electrónico de verificación.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.') }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>



            <div class="flex justify-center items-center gap-4 mt-4">
                <x-primary-button>{{ __('Guardar') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                <h1 x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-gray-900 dark:text-gray-400">{{ __('Se Actualizó.') }}</h1>
                @endif
            </div>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('dropArea');
        const profileImageInput = document.getElementById('avatar');
        const previewImage = document.getElementById('previewImage');

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('border-primary');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('border-primary');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-primary');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                profileImageInput.files = files;
                previewImageFile(files[0]);
            }
        });

        dropArea.addEventListener('click', () => {
            profileImageInput.click();
        });

        profileImageInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files.length > 0) {
                previewImageFile(files[0]);
            }
        });

        function previewImageFile(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>