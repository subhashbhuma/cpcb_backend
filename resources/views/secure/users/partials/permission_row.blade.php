@php 
    $hasChildren = $menu->children && $menu->children->isNotEmpty();
    $isCaption = $menu->is_caption || ($menu->url === '#' && empty($menu->icon_png) && !$hasChildren);

    $matchingKey = null;
    if ($menu->permission_group) {
        $matchingKey = $permissionGroups->keys()->first(function ($k) use ($menu) {
            return strtolower($k) === strtolower($menu->permission_group);
        });
    }

    $showCheckboxes = !$isCaption && $matchingKey !== null;
    $hasGroup = $matchingKey !== null;
    $groupPerms = $hasGroup ? $permissionGroups[$matchingKey] : collect();
    $menuSlug = $hasGroup ? \Illuminate\Support\Str::slug($matchingKey) : 'no-group-' . $menu->id;

    $allChecked = false;
    if ($showCheckboxes && isset($userPermissions)) {
        $menuPermNames = $groupPerms->pluck('name')->toArray();
        $allChecked = !empty($userPermissions) && count(array_intersect($menuPermNames, $userPermissions)) === count($menuPermNames);
    }
@endphp

<style @cspNonce>
    .menu-row:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
    }

    .perm-cell {
        transition: all 0.2s;
        border-left: 1px solid #f1f1f1;
    }

    .perm-cell:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .perm-check-wrapper {
        cursor: pointer;
        display: block;
        width: 100%;
        height: 100%;
        padding: 8px 0;
    }

    .perm-checkbox:checked+.form-check-label {
        font-weight: bold;
    }

    .bg-view {
        background-color: rgba(40, 167, 69, 0.03);
    }

    .bg-add {
        background-color: rgba(0, 123, 255, 0.03);
    }

    .bg-edit {
        background-color: rgba(255, 193, 7, 0.03);
    }

    .bg-delete {
        background-color: rgba(220, 53, 69, 0.03);
    }

    .bg-publish {
        background-color: rgba(111, 66, 193, 0.03);
    }

    .bg-approve {
        background-color: rgba(232, 62, 140, 0.03);
    }

    .bg-view:has(.perm-checkbox:checked) {
        background-color: rgba(40, 167, 69, 0.12) !important;
    }

    .bg-add:has(.perm-checkbox:checked) {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }

    .bg-edit:has(.perm-checkbox:checked) {
        background-color: rgba(255, 193, 7, 0.12) !important;
    }

    .bg-delete:has(.perm-checkbox:checked) {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .bg-publish:has(.perm-checkbox:checked) {
        background-color: rgba(111, 66, 193, 0.1) !important;
    }

    .bg-approve:has(.perm-checkbox:checked) {
        background-color: rgba(232, 62, 140, 0.1) !important;
    }

    .menu-row:has(.perm-checkbox:checked) {
        background-color: rgba(0, 123, 255, 0.02) !important;
    }

    .row-caption {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6;
    }

    .row-parent {
        background-color: #fcfcfc !important;
        border-bottom: 1px solid #eee;
    }
</style>

<tr class="menu-row {{ $isCaption ? 'row-caption' : ($hasChildren ? 'row-parent' : '') }}">
    <td style="width: 40%; vertical-align: middle; padding-left: 15px; overflow: hidden;">
        <div class="d-flex align-items-center" style="padding-left: {{ ($menu->depth ?? 0) * 20 }}px; min-width: 0;">
            @if($menu->slNo)
                <span class="text-muted me-2 fw-bold" style="font-size: 0.75rem; min-width: 25px;">{{ $menu->slNo }}.</span>
            @endif

            <div class="flex-grow-1" style="min-width: 0;">
                <div class="fw-bold {{ ($isCaption || $hasChildren) ? 'text-primary' : 'text-dark' }}"
                    style="font-size: {{ ($isCaption || $hasChildren) ? '0.9rem' : '0.85rem' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    @if(($menu->depth ?? 0) > 0)
                        <i class="ti ti-corner-down-right text-muted me-1"></i>
                    @endif
                    {{ $menu->title }}
                    @if($isCaption)
                        <span class="badge bg-light-primary text-primary ms-2" style="font-size: 0.65rem;">SECTION</span>
                    @elseif($hasChildren)
                        <span class="badge bg-light-info text-info ms-2" style="font-size: 0.65rem;">GROUP</span>
                    @endif
                </div>
            </div>
        </div>
    </td>
    <td class="text-center bg-light" style="width: 6%; vertical-align: middle; border-left: 2px solid #dee2e6;">
        @if($showCheckboxes)
            <div class="perm-check-wrapper" onclick="$(this).find('input').click(); event.stopPropagation();">
                <input type="checkbox" class="form-check-input select-menu-all shadow-none"
                    title="Select All Permissions for {{ $menu->title }}" data-menu="{{ $menuSlug }}" @if(isset($rowId))
                    data-row="{{ $rowId }}" @endif {{ $allChecked ? 'checked' : '' }} onclick="event.stopPropagation();">
            </div>
        @else
            <span class="text-muted small">—</span>
        @endif
    </td>
    @foreach(['view', 'add', 'edit', 'delete', 'publish', 'approve'] as $action)
        @php
            $perm = null;
            if ($showCheckboxes) {
                // 1. Try exact match using permission_name if defined on the menu
                if (!empty($menu->permission_name)) {
                    $exactName = strtolower($action . ' ' . $menu->permission_name);
                    $perm = $groupPerms->first(function ($p) use ($exactName) {
                        return strtolower($p->name) === $exactName;
                    });
                }
                // 2. Try exact match using permission_group (which Menu.php userCan checks by default)
                if (!$perm && !empty($menu->permission_group)) {
                    $groupName = strtolower($action . ' ' . $menu->permission_group);
                    $perm = $groupPerms->first(function ($p) use ($groupName) {
                        return strtolower($p->name) === $groupName;
                    });
                }
                // 3. Fallback to first prefix match
                if (!$perm) {
                    $perm = $groupPerms->first(function ($p) use ($action) {
                        return str_starts_with(strtolower($p->name), $action . ' ');
                    });
                }
            }
            $isChecked = isset($userPermissions) && $perm && in_array($perm->name, $userPermissions);
            $label = ucfirst($action);
            $bgClass = 'bg-' . $action;
        @endphp
        <td class="text-center perm-cell {{ $bgClass }}" style="width: 9%; vertical-align: middle;">
            @if($perm)
                <div class="perm-check-wrapper" onclick="$(this).find('input').click(); event.stopPropagation();">
                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                        title="{{ $label }} permission for {{ $menu->title }}"
                        data-menu-title="{{ $menu->title }}"
                        data-action="{{ $action }}"
                        data-section="{{ $menu->location === 'sidebar' ? 'sidebar' : 'page' }}"
                        class="form-check-input perm-checkbox perm-{{ $menuSlug }} @if(isset($rowId)) row-{{ $rowId }} @endif shadow-none"
                        {{ $isChecked ? 'checked' : '' }} onclick="event.stopPropagation();">
                </div>
            @else
                <span class="text-muted" style="font-size: 0.75rem;"
                    title="{{ $showCheckboxes ? 'Not available' : 'Structural header' }}">
                    {{ $showCheckboxes ? '—' : '' }}
                </span>
            @endif
        </td>
    @endforeach
</tr>