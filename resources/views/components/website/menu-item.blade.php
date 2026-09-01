@foreach ($items as $item)
@if (count($item->children) > 0)
<li class="dropdown-submenu position-relative">
    <a class="{{ isset($isChild) ? 'dropdown-item' : 'nav-link' }} dropdown-toggle py-2 ripple-effect d-flex justify-content-between align-items-center"
        href="#"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false">
        {{ getLocalizedDataFromObj($item, 'title') }}
        <i class="fas {{ isset($isChild) ? 'fa-angle-right' : 'fa-angle-down' }} ms-2"></i>
    </a>
    <ul class="dropdown-menu shadow border-0 rounded-3 mt-1 {{ isset($isChild) ? 'dropdown-submenu ms-2' : 'elevation-3' }}">
        @include('components.website.menu-item', ['items' => $item->children, 'isChild' => true])
    </ul>
</li>
@else
<li>
    <a class="{{ isset($isChild) ? 'dropdown-item' : 'nav-link' }} py-2 ripple-effect"
        href="{{ $item->url }}">
        {{ getLocalizedDataFromObj($item, 'title') }}
    </a>
</li>
@endif
@endforeach