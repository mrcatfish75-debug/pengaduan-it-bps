@props(['route','label'])

<a href="{{ route($route) }}"
   class="block px-4 py-2 rounded-md text-sm transition
   {{ request()->routeIs($route.'*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
    {{ $label }}
</a>