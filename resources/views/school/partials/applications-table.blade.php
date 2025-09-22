<div class="overflow-x-auto">
  <table class="min-w-full text-left">
    <thead>
      <tr class="border-b">
        <th class="py-2">Name</th>
        <th class="py-2">Email</th>
        <th class="py-2">Status</th>
        <th class="py-2">Actions</th>
      </tr>
    </thead>
    <tbody id="apps-tbody">
      @foreach($apps as $app)
        @include('school.partials.application-row', ['app' => $app, 'school' => $school])
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{ $apps->links() }}
  </div>
</div>
