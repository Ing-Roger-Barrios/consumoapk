<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold mb-6">
                <x-back-button :href="url()->previous()" />
                Editar Residente
            </h1>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <form method="POST" action="{{ route('residents.update', $resident) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input 
                            id="name" 
                            class="block mt-1 w-full" 
                            type="text" 
                            name="name" 
                            :value="old('name', $resident->name)" 
                            required 
                            autofocus 
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input 
                            id="email" 
                            class="block mt-1 w-full" 
                            type="email" 
                            name="email" 
                            :value="old('email', $resident->email)" 
                            required 
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password (optional) -->
                    <div class="mb-4">
                        <x-input-label for="password" :value="__('Nueva Contraseña (opcional)')" />
                        <x-text-input 
                            id="password" 
                            class="block mt-1 w-full"
                            type="password"
                            name="password"
                            autocomplete="new-password" 
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <p class="text-xs text-gray-500 mt-1">Deja en blanco para mantener la contraseña actual</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                        <x-text-input 
                            id="password_confirmation" 
                            class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" 
                            autocomplete="new-password" 
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <x-back-button :href="url()->previous()" label="Cancelar"/>
                        <x-primary-button>
                            {{ __('Actualizar Residente') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>