@php 
    $children = isset($menu->custom_children) ? $menu->custom_children : $menu->children;
    $hasChildren = $children && $children->isNotEmpty();
    $isCaption = $menu->is_caption || ($menu->url === '#' && empty($menu->icon_png) && !$hasChildren);

    $effectiveGroup = $menu->permission_group ?: $menu->title;

    $matchingKey = null;
    if ($effectiveGroup) {
        $matchingKey = $permissionGroups->keys()->first(function ($k) use ($effectiveGroup) {
            return strtolower($k) === strtolower($effectiveGroup);
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
                // 2. Try exact match using effective group
                if (!$perm && !empty($effectiveGroup)) {
                    $groupName = strtolower($action . ' ' . $effectiveGroup);
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