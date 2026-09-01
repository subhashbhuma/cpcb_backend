<?php

use App\Http\Controllers\Api\CaptchaController;
use App\Http\Controllers\CMSFileController;
use App\Http\Controllers\Secure\JobController;
use App\Http\Controllers\Secure\JobResultController;
use App\Http\Controllers\Secure\WhoIsWhoController;
use App\Http\Controllers\Secure\HomeAboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Secure\AnnouncementController;
use App\Http\Controllers\Secure\AnnualReportController;
use App\Http\Controllers\Secure\EnvironmentalRegulationController;
use App\Http\Controllers\Secure\EnvironmentalRegulationDetailController;
use App\Http\Controllers\Secure\TechnicalReportController;
use App\Http\Controllers\Secure\PublicationCategoryController;
use App\Http\Controllers\Secure\PublicationController;
use App\Http\Controllers\Secure\QuickLinkController;
use App\Http\Controllers\Secure\TenderController;
use App\Http\Controllers\Secure\PhotoGalleryController;
use App\Http\Controllers\Secure\VideoGalleryController;
use App\Http\Controllers\Secure\EventController;
use App\Http\Controllers\Secure\FaqController;
use App\Http\Controllers\Secure\FortnightlyReportController;
use App\Http\Controllers\Secure\GalleryEventController;
use App\Http\Controllers\Secure\GovernmentPortalController;
use App\Http\Controllers\Secure\HeadOfficeController;
use App\Http\Controllers\Secure\LatestCpcbController;
use App\Http\Controllers\Secure\NgtCourtCaseController;
use App\Http\Controllers\Secure\MenuController;
use App\Http\Controllers\Secure\PageController;
use App\Http\Controllers\Secure\SiteSettingController;
use App\Http\Controllers\Secure\SliderController;
use App\Http\Controllers\Secure\SocialMediaController;
use App\Http\Controllers\Secure\RegionalDirectorateController;
use App\Http\Controllers\Secure\DirectoryController;
use App\Http\Controllers\Secure\ContactDetailController;
use App\Http\Controllers\Secure\QueryFormSubjectController;
use App\Http\Controllers\Secure\ComplaintFormSubjectController;
use App\Http\Controllers\Secure\DirectionCategoryController;
use App\Http\Controllers\Secure\DirectionStateController;
use App\Http\Controllers\Secure\DirectionIssuedToController;
use App\Http\Controllers\Secure\DirectionSubjectController;
use App\Http\Controllers\Secure\DirectionActTypeController;
use App\Http\Controllers\Api\PublicFeedbackController;
use App\Http\Controllers\Api\PublicComplaintController;
use App\Http\Controllers\Secure\StudiesReportController;
use App\Http\Controllers\Secure\PortalController;
use App\Http\Controllers\Secure\AgraAirQualityController;
use App\Http\Controllers\Secure\AqiBulletinController;
use App\Http\Controllers\Secure\DirectionController;
use App\Http\Controllers\Secure\LettersIssuedController;
use App\Http\Controllers\Secure\DashboardController;
use App\Http\Controllers\Secure\SearchController;
use App\Http\Controllers\Secure\CircularController;
use App\Http\Controllers\Api\VisitorController;
use App\Http\Controllers\Api\InitController;
use App\Http\Controllers\Api\SlugInitController;
use App\Http\Controllers\Secure\InformationCenterController;
use App\Http\Controllers\Secure\InformationCenterDetailController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\Api\SmsController;

// ========================================
// Public API Routes
// ========================================
// Consolidated Layout Init
Route::get('/layout-init', [InitController::class, 'index'])->name('layout.init');
Route::get('/slug-init', [SlugInitController::class, 'index'])->name('slug.init');

Route::get('/jobs/fetch-all', [JobController::class, 'fetchAllForPublic'])->name('jobs.fetch-all-for-public');

