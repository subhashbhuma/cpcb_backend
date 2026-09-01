@if($submenus && $submenus->count() > 0)
<!-- Sidebar Navigation -->
<div class="col-lg-3">
    <div
        class="card shadow border-0 sticky-lg-top"
        style="top: 200px; z-index: 1">
        <!-- <div class="card-header bg-gradient-to-r from-primary to-primary-dark text-dark">
            <h3 class="card-title h5 mb-0">About Navigation</h3>
        </div> -->

        <div class="card-body p-0">
            <nav class="nav flex-column">
                @foreach ($submenus as $menu)
                @include('components.website.side-nav-item', ['menu' => $menu])
                @endforeach
            </nav>

        </div>
    </div>
</div>
@endif