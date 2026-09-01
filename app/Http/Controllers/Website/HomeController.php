<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Repositories\PageRepository;
use App\Services\AnnouncementService;
use App\Services\CircularCategoryService;
use App\Services\CircularService;
use App\Services\EventService;
use App\Services\GovernmentPortalService;
use App\Services\HomeAboutService;
use App\Services\MenuService;
use App\Services\PhotoGalleryService;
use App\Services\QuickLinkService;
use App\Services\SliderService;
use App\Services\SocialMediaService;
use App\Services\TenderService;
use App\Services\VideoGalleryService;
use App\Services\WhoIsWhoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $menuService;
    protected $announcementService;
    protected $pageRepository;
    protected $sliderService;
    protected $whoIsWhoService;
    protected $circularService;
    protected $circularCategoryService;
    protected $tenderService;
    protected $eventService;
    protected $photoGalleryService;
    protected $videoGalleryService;
    protected $quickLinksService;
    protected $governmentPortalService;
    protected $homeAboutService;
    protected $socialMediaService;

    // Construct
    public function __construct()
    {
        $this->menuService = new MenuService();
        $this->announcementService = new AnnouncementService();
        $this->pageRepository = new PageRepository();
        $this->sliderService = new SliderService();
        $this->whoIsWhoService = new WhoIsWhoService();
        $this->circularService = new CircularService();
        $this->circularCategoryService = new CircularCategoryService();
        $this->tenderService = new TenderService();
        $this->eventService = new EventService();
        $this->photoGalleryService = new PhotoGalleryService();
        $this->videoGalleryService = new VideoGalleryService();
        $this->quickLinksService = new QuickLinkService();
        $this->governmentPortalService = new GovernmentPortalService();
        $this->homeAboutService = new HomeAboutService();
        $this->socialMediaService = new SocialMediaService();
    }

    // Homepage of website
    public function index()
    {
        // Fetch all data
        $announcements = $this->announcementService->findForPublicHomepage();
        $sliders = $this->sliderService->findForPublic();
        $homeAbout = $this->homeAboutService->findFirst();
        $socialMedias = $this->socialMediaService->findForPublic();
        $whoIsWhos = $this->whoIsWhoService->findAllForHomepage();
        $whatsNew = getWhatsNew(5);
        $circulars = $this->circularService->findForPublic(5);
        $tenders = $this->tenderService->findForPublic(5);
        $events = $this->eventService->findForPublic(5);
        $photoGalleries = $this->photoGalleryService->findForPublic(8);
        $videoGalleries = $this->videoGalleryService->findForPublic(8);
        $quickLinks = $this->quickLinksService->findForPublic();
        $governmentPortals = $this->governmentPortalService->findForPublic();

        return view('website.index', compact(
            'announcements',
            'sliders',
            'homeAbout',
            'socialMedias',
            'whoIsWhos',
            'circulars',
            'tenders',
            'events',
            'photoGalleries',
            'videoGalleries',
            'quickLinks',
            'governmentPortals',
            'whatsNew'
        ));
    }

    // Page
    public function page($page)
    {
        $url = '/' . $page;
        $pageTitle = '';
        $parentPageTitle = '';
        $pageDetails = '';
        $submenus = '';

        // Search in menu
        $menu = $this->menuService->findByUrlWithParents($url);
        if (!$menu) {
            // Search in page slug
            $page = $this->pageRepository->findBySlug($url);
            if ($page) {
                $pageTitle = getLocalizedDataFromObj($page, 'title');
                $pageDetails = $page;
            } else {
                abort(404);
            }
        } else {
            $parentId = $menu->id;
            $pageTitle = getLocalizedDataFromObj($menu, 'title');
            $pageDetails = $menu->page;

            if ($menu->parents) {
                $parentPageTitle = $this->menuService->fetchMainParentName($menu->parents);
                // Fetch all submenus based on parent menu
                $mainParent = $this->menuService->fetchMainParent($menu->parents);
                $parentId = $mainParent->id;
            }

            $submenus = $this->menuService->findAllById($parentId);
        }

        // Check if page is published and page details are not empty
        if (!empty($pageDetails) && $pageDetails->is_published == 1) {
            return view('website.page', compact('pageTitle', 'parentPageTitle', 'submenus', 'pageDetails'));
        } else {
            // Circular view 
            if ($url == '/notifications-circulars/vacancy-circular') {
                $circularCategories = $this->circularCategoryService->findForPublic();
                $circulars = $this->circularService->findForPublicWithPagination(2);
                return view('website.circulars', compact('pageTitle', 'parentPageTitle', 'submenus', 'pageDetails', 'circulars', 'circularCategories'));
            }

            // Recruitment view 
            if ($url == '/notifications-circulars/recruitment') {
                $reqruitments = $this->circularService->findByCategoryWithPagination(2);
                return view('website.recruitments', compact('pageTitle', 'parentPageTitle', 'submenus', 'pageDetails', 'reqruitments'));
            }

            // Tender view
            if ($url == '/notifications-circulars/tenders') {
                $tenders = $this->tenderService->findForPublicWithPagination(1);
                return view('website.tenders', compact('pageTitle', 'parentPageTitle', 'submenus', 'pageDetails', 'tenders'));
            }

            // Who is who view
            if ($url == '/about-us/whos-who') {
                $whoIsWhos = $this->whoIsWhoService->findAllForWhoIsWho();
                return view('website.who_is_who', compact('pageTitle', 'parentPageTitle', 'submenus', 'pageDetails', 'whoIsWhos'));
            }

            abort(404);
        }
    }
}