Route::get('/announcements/fetch-all', [AnnouncementController::class, 'fetchAllForPublic'])->name('announcements.fetch-all-for-public');
Route::get('/announcements/fetch-all-latest', [AnnouncementController::class, 'fetchAllForPublicLatest'])->name('announcements.fetch-all-for-public-latest');
Route::post('/announcements/fetch-all-datatable/{type?}', [AnnouncementController::class, 'fetchAllForPublicDataTable'])->name('announcements.fetch-all-for-public-datatable');

Route::get('/homeAbout/fetch-all', [HomeAboutController::class, 'fetchAllForPublic'])->name('homeAbout.fetch-all-for-public');

Route::get('/annualReports/fetch-all', [AnnualReportController::class, 'fetchAllforPublic'])->name('annualreport.fetch-all-for-public');
Route::post('/annual-report/fetch-all-datatable', [AnnualReportController::class, 'fetchAllForPublicDataTable'])->name('annual-report.fetch-all-for-public-datatable');

Route::get('/technicalReport/fetch-all', [TechnicalReportController::class, 'fetchAllforPublic'])->name('technicalReport.fetch-all-for-public');
Route::post('/technical-report/fetch-all-datatable/{type}', [TechnicalReportController::class, 'fetchAllForPublicDataTable'])->name('technical-report.fetch-all-for-public-datatable');

Route::get('/publicationCategory/fetch-all', [PublicationCategoryController::class, 'fetchAllforPublic'])->name('publicationCategory.fetch-all-for-public');
Route::get('/publicationCategory/fetch-by-id/{id}', [PublicationCategoryController::class, 'fetchByIdForPublic'])->name('publicationCategory.fetch-by-id-for-public');

Route::get('/publication/fetch-all', [PublicationController::class, 'fetchAllforPublic'])->name('publication.fetch-all-for-public');
Route::get('/publication/fetch-by-category/{categories}', [PublicationController::class, 'fetchByCategoryForPublic'])->name('publication.fetch-by-category-for-public');

Route::get('/quick-links/fetch-all', [QuickLinkController::class, 'fetchAllForPublic'])->name('quick-links.fetch-all-for-public');

Route::get('/tender/fetch-all', [TenderController::class, 'fetchAllforPublic'])->name('tender.fetch-all-for-public');
Route::post('/tender/fetch-all-datatable/{type}', [TenderController::class, 'fetchAllForPublicDataTable'])->name('tender.fetch-all-for-public-datatable');

Route::get('/circulars/fetch-all', [CircularController::class, 'fetchAllforPublic'])->name('circular.fetch-all-for-public');
Route::post('/circulars/fetch-all-datatable/{type}', [CircularController::class, 'fetchAllForPublicDataTable'])->name('circular.fetch-all-for-public-datatable');
Route::get('/circulars/fetch-all-category', [CircularController::class, 'fetchAllCategory'])->name('circular.fetch-all-category');

Route::get('/public-files', [CMSFileController::class, 'getfileurl'])->name('get-file-url');
Route::get('/resolve-old-file', [CMSFileController::class, 'resolveOldSiteFile'])->name('resolve-old-file');

Route::get('/whoiswho/fetch-all', [WhoIsWhoController::class, 'findAllForPublic'])->name('whoiswho.fetch-all-for-who-is-who');
Route::get('/whoiswho/fetch-by-designation/{designation}', [WhoIsWhoController::class, 'findByDesignation'])->name('whoiswho.fetch-by-designation');
Route::get('/whoiswho/fetch-home-page', [WhoIsWhoController::class, 'whoIsWhoHomePage'])->name('whoiswho.fetch-home-page');

Route::get('/photoGallery/fetch-all', [PhotoGalleryController::class, 'findAllforPublic'])->name('photoGallery.fetch-all-for-public');
Route::get('/photoGallery/fetch-by-id/{id}', [PhotoGalleryController::class, 'findByIdWithImages'])->name('photoGallery.fetch-by-id-for-public');

Route::get('/videoGallery/fetch-all', [VideoGalleryController::class, 'findAllforPublic'])->name('videoGallery.fetch-all-for-public');

Route::get('/events/fetch-all', [EventController::class, 'findAllforPublic'])->name('events.fetch-all-for-public');



