<x-app-layout>

    <div class="card cardForm" style="max-width: 300px;">
        <div class="card-body">
            <h2 class="text-center">{{ __('Perfil') }}</h2>
        </div>
    </div>
    <br>
    <div class="card cardForm" style="max-width: 1000px;">
        <div class="card-body">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
    </div>
    <br>
    <div class="card cardForm" style="max-width: 1000px;">
        <div class="card-body">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('profile.partials.update-password-form')

            </div>
        </div>
    </div>
    <br>
    <div class="card cardForm" style="max-width: 1000px;">
        <div class="card-body">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @include('profile.partials.delete-user-form')

            </div>
        </div>
    </div>

</x-app-layout>