<?php

namespace App\Http\Controllers\Secure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Menu;

class NotificationController extends Controller
{
    //  * Non-page modules with dedicated tables.
    //  */
    protected static array $dedicatedModules = [
        [
            'table' => 'tenders',
            'label' => 'Tender',
            'title_col' => 'title',
            'show_route' => 'tenders.show',
            'route_key' => 'tender',
            'approve_perm' => 'approve tender',
            'publish_perm' => 'publish tender',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'announcements',
            'label' => 'Announcement',
            'title_col' => 'title',
            'show_route' => 'announcements.show',
            'route_key' => 'announcement',
            'approve_perm' => 'approve announcement',
            'publish_perm' => 'publish announcement',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'technical_reports',
            'label' => 'Technical Report',
            'title_col' => 'title',
            'show_route' => 'technical_report.show',
            'route_key' => 'technicalReport',
            'approve_perm' => 'approve technical report',
            'publish_perm' => 'publish technical report',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'sliders',
            'label' => 'Slider',
            'title_col' => 'title',
            'show_route' => 'sliders.show',
            'route_key' => 'slider',
            'approve_perm' => 'approve slider',
            'publish_perm' => 'publish slider',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'latest_cpcbs',
            'label' => 'Latest CPCB',
            'title_col' => 'title',
            'show_route' => 'latest-cpcbs.show',
            'route_key' => 'latestCpcb',
            'approve_perm' => 'approve latest cpcb',
            'publish_perm' => 'publish latest cpcb',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'annual_reports',
            'label' => 'Annual Report',
            'title_col' => 'title',
            'show_route' => 'annual_report.show',
            'route_key' => 'annualReport',
            'approve_perm' => 'approve annual report',
            'publish_perm' => 'publish annual report',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'publications',
            'label' => 'Publication',
            'title_col' => 'title',
            'show_route' => 'publication.show',
            'route_key' => 'publication',
            'approve_perm' => 'approve publication',
            'publish_perm' => 'publish publication',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'directions',
            'label' => 'Direction',
            'title_col' => 'title',
            'show_route' => 'direction.show',
            'route_key' => 'direction',
            'approve_perm' => 'approve direction',
            'publish_perm' => 'publish direction',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'circulars',
            'label' => 'Circular',
            'title_col' => 'title',
            'show_route' => 'circulars.show',
            'route_key' => 'circular',
            'approve_perm' => 'approve circular',
            'publish_perm' => 'publish circular',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'photo_galleries',
            'label' => 'Photo Gallery',
            'title_col' => 'title',
            'show_route' => 'photo-gallery.show',
            'route_key' => 'photoGallery',
            'approve_perm' => 'approve photo gallery',
            'publish_perm' => 'publish photo gallery',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'video_galleries',
            'label' => 'Video Gallery',
            'title_col' => 'title',
            'show_route' => 'video-gallery.show',
            'route_key' => 'videoGallery',
            'approve_perm' => 'approve video gallery',
            'publish_perm' => 'publish video gallery',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'fortnightly_reports',
            'label' => 'Fortnightly Report',
            'title_col' => 'title',
            'show_route' => 'fortnightly-reports.show',
            'route_key' => 'fortnightlyReport',
            'approve_perm' => 'approve fortnightly report',
            'publish_perm' => 'publish fortnightly report',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'letters_issued',
            'label' => 'Letters Issued',
            'title_col' => 'title',
            'show_route' => 'letters-issued.show',
            'route_key' => 'lettersIssued',
            'approve_perm' => 'approve letters_issued',
            'publish_perm' => 'publish letters_issued',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'government_portals',
            'label' => 'Government Portal',
            'title_col' => 'title',
            'show_route' => 'government-portals.show',
            'route_key' => 'governmentPortal',
            'approve_perm' => 'approve government portal',
            'publish_perm' => 'publish government portal',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'portals',
            'label' => 'CPCB Portal',
            'title_col' => 'title',
            'show_route' => 'cpcb-portals.show',
            'route_key' => 'portal',
            'approve_perm' => 'approve cpcb portal',
            'publish_perm' => 'publish cpcb portal',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'epr_portals',
            'label' => 'EPR Portal',
            'title_col' => 'title',
            'show_route' => 'epr-portals.show',
            'route_key' => 'eprPortal',
            'approve_perm' => 'approve epr portal',
            'publish_perm' => 'publish epr portal',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'who_is_whos',
            'label' => 'Who Is Who',
            'title_col' => 'name',
            'show_route' => 'who-is-who.show',
            'route_key' => 'whoIsWho',
            'approve_perm' => 'approve who is who',
            'publish_perm' => 'publish who is who',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'contact_details',
            'label' => 'Contact Detail',
            'title_col' => 'title',
            'show_route' => 'contact-details.show',
            'route_key' => 'contactDetail',
            'approve_perm' => 'approve contact detail',
            'publish_perm' => 'publish contact detail',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'social_media',
            'label' => 'Social Media',
            'title_col' => 'name',
            'show_route' => 'social-medias.show',
            'route_key' => 'socialMedia',
            'approve_perm' => 'approve social media',
            'publish_perm' => 'publish social media',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'jobs',
            'label' => 'Job',
            'title_col' => 'title',
            'show_route' => 'jobs.show',
            'route_key' => 'job',
            'approve_perm' => 'approve job',
            'publish_perm' => 'publish job',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'job_posts',
            'label' => 'Job Post',
            'title_col' => 'title',
            'show_route' => 'job-posts.show',
            'route_key' => 'job_post',
            'approve_perm' => 'approve job post',
            'publish_perm' => 'publish job post',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'recruitment_announcements',
            'label' => 'Recruitment Announcement',
            'title_col' => 'title',
            'show_route' => 'recruitment-announcements.show',
            'route_key' => 'recruitment_announcement',
            'approve_perm' => 'approve recruitment announcement',
            'publish_perm' => 'publish recruitment announcement',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'faqs',
            'label' => 'FAQ',
            'title_col' => 'question',
            'show_route' => 'faq.show',
            'route_key' => 'faq',
            'approve_perm' => 'approve faq',
            'publish_perm' => 'publish faq',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'ngt_court_cases',
            'label' => 'NGT Court Case',
            'title_col' => 'title',
            'show_route' => 'ngt-court-cases.show',
            'route_key' => 'ngtCourtCase',
            'approve_perm' => 'approve ngt court case',
            'publish_perm' => 'publish ngt court case',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'gallery_events',
            'label' => 'Gallery Event',
            'title_col' => 'title',
            'show_route' => 'gallery-event.show',
            'route_key' => 'galleryEvent',
            'approve_perm' => 'approve gallery event',
            'publish_perm' => 'publish gallery event',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'studies_reports',
            'label' => 'Studies Report',
            'title_col' => 'title',
            'show_route' => 'studies_reports.show',
            'route_key' => 'studiesReport',
            'approve_perm' => 'approve studies report',
            'publish_perm' => 'publish studies report',
            'created_by_col' => 'created_by',
        ],
        [
            'table' => 'comment_reports',
            'label' => 'Comment Report',
            'title_col' => 'title',
            'show_route' => 'comment-reports.show',
            'route_key' => 'commentReport',
            'approve_perm' => 'approve comment report',
            'publish_perm' => 'publish comment report',
            'created_by_col' => 'created_by',
        ],
    ];

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }
        $isAdmin = $user->hasRole('ADMIN') || $user->hasRole('SUPERADMIN');
        $isOperator = $user->hasRole('DATA_ENTRY_OPERATOR');
        $notifications = collect();

        // 1. Pre-fetch permissions once
        $userPerms = $isAdmin ? [] : $user->getAllPermissions()->pluck('name')->toArray();

        // 2. Process Dedicated Modules
        foreach (self::$dedicatedModules as $module) {
            $this->processModule($module, $user, $isAdmin, $isOperator, $notifications, $userPerms);
        }

        // 3. Process Dynamic Page Modules
        $this->processDynamicPages($user, $isAdmin, $isOperator, $notifications, $userPerms);

        // Filter duplicates (some items might appear in both dedicated and dynamic)
        $uniqueNotifications = $notifications->unique(function ($item) {
            return $item['module'] . $item['url'];
        });

        $sorted = $uniqueNotifications->sortByDesc('raw_created')->take(10)->values();

        return response()->json([
            'count' => $sorted->count(),
            'notifications' => $sorted,
        ]);
    }

    protected function processModule($module, $user, $isAdmin, $isOperator, &$notifications, $userPerms = [])
    {
        try {
            // Optimization: Avoid slow getSchemaBuilder->getColumnListing for dedicated modules
            // These tables are known to have id, is_approved, title, created_at
            $hasPublished = !in_array($module['table'], ['photo_galleries', 'video_galleries']); // Most have is_published
            $hasSoftDelete = true; // All our standard models have soft deletes

            $canApprove = $isAdmin || in_array(strtolower($module['approve_perm']), $userPerms);
            $canPublish = $isAdmin || in_array(strtolower($module['publish_perm']), $userPerms);

            $query = DB::table($module['table'])
                ->select(['id', $module['title_col'] . ' as title', 'updated_at', 'is_approved']);

            if ($hasPublished)
                $query->addSelect('is_published');
            if ($hasSoftDelete)
                $query->whereNull('deleted_at');

            // Pending Logic
            $query->where(function ($q) use ($hasPublished) {
                $q->where('is_approved', '!=', 1);
                if ($hasPublished)
                    $q->orWhere('is_published', 0);
            });

            if (!$isAdmin) {
                $query->where(function ($q) use ($canApprove, $canPublish, $isOperator, $hasPublished, $user, $module) {
                    $conditions = false;
                    if ($canApprove) {
                        $q->whereIn('is_approved', [0, 2]);
                        $conditions = true;
                    }
                    if ($canPublish) {
                        if ($hasPublished) {
                            $pubCond = function ($sq) {
                                $sq->where('is_approved', 1)->where('is_published', 0);
                            };
                            if (!$conditions)
                                $q->where($pubCond);
                            else
                                $q->orWhere($pubCond);
                        } else {
                            if (!$conditions)
                                $q->whereIn('is_approved', [0, 2]);
                            else
                                $q->orWhereIn('is_approved', [0, 2]);
                        }
                        $conditions = true;
                    }
                    if ($isOperator) {
                        $opCond = function ($sq) use ($user, $module) {
                            $sq->where($module['created_by_col'] ?? 'created_by', $user->id);
                        };
                        if (!$conditions)
                            $q->where($opCond);
                        else
                            $q->orWhere($opCond);
                        $conditions = true;
                    }

                    if (!$conditions) {
                        $q->whereRaw('1=0'); // Should not happen if perms checked
                    }
                });
            }

            $items = $query->orderByDesc('created_at')->limit(10)->get();

            foreach ($items as $item) {
                $statusLabel = $this->getStatusLabel($item);
                $url = '#';
                try {
                    $url = route($module['show_route'], [$module['route_key'] => $item->id]);
                } catch (\Exception $e) {
                }

                $notifications->push([
                    'module' => $module['label'],
                    'title' => Str::limit($item->title ?? 'Untitled', 50),
                    'url' => $url,
                    'status' => $statusLabel,
                    'created' => $item->updated_at ? Carbon::parse($item->updated_at)->diffForHumans() : '',
                    'raw_created' => $item->created_at ?? '0000-00-00 00:00:00',
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("NotificationController::processModule failed for [{$module['table']}]: " . $e->getMessage());
        }
    }

    protected function processDynamicPages($user, $isAdmin, $isOperator, &$notifications, $userPerms = [])
    {
        // Identify which groups the user can approve or publish
        $accessibleGroups = [];
        if (!$isAdmin) {
            foreach ($userPerms as $perm) {
                if (str_starts_with($perm, 'approve ')) {
                    $accessibleGroups[] = substr($perm, 8);
                } elseif (str_starts_with($perm, 'publish ')) {
                    $accessibleGroups[] = substr($perm, 8);
                }
            }
        }

        // Fetch all menus that belong to accessible groups OR all if admin/operator
        $menuQuery = Menu::whereNotNull('permission_group');
        if (!$isAdmin && !$isOperator && empty($accessibleGroups)) {
            return; // No accessible groups and not an operator
        }

        if (!$isAdmin && !$isOperator) {
            $menuQuery->whereIn(DB::raw('LOWER(permission_group)'), $accessibleGroups);
        }

        $menus = $menuQuery->get();
        if ($menus->isEmpty())
            return;

        $menuIds = $menus->pluck('id')->toArray();
        $menuMap = $menus->pluck('title', 'id');

        // Single bulk query for all relevant pages
        $query = DB::table('pages')
            ->select(['id', 'title', 'updated_at', 'is_approved', 'is_published', 'menu_id'])
            ->whereIn('menu_id', $menuIds)
            ->whereNull('deleted_at');

        $query->where(function ($q) {
            $q->where('is_approved', '!=', 1)
                ->orWhere(function ($sq) {
                    $sq->where('is_approved', 1)->where('is_published', 0);
                });
        });

        if (!$isAdmin) {
            $query->where(function ($q) use ($isOperator, $user, $userPerms, $menus) {
                $conditions = false;

                // Group-based permissions check
                foreach ($menus as $m) {
                    $g = strtolower($m->permission_group);
                    $canApprove = in_array("approve $g", $userPerms);
                    $canPublish = in_array("publish $g", $userPerms);

                    if ($canApprove || $canPublish) {
                        $q->orWhere(function ($sq) use ($m, $canApprove, $canPublish) {
                            $sq->where('menu_id', $m->id);
                            if ($canApprove && !$canPublish)
                                $sq->whereIn('is_approved', [0, 2]);
                            elseif ($canPublish && !$canApprove)
                                $sq->where('is_approved', 1)->where('is_published', 0);
                        });
                        $conditions = true;
                    }
                }

                if ($isOperator) {
                    if (!$conditions)
                        $q->where('created_by', $user->id);
                    else
                        $q->orWhere('created_by', $user->id);
                    $conditions = true;
                }
            });
        }

        $items = $query->orderByDesc('created_at')->limit(20)->get();

        foreach ($items as $item) {
            $statusLabel = $this->getStatusLabel($item);
            $notifications->push([
                'module' => $menuMap[$item->menu_id] ?? 'Page',
                'title' => Str::limit($item->title ?? 'Untitled', 50),
                'url' => route('pages.show', ['page' => $item->id]),
                'status' => $statusLabel,
                'created' => $item->updated_at ? Carbon::parse($item->updated_at)->diffForHumans() : '',
                'raw_created' => $item->created_at ?? '0000-00-00 00:00:00',
            ]);
        }
    }

    protected function getStatusLabel($item)
    {
        $isApproved = (int) ($item->is_approved ?? 0);
        $isPublished = isset($item->is_published) ? (int) $item->is_published : 1;

        if ($isApproved === 2)
            return 'Rejected';

        if ($isApproved === 0) {
            return ($isPublished === 0) ? 'Draft' : 'Pending Approval';
        }

        if ($isPublished === 0)
            return 'Unpublished';

        return 'Pending';
    }
}