Route::get('/slider/fetch-all', [SliderController::class, 'findAllForPublic'])->name('slider.fetch-all-for-public');

Route::get('/faq/fetch-all/', [FaqController::class, 'fetchAllForPublic'])->name('faq.fetch-all-for-public');

Route::get('/socialMedia/fetch-all/', [SocialMediaController::class, 'findForPublic'])->name('slider.fetch-for-public');

Route::get('/govtPortal/fetch-all/', [GovernmentPortalController::class, 'findForPublic'])->name('govtPortal.fetch-for-public');

Route::get('/site-settings/fetch-all', [SiteSettingController::class, 'findAllForPublic'])->name('site_settings.fetch-for-public');

Route::get('/latest-cpcb/fetch', [LatestCpcbController::class, 'fetchAllForPublic'])->name('latest_cpcb.fetch-all-for-public');
Route::post('/latest-cpcb/fetch-all/{type}', [LatestCpcbController::class, 'fetchAllForPublicDataTable'])->name('latest_cpcb.fetch-all-for-public-datatable');

Route::get('/ngt-court-cases/fetch', [NgtCourtCaseController::class, 'fetchAllForPublic'])->name('ngt_court_cases.fetch-all-for-public');
Route::post('/ngt-court-cases/fetch-all-for-public-datatable', [NgtCourtCaseController::class, 'fetchAllForPublicDataTable'])->name('ngt_court_cases.fetch-all-for-public-datatable');

Route::get('/fortnightly-reports/fetch', [FortnightlyReportController::class, 'fetchAllForPublic'])->name('fortnightly_reports.fetch-all-for-public');
Route::post('/fortnightly-reports/fetch-all-for-public-datatable', [FortnightlyReportController::class, 'fetchAllForPublicDataTable'])->name('fortnightly_reports.fetch-all-for-public-datatable');

Route::get('/environmental-regulation/fetch-all', [EnvironmentalRegulationController::class, 'findAllforPublic'])->name('environmentalRegulation.fetch-all-for-public');
Route::get('/gallery/events/fetch-all', [GalleryEventController::class, 'findAllforPublic'])->name('galleryEvent.fetch-all-for-public');
Route::get('/gallery/events/photos', [PhotoGalleryController::class, 'byEvent'])->name('photoGalleryByEvent.fetch-all-for-public');
Route::get('/gallery/events/videos', [VideoGalleryController::class, 'byEvent'])->name('videoGalleryByEvent.fetch-all-for-public');
Route::get('/videoGallery/fetch', [VideoGalleryController::class, 'findforPublicbyid'])->name('videoGallery.fetch-for-public-by-id');
Route::get('/environmental-regulation-detail/fetch', [EnvironmentalRegulationDetailController::class, 'byRegulationId'])->name('environmentalRegulationDetail.fetch-all-for-public');
Route::get('/environmental-regulation-detail/fetch-by-url/{url}', [EnvironmentalRegulationDetailController::class, 'findByUrlForPublic'])->name('evr-by-url.fetch-by-id-for-public');

// Information Center Public Routes
Route::get('/information-center/fetch-all', [InformationCenterController::class, 'findAllforPublic'])->name('informationCenter.fetch-all-for-public');
Route::get('/information-center-detail/fetch', [InformationCenterDetailController::class, 'byCenterId'])->name('informationCenterDetail.fetch-all-for-public');
Route::get('/information-center-detail/fetch-by-url/{url}', [InformationCenterDetailController::class, 'findByUrlForPublic'])->name('info-center-by-url.fetch-by-id-for-public');
Route::get('/regional-directorate/fetch-all', [RegionalDirectorateController::class, 'findAllforPublic'])->name('regionalDirectorate.fetch-all-for-public');
Route::get('/head-office/fetch-all', [HeadOfficeController::class, 'findAllforPublic'])->name('headOffice.fetch-all-for-public');
Route::post('/jobs/fetch-all-datatable/{type}', [JobController::class, 'fetchAllForPublicDataTable'])->name('jobs.fetch-all-for-public-datatable');
Route::post('/contact-directory/fetch-all-datatable', [DirectoryController::class, 'fetchAllForPublicDataTable'])->name('contact-directory.fetch-all-for-public-datatable');
Route::get('/contact-directory/download-directory', [DirectoryController::class, 'downloadDirectoryForPublic'])->name('contact-directory.download-directory');
Route::get('/contact-detail/fetch-all', [ContactDetailController::class, 'fetchAllForPublic'])->name('contact-detail.fetch-all-for-public');

