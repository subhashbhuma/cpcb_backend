@php
$hasChildren = isset($menu->children) && $menu->children->isNotEmpty();
$isActive = request()->url() === url($menu->url);
@endphp

<div class="nav-item-wrapper">
    <a
        href="{{ $hasChildren ? '#' : $menu->url }}"
        class="nav-link border-bottom p-3 d-flex justify-content-between align-items-center {{ $isActive ? 'active text-primary fw-bold' : 'text-dark' }}">
        <div class="d-flex align-items-center">
            @if (!empty($menu->icon))
            <i class="{{ $menu->icon }} me-2 {{ $isActive ? 'text-primary' : 'text-dark' }}"></i>
            @endif
            <span class="fw-medium">{{ getLocalizedDataFromObj($menu, 'title') }}</span>
        </div>

        @if ($hasChildren)
        <button class="btn btn-sm toggle-submenu p-0 border-0 bg-transparent">
            <i class="fas fa-plus {{ $isActive ? 'text-primary' : 'text-dark' }}"></i>
        </button>
        @endif
    </a>

    @if ($hasChildren)
    <div class="submenu ps-3 {{ $menu->children->contains(fn($child) => request()->url() === url($child->url)) ? '' : 'd-none' }}">
        @foreach ($menu->children as $child)
        @include('components.website.side-nav-item', ['menu' => $child])
        @endforeach
    </div>
    @endif
</div>