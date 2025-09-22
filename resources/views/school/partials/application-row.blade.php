<tr id="app-row-{{ $app->id }}" class="border-b">
  <td class="py-2">{{ $app->first_name }} {{ $app->last_name }}</td>
  <td class="py-2">{{ $app->email }}</td>
  <td class="py-2">
    <span class="px-2 py-1 text-xs rounded
      @class([
        'bg-yellow-100 text-yellow-800' => $app->status === 'submitted',
        'bg-green-100 text-green-800'   => $app->status === 'accepted',
        'bg-red-100 text-red-800'       => $app->status === 'rejected',
      ])">
      {{ ucfirst($app->status) }}
    </span>
  </td>
  <td class="py-2 space-x-2">
    @if($app->status === 'submitted')
      <button
        hx-post="{{ route('school.applications.accept', [$school->slug, $app->id]) }}"
        hx-target="#app-row-{{ $app->id }}"
        hx-swap="outerHTML"
        class="px-3 py-1 bg-green-600 text-white rounded">
        Accept
      </button>

      <button
        hx-post="{{ route('school.applications.reject', [$school->slug, $app->id]) }}"
        hx-vals='{"notes":"Rejected by admin"}'
        hx-target="#app-row-{{ $app->id }}"
        hx-swap="outerHTML"
        class="px-3 py-1 bg-red-600 text-white rounded">
        Reject
      </button>
    @else
      <span class="text-sm text-gray-500">No actions</span>
    @endif
  </td>
</tr>