Route::get('/form-query-subject/fetch-all', [QueryFormSubjectController::class, 'fetchAllForPublic'])->name('form-query-subject.fetch-all-for-public');
Route::get('/form-complaint-subject/fetch-all', [ComplaintFormSubjectController::class, 'fetchAllForPublic'])->name('form-complaint-subject.fetch-all-for-public');

// Direction Master Data
Route::get('/direction-category/fetch-all', [DirectionCategoryController::class, 'fetchAllForPublic'])->name('direction-category.fetch-all-for-public');
Route::get('/direction-state/fetch-all', [DirectionStateController::class, 'fetchAllForPublic'])->name('direction-state.fetch-all-for-public');
Route::get('/direction-issued-to/fetch-all', [DirectionIssuedToController::class, 'fetchAllForPublic'])->name('direction-issued-to.fetch-all-for-public');
Route::get('/direction-subject/fetch-all', [DirectionSubjectController::class, 'fetchAllForPublic'])->name('direction-subject.fetch-all-for-public');
Route::get('/direction-act-type/fetch-all', [DirectionActTypeController::class, 'fetchAllForPublic'])->name('direction-act-type.fetch-all-for-public');

// Direction & Letters Issued Public DataTable
Route::post('/direction/fetch-all-datatable/{type}', [DirectionController::class, 'fetchAllForPublicDataTable'])->name('direction.fetch-all-for-public-datatable');
Route::post('/letters-issued/fetch-all-datatable', [LettersIssuedController::class, 'fetchAllForPublicDataTable'])->name('letters-issued.fetch-all-for-public-datatable');
Route::post('/comment-reports/fetch-all-datatable/{type?}', [\App\Http\Controllers\Secure\CommentReportController::class, 'fetchAllForPublicDataTable'])->name('comment-reports.fetch-all-for-public-datatable');

// Captcha
Route::get('/captcha/refresh', [CaptchaController::class, 'getCaptcha']);
Route::post('/captcha/verify', [CaptchaController::class, 'verifyCaptcha']);

Route::get('/public-files-detail', [CMSFileController::class, 'getfileDetail'])->name('get-file-detail');
Route::get('/studies-report/fetch-all', [StudiesReportController::class, 'fetchAllForPublic'])->name('studies-report.fetch-all-for-public');
Route::get('/cpcb-portals/fetch-all', [PortalController::class, 'fetchAllForPublic'])->name('cpcb-portals.fetch-all-for-public');
Route::get('/epr-portals/fetch-all', [\App\Http\Controllers\Secure\EprPortalController::class, 'fetchAllForPublic'])->name('epr-portals.fetch-all-for-public');
Route::get('/agra-airquality-monthly/fetch', [AgraAirQualityController::class, 'getMonthlyAirQuality'])->name('agra-airquality-monthly.fetch-all-for-public');
Route::get('/search-air-quality-file', [AgraAirQualityController::class, 'searchAirQualityFile'])->name('search-air-quality-file');

// Global Search Routes
Route::post('/search/global', [SearchController::class, 'globalSearch'])->name('search.global');
Route::post('/search/by-type/{type}', [SearchController::class, 'searchByType'])->name('search.by-type');
Route::get('/search/suggestions', [SearchController::class, 'getSearchSuggestions'])->name('search.suggestions');

// Sitemap Route (GIGW 3.0 Compliance)
Route::get('/pages/sitemap', [\App\Http\Controllers\Api\SitemapController::class, 'getPagesForSitemap'])->name('pages.sitemap');


