<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Carbon\Carbon;


class Menu extends Model
{
    protected $fillable = ['location', 'title', 'title_hi', 'url', 'file_name', 'file_name_hi', 'icon_type', 'icon_png', 'parent_id', 'order', 'permission_name', 'permission_group', 'created_by', 'updated_by'];

    /**
     * Auto-create permissions and groups for the menu on save.
     */
    protected static function booted()
    {
        static::saving(function ($menu) {
            // Auto-assign a permission group based on the title if none is set.
            // This ensures all menus appear in the User Permission Matrix.
            if (empty($menu->permission_group) && !($menu->is_caption ?? false)) {
                $menu->permission_group = $menu->title;
            }
        });

        static::saved(function ($menu) {
            if ($menu->permission_group) {
                $group = $menu->permission_group;
                $actions = ['view', 'add', 'edit', 'delete', 'publish', 'approve'];

                foreach ($actions as $action) {
                    $permissionName = strtolower($action . ' ' . $group);
                    \Spatie\Permission\Models\Permission::updateOrCreate(
                        ['name' => $permissionName, 'guard_name' => 'web'],
                        ['group' => $group]
                    );
                }
            }
            \Illuminate\Support\Facades\Cache::flush();
        });

        static::deleted(function ($menu) {
            \Illuminate\Support\Facades\Cache::flush();
        });
    }

    /**
     * Check if user has a specific action permission for this menu.
     * Actions: view, add, edit, delete, publish, approve
     */
    public function userCan($action)
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN')) {
            return true;
        }

        // For EMPLOYEE role, check permission based on role only, not per user permission
        if ($user->hasRole('EMPLOYEE')) {
            $permissionName = $this->permission_name ? $action . ' ' . $this->permission_name : null;
            $groupPermissionName = $this->permission_group ? $action . ' ' . strtolower($this->permission_group) : null;

            foreach ($user->roles as $role) {
                if ($permissionName && $role->hasPermissionTo($permissionName)) {
                    return true;
                }
                if ($groupPermissionName && $role->hasPermissionTo($groupPermissionName)) {
                    return true;
                }
            }

            // If no permissions are defined on the menu, allow viewing (standard behavior)
            if (empty($this->permission_name) && empty($this->permission_group)) {
                return true;
            }

            return false;
        }

        if ($this->permission_name && $user->can($action . ' ' . $this->permission_name)) {
            return true;
        }

        if ($this->permission_group && $user->can($action . ' ' . strtolower($this->permission_group))) {
            return true;
        }

        // If no permissions are defined on the menu, allow viewing (standard behavior)
        if (empty($this->permission_name) && empty($this->permission_group)) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user can view this menu item.
     *
     * Logic:
     *  - If the menu has NO permission_group (pure nav child), always allow (parent guards access).
     *  - If the menu has a permission_group, the user must have at least one action permission in that group.
     *  - If the menu has children, visibility is driven by the parent's OWN permission_group (children are nav-only).
     */
    public function userCanView()
    {
        // No permission_group = pure navigation link; always visible (parent already guards access)
        if (empty($this->permission_group)) {
            return true;
        }

        // Has own permission_group — check if user has any action for it
        return $this->userCan('view') ||
            $this->userCan('add') ||
            $this->userCan('edit') ||
            $this->userCan('delete') ||
            $this->userCan('approve') ||
            $this->userCan('publish');
    }

    /**
     * Get all Spatie permissions belonging to this menu's permission group.
     */
    public function getGroupPermissions()
    {
        if (!$this->permission_group)
            return collect();
        return \Spatie\Permission\Models\Permission::where('group', $this->permission_group)->get();
    }

    /**
     * Scope: sidebar-location menus only.
     */
    public function scopeSidebar($query)
    {
        return $query->where('location', 'sidebar');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->with('children')->orderBy('order');
    }

    public function page()
    {
        return $this->hasOne(Page::class, 'menu_id');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'menu_id');
    }

    public static function getParentMenus()
    {
        return Menu::where('parent_id', null)->get();
    }

    public function location()
    {
        return $this->belongsTo(MenuLocation::class, 'location', 'location_code');
    }

    public static function getHeaderParentMenus()
    {
        return Menu::where([
            'parent_id' => null,
            'location' => 'header',
        ])->orderBy('order', 'ASC')->get();
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function getAllParents()
    {
        $parents = collect();

        $parent = $this->parent;
        while ($parent) {
            $parents->push($parent);
            $parent = $parent->parent;
        }

        return $parents->reverse(); // So it goes from top-level to immediate parent
    }
    protected $appends = ['file_path_en', 'file_path_hi', 'icon_image_path'];
    public function getFilePathEnAttribute()
    {
        return $this->file_name ? base64_encode(Config::get('file_paths')['MENU_FILE_EN_PATH'] . '/' . $this->file_name) : null;
    }
    public function getFilePathHiAttribute()
    {
        return $this->file_name_hi ? base64_encode(Config::get('file_paths')['MENU_FILE_HI_PATH'] . '/' . $this->file_name_hi) : null;
    }

    public function getIconImagePathAttribute()
    {
        if ($this->icon_type === 'IMAGE' && $this->icon_png) {
            return asset('storage/' . Config::get('file_paths')['MENU_ICON_IMAGE_PATH'] . '/' . $this->icon_png);
        }
        return null;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('menu')
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Menu model has been {$eventName}");
    }


    public static function getLastUpdatedOrCreatedAt($type = null)
    {
        $model = new static();
        $tableName = $model->getTable();

        $columns = Schema::getColumnListing($tableName);

        $query = DB::table($tableName)->whereNull('deleted_at');

        if ($type && Schema::hasColumn($tableName, 'type')) {
            $query->where('type', $type);
        }

        if (in_array('updated_at', $columns)) {
            $timestamp = $query->max('updated_at');
        } elseif (in_array('created_at', $columns)) {
            $timestamp = $query->max('created_at');
        } else {
            throw new \Exception("Table '{$tableName}' has no timestamps.");
        }

        return $timestamp
            ? Carbon::parse($timestamp)->format('d-m-Y H:i:s')
            : null;
    }
}
