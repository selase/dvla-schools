<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">School: {{ $school->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <p>Welcome to {{ $school->name }} dashboard.</p>
            </div>
        </div>
    </div>
</x-app-layout>