// aqi bulletin routes
Route::get('/today-aqi-bulletin-report', [AqiBulletinController::class, 'getTodayAqiBulletinFile'])->name('today-aqi-bulletin-report');
Route::get('/today-delhi-ncr-aqi-bulletin-report', [AqiBulletinController::class, 'getTodayDelhiNcrAqiBulletinFile'])->name('today-delhi-ncr-aqi-bulletin-report');
Route::post('/aqi-bulletin/fetchAqiList', [AqiBulletinController::class, 'fetchAqiList'])->name('aqi-bulletin.fetchAqiList');

// ========================================
// Visitor Tracking
// ========================================
Route::post('/visitor/add', [VisitorController::class, 'add'])->name('visitor.add');
Route::get('/visitor/stats', [VisitorController::class, 'stats'])->name('visitor.stats');


Route::get('/menus/fetch-by-location/{location}', [MenuController::class, 'fetchAllMenusByLocation'])->name('menus.fetch-all-for-public-by-location');
Route::get('/menus/fetch-by-url/', [MenuController::class, 'fetchAllMenusByUrl'])->name('menus.fetch-all-for-public-by-url');
Route::get('/menus/breadcrumb', [MenuController::class, 'getBreadcrumb'])->name('menus.get-breadcrumb');
Route::get('/menus/fetch-inner-menus-by-parent-title', [MenuController::class, 'fetchInnerMenusByParentTitle'])->name('menus.fetch-inner-menus-by-parent-title');
Route::get('/menus/fetch-inner-menus-by-parent-full-title', [MenuController::class, 'fetchInnerMenusByParentFullTitle'])->name('menus.fetch-inner-menus-by-parent-full-title');
Route::get('/menus/fetch-all', [MenuController::class, 'fetchAllMenus'])->name('menus.fetch-all');
Route::get('/pages/fetch/{url}', [PageController::class, 'findByUrlForPublic'])->name('menus.fetch-by-id-for-public');
Route::get('/pages/fetch-by-menu/', [PageController::class, 'findByMenuForPublic'])->name('menus.fetch-by-menu-for-public');

// for checking today entries
Route::get('/today-entries', [DashboardController::class, 'getTodayEntries'])->name('today-entries');


// ========================================
// Strict Rate Limited Routes — Form Submissions (Increased for testing)
// ========================================
// ,'throttle:60,1'
Route::middleware(['scanUploadedFiles'])->group(function () {
    Route::post('/submit-feedback', [PublicFeedbackController::class, 'store'])->name('feedback.submit');
    Route::post('/submit-complaint', [PublicComplaintController::class, 'store'])->name('complaint.submit');
});

Route::post('/send-otp/email', [OtpController::class, 'sendPublicEmailOtp'])
    ->middleware('otp.throttle.generation:5,30')
    ->name('otp.send.email');

Route::post('/send-otp/mobile', [OtpController::class, 'sendPublicMobileOtp'])
    ->middleware('otp.throttle.generation:5,30')
    ->name('otp.send.mobile');

Route::post('/verify-otp/email', [OtpController::class, 'verifyPublicEmailOtp'])
    ->middleware('otp.throttle.verification:3,30')
    ->name('otp.verify.email');

Route::post('/verify-otp/mobile', [OtpController::class, 'verifyPublicMobileOtp'])
    ->middleware('otp.throttle.verification:3,30')
    ->name('otp.verify.mobile');

// ========================================
// Token-Protected API Routes (Custom Auth)
// ========================================
// Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/aqi/daily/upload', [AqiBulletinController::class, 'uploadDailyAqiBulletin']);
    Route::post('/aqi/delhi-ncr/upload', [AqiBulletinController::class, 'uploadDelhiNcrAqiBulletin']);
// });

// ========================================
// Authenticated API Routes — Require Sanctum Auth
// ========================================
Route::middleware(['auth:sanctum', 'throttle:20,1'])->group(function () {
    // Other frontend admin authenticated routes can go here
});



Route::post('/sms/check', [SmsController::class, 'sendOtp']);