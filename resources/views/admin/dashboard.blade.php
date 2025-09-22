<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Global Admin</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow space-y-4">
                <p class="text-gray-700">Cross-tenant overview (all schools).</p>
                <ul class="list-disc pl-6">
                    <li><a class="underline" href="{{ route('admin.schools.index') }}">Schools</a></li>
                    <li><a class="underline" href="{{ route('admin.users.index') }}">Users</a></li>
                    <li><a class="underline" href="{{ route('admin.context.clear') }}">Clear “view as school”</a></li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
