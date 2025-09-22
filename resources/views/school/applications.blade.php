<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl">Applications — {{ $school->name }}</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow" id="apps-table"
           hx-get="{{ route('school.applications.index', $school->slug) }}"
           hx-trigger="load"
           hx-target="#apps-table"
           hx-swap="innerHTML">
        <p class="text-gray-600">Loading…</p>
      </div>
    </div>
  </div>
</x-app-layout>
