<x-guest-layout>
  <div class="max-w-2xl mx-auto mt-10 bg-white p-6 rounded-xl shadow">
    <h1 class="text-2xl font-semibold mb-4">Apply to {{ $school->name }}</h1>
    <form method="POST" action="{{ route('school.apply.store', $school->slug) }}" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium">First name</label>
          <input name="first_name" required class="mt-1 w-full rounded border-gray-300" />
          @error('first_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium">Last name</label>
          <input name="last_name" required class="mt-1 w-full rounded border-gray-300" />
          @error('last_name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input name="email" type="email" required class="mt-1 w-full rounded border-gray-300" />
          @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium">Phone</label>
          <input name="phone" class="mt-1 w-full rounded border-gray-300" />
        </div>
        <div>
          <label class="block text-sm font-medium">Ghana Card No.</label>
          <input name="ghana_card_no" class="mt-1 w-full rounded border-gray-300" />
        </div>
        <div>
          <label class="block text-sm font-medium">Passport No.</label>
          <input name="passport_no" class="mt-1 w-full rounded border-gray-300" />
        </div>
      </div>
      <button class="px-4 py-2 bg-gray-900 text-white rounded">Submit application</button>
    </form>
  </div>
</x-guest-layout>
