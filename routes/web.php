<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Secure\AdditionalLogoController;
use App\Http\Controllers\Secure\AnnouncementController;
use App\Http\Controllers\Secure\AnnualReportController;
use App\Http\Controllers\Secure\AuditLogController;
use App\Http\Controllers\Secure\AuthenticationLogController;
use App\Http\Controllers\Secure\CircularController;
use App\Http\Controllers\Secure\ContactDetailController;
use App\Http\Controllers\Secure\DashboardController;
use App\Http\Controllers\Secure\DesignationController;
use App\Http\Controllers\Secure\DirectoryController;
use App\Http\Controllers\Secure\DivisionController;
use App\Http\Controllers\Secure\EmployeeController;
use App\Http\Controllers\Secure\EnvironmentalRegulationController;
use App\Http\Controllers\Secure\EnvironmentalRegulationDetailController;
use App\Http\Controllers\Secure\EventController;
use App\Http\Controllers\Secure\InformationCenterController;
use App\Http\Controllers\Secure\InformationCenterDetailController;
use App\Http\Controllers\Secure\FaqController;
use App\Http\Controllers\Secure\GovernmentPortalController;
use App\Http\Controllers\Secure\HomeAboutController;
use App\Http\Controllers\Secure\JobController;
use App\Http\Controllers\Secure\LabsCategoryController;
use App\Http\Controllers\Secure\LabsPageController;
use App\Http\Controllers\Secure\LatestCpcbController;
use App\Http\Controllers\Secure\NgtCourtCaseController;
use App\Http\Controllers\Secure\QualityZoneController;
use App\Http\Controllers\Secure\AgraAirQualityController;
use App\Http\Controllers\Secure\MediaController;
use App\Http\Controllers\Secure\MenuController;
use App\Http\Controllers\Secure\EmployeeMenuController;
use App\Http\Controllers\Secure\PageController;
use App\Http\Controllers\Secure\PageCategoryController;
use App\Http\Controllers\Secure\QueryFormSubjectController;
use App\Http\Controllers\Secure\ComplaintFormSubjectController;
use App\Http\Controllers\Secure\FeedbackController;
use App\Http\Controllers\Secure\ComplaintController;
use App\Http\Controllers\Secure\PhotoGalleryController;
use App\Http\Controllers\Secure\FortnightlyReportController;
use App\Http\Controllers\Secure\GalleryEventController;
use App\Http\Controllers\Secure\PortalController;
use App\Http\Controllers\Secure\EprPortalController;
use App\Http\Controllers\Secure\ProfileController;
use App\Http\Controllers\Secure\PublicationCategoryController;
use App\Http\Controllers\Secure\PublicationController;
use App\Http\Controllers\Secure\QuickLinkController;
use App\Http\Controllers\Secure\RegionalDirectoriesController;
use App\Http\Controllers\Secure\RoleController;
use App\Http\Controllers\Secure\SiteSettingController;
use App\Http\Controllers\Secure\SliderController;
use App\Http\Controllers\Secure\SocialMediaController;
use App\Http\Controllers\Secure\SubjectAreaController;
use App\Http\Controllers\Secure\StudiesReportController;
use App\Http\Controllers\Secure\HeadOfficeController;
use App\Http\Controllers\Secure\RegionalDirectorateController;
use App\Http\Controllers\Secure\TechnicalReportController;
use App\Http\Controllers\Secure\TenderCategoryController;
use App\Http\Controllers\Secure\TenderController;
use App\Http\Controllers\Secure\UserController;
use App\Http\Controllers\Secure\VideoGalleryController;
use App\Http\Controllers\Secure\WhoIsWhoController;
use App\Http\Controllers\Secure\ZonalOfficeController;
use App\Http\Controllers\Secure\DirectionTypeController;
use App\Http\Controllers\Secure\DirectionController;
use App\Http\Controllers\Secure\DirectionCategoryController;
use App\Http\Controllers\Secure\DirectionStateController;
use App\Http\Controllers\Secure\DirectionIssuedToController;
use App\Http\Controllers\Secure\DirectionSubjectController;
use App\Http\Controllers\Secure\DirectionActTypeController;
use App\Http\Controllers\Secure\LettersIssuedController;
use App\Http\Controllers\Secure\CommentReportController;
use App\Http\Controllers\Secure\JobPostController;
use App\Http\Controllers\Secure\RecruitmentAnnouncementController;
use App\Http\Controllers\FileViewController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\Secure\EmployeeDynamicPageController;
use App\Http\Controllers\Secure\SitemapController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

Route::middleware('referer.check')->group(function () {
    Route::get('language/{lang}', [LanguageController::class, 'switch'])->name('language.switch');



    Route::prefix('secure')->middleware(['auth', 'geoFence', 'CheckConcurrentLogin', 'checkPasswordExpiry', 'preventBackHistory'])->group(function () {
        // ------------------------------------
        // Notifications
        // ------------------------------------
        Route::get('/notifications/fetch', [\App\Http\Controllers\Secure\NotificationController::class, 'fetch'])->name('notifications.fetch');
    });
// , 'throttle:40,1'
    Route::prefix('secure')->middleware(['auth', 'geoFence', 'CheckConcurrentLogin', 'checkPasswordExpiry', 'scanUploadedFiles','preventBackHistory'])->group(function () {
        // Route::post('/logout', [LogoutController::class, 'index'])->name('logout');
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        Route::get('/logout', [LogoutController::class, 'logout'])->name('secure.logout');

        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('secure.dashboard');


        Route::get('/audit-logs', [AuditLogController::class, 'index'])->middleware('can:view audit log')->name('audit-logs.index');
        Route::post('/audit-logs/fetch-for-datatable', [AuditLogController::class, 'fetchForDatatable'])->middleware('can:view audit log')->name('audit-logs.fetch-for-datatable');
        Route::get('/audit-logs/download-all', [AuditLogController::class, 'downloadAllLogs'])->middleware('can:view audit log')->name('audit-logs.download-all');
        Route::get('/audit-logs/download-audit', [AuditLogController::class, 'downloadAuditLogs'])->middleware('can:view audit log')->name('audit-logs.download-audit');
        Route::get('/audit-logs/download-auth', [AuditLogController::class, 'downloadAuthLogs'])->middleware('can:view audit log')->name('audit-logs.download-auth');
        Route::get('/audit-logs/download-system', [AuditLogController::class, 'downloadSystemLogs'])->middleware('can:view audit log')->name('audit-logs.download-system');
        Route::get('/audit-logs/export/{id}', [AuditLogController::class, 'downloadReadyExport'])->middleware('can:view audit log')->name('audit-logs.download-ready');
        // ------------------------------------
        // Users
        // ------------------------------------
        Route::get('/users', [UserController::class, 'index'])->middleware('can:view user')->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->middleware('can:add user')->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->middleware('can:add user')->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->middleware('can:view user')->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('can:edit user')->name('users.edit');
        Route::post('/users/reset-password/{user}', [UserController::class, 'resetPassword'])->middleware('can:reset password')->name('users.reset-password');
        Route::put('/users/{user}', [UserController::class, 'update'])->middleware('can:edit user')->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:delete user')->name('users.destroy');
        Route::post('/users/fetch-for-datatable', [UserController::class, 'fetchForDatatable'])->middleware('can:view user')->name('users.fetch-for-datatable');

        // ------------------------------------
        // Employees
        // ------------------------------------
        Route::get('/employee', [EmployeeController::class, 'index'])->middleware('can:view employee')->name('employee.index');
        Route::get('/employee/create', [EmployeeController::class, 'create'])->middleware('can:add employee')->name('employee.create');
        Route::post('/employee', [EmployeeController::class, 'store'])->middleware('can:add employee')->name('employee.store');
        Route::get('/employee/{employee}/edit', [EmployeeController::class, 'edit'])->middleware('can:edit employee')->name('employee.edit');
        Route::put('/employee/{employee}', [EmployeeController::class, 'update'])->middleware('can:edit employee')->name('employee.update');
        Route::delete('/employee/{employee}', [EmployeeController::class, 'destroy'])->middleware('can:delete employee')->name('employee.destroy');
        Route::post('/employee/fetch-for-datatable', [EmployeeController::class, 'fetchForDatatable'])->middleware('can:view employee')->name('employee.fetch-for-datatable');
        Route::post('/employee/bulk-import', [EmployeeController::class, 'bulkImport'])->middleware('can:add employee')->name('employee.bulk-import');
        Route::get('/employee/download-format', [EmployeeController::class, 'downloadFormat'])->middleware('can:view employee')->name('employee.download-format');

        // ------------------------------------
        // Roles
        // ------------------------------------
        Route::get('/roles', [RoleController::class, 'index'])->middleware('can:view role')->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->middleware('can:add role')->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->middleware('can:add role')->name('roles.store');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('can:view role')->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('can:edit role')->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('can:edit role')->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('can:delete role')->name('roles.destroy');
        Route::post('/roles/fetch-for-datatable', [RoleController::class, 'fetchRolesForDatatable'])->middleware('can:view role')->name('roles.fetch-for-datatable');

        // ------------------------------------
        // Menu
        // ------------------------------------
        Route::get('/menus', [MenuController::class, 'index'])->middleware('can:view menu')->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->middleware('can:add menu')->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->middleware('can:add menu')->name('menus.store');
        Route::get('/menus/{menu}', [MenuController::class, 'show'])->middleware('can:view menu')->name('menus.show');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->middleware('can:edit menu')->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->middleware('can:edit menu')->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->middleware('can:delete menu')->name('menus.destroy');
        Route::post('/menus/update-order', [MenuController::class, 'updateOrder'])->middleware('can:view menu')->name('menus.updateOrder');

        // ------------------------------------
        // Employee Menu
        // ------------------------------------
        Route::get('/employee-menus', [EmployeeMenuController::class, 'index'])->middleware('can:view employee menu')->name('employee-menus.index');
        Route::get('/employee-menus/create', [EmployeeMenuController::class, 'create'])->middleware('can:add employee menu')->name('employee-menus.create');
        Route::post('/employee-menus', [EmployeeMenuController::class, 'store'])->middleware('can:add employee menu')->name('employee-menus.store');
        Route::get('/employee-menus/{menu}/edit', [EmployeeMenuController::class, 'edit'])->middleware('can:edit employee menu')->name('employee-menus.edit');
        Route::put('/employee-menus/{menu}', [EmployeeMenuController::class, 'update'])->middleware('can:edit employee menu')->name('employee-menus.update');
        Route::delete('/employee-menus/{menu}', [EmployeeMenuController::class, 'destroy'])->middleware('can:delete employee menu')->name('employee-menus.destroy');
        Route::post('/employee-menus/update-order', [EmployeeMenuController::class, 'updateOrder'])->middleware('can:view employee menu')->name('employee-menus.updateOrder');

        // ------------------------------------
        // Site Settings
        // ------------------------------------
        Route::resource('/site-settings', SiteSettingController::class)->middleware(['can:edit site setting']);

        // ------------------------------------
        // Sitemap Export
        // ------------------------------------
        Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap.index');
        Route::get('/sitemap/export', [SitemapController::class, 'export'])->name('sitemap.export');



        // ------------------------------------
        // Tender Category
        // ------------------------------------
        Route::get('/tender_category', [TenderCategoryController::class, 'index'])->middleware('can:view tender category')->name('tender_category.index');
        Route::get('/tender_category/create', [TenderCategoryController::class, 'create'])->middleware('can:add tender category')->name('tender_category.create');
        Route::post('/tender_category', [TenderCategoryController::class, 'store'])->middleware('can:add tender category')->name('tender_category.store');
        Route::get('/tender_category/{tenderCategory}', [TenderCategoryController::class, 'show'])->middleware('can:view tender category')->name('tender_category.show');
        Route::get('/tender_category/{tenderCategory}/edit', [TenderCategoryController::class, 'edit'])->middleware('can:edit tender category')->name('tender_category.edit');
        Route::put('/tender_category/{tenderCategory}', [TenderCategoryController::class, 'update'])->middleware('can:edit tender category')->name('tender_category.update');
        Route::delete('/tender_category/{tenderCategory}', [TenderCategoryController::class, 'destroy'])->middleware('can:delete tender category')->name('tender_category.destroy');
        Route::post('/tender_category/fetch-for-datatable', [TenderCategoryController::class, 'fetchForDatatable'])->middleware('can:view tender category')->name('tender_category.fetch-for-datatable');
        Route::put('/tender_category/approve/{tenderCategory}', [TenderCategoryController::class, 'approve'])->middleware('can:approve tender category')->name('tender_category.approve');
        Route::put('/tender_category/publish/{tenderCategory}', [TenderCategoryController::class, 'publish'])->middleware('can:publish tender category')->name('tender_category.publish');

        // ------------------------------------
        // Tender Zonal Office Setup
        // ------------------------------------
        Route::get('/zonal_office', [ZonalOfficeController::class, 'index'])->middleware('can:view zonal office')->name('zonal_office.index');
        Route::get('/zonal_office/create', [ZonalOfficeController::class, 'create'])->middleware('can:add zonal office')->name('zonal_office.create');
        Route::post('/zonal_office', [ZonalOfficeController::class, 'store'])->middleware('can:add zonal office')->name('zonal_office.store');
        Route::get('/zonal_office/{zonalOffice}', [ZonalOfficeController::class, 'show'])->middleware('can:view zonal office')->name('zonal_office.show');
        Route::get('/zonal_office/{zonalOffice}/edit', [ZonalOfficeController::class, 'edit'])->middleware('can:edit zonal office')->name('zonal_office.edit');
        Route::put('/zonal_office/{zonalOffice}', [ZonalOfficeController::class, 'update'])->middleware('can:edit zonal office')->name('zonal_office.update');
        Route::delete('/zonal_office/{zonalOffice}', [ZonalOfficeController::class, 'destroy'])->middleware('can:delete zonal office')->name('zonal_office.destroy');
        Route::post('/zonal_office/fetch-for-datatable', [ZonalOfficeController::class, 'fetchForDatatable'])->middleware('can:view zonal office')->name('zonal_office.fetch-for-datatable');
        Route::put('/zonal_office/approve/{zonalOffice}', [ZonalOfficeController::class, 'approve'])->middleware('can:approve zonal office')->name('zonal_office.approve');
        Route::put('/zonal_office/publish/{zonalOffice}', [ZonalOfficeController::class, 'publish'])->middleware('can:publish zonal office')->name('zonal_office.publish');


        // ------------------------------------
        // Tender
        // ------------------------------------
        Route::get('/tenders', [TenderController::class, 'index'])->middleware('can:view tender')->name('tenders.index');
        Route::get('/tenders/create', [TenderController::class, 'create'])->middleware('can:add tender')->name('tenders.create');
        Route::post('/tenders', [TenderController::class, 'store'])->middleware('can:add tender')->name('tenders.store');
        Route::get('/tenders/{tender}', [TenderController::class, 'show'])->middleware('can:view tender')->name('tenders.show');
        Route::get('/tenders/{tender}/edit', [TenderController::class, 'edit'])->middleware('can:edit tender')->name('tenders.edit');
        Route::put('/tenders/{tender}', [TenderController::class, 'update'])->middleware('can:edit tender')->name('tenders.update');
        Route::delete('/tenders/{tender}', [TenderController::class, 'destroy'])->middleware('can:delete tender')->name('tenders.destroy');
        Route::post('/tenders/fetch-for-datatable', [TenderController::class, 'fetchForDatatable'])->middleware('can:view tender')->name('tenders.fetch-for-datatable');
        Route::put('/tenders/approve/{tender}', [TenderController::class, 'approve'])->middleware('can:approve tender')->name('tenders.approve');
        Route::put('/tenders/publish/{tender}', [TenderController::class, 'publish'])->middleware('can:publish tender')->name('tenders.publish');
        Route::delete('/tenders/corrigendum/{tenderCorrigendum}', [TenderController::class, 'destroyCorrigendum'])->middleware('can:delete tender')->name('tenders.corrigendum.destroy');





        // ------------------------------------
        // Direction
        // ------------------------------------
        Route::get('/direction', [DirectionController::class, 'index'])->middleware('can:view direction')->name('direction.index');
        Route::get('/direction/create', [DirectionController::class, 'create'])->middleware('can:add direction')->name('direction.create');
        Route::post('/direction', [DirectionController::class, 'store'])->middleware('can:add direction')->name('direction.store');
        Route::get('/direction/{direction}', [DirectionController::class, 'show'])->middleware('can:view direction')->name('direction.show');
        Route::get('/direction/{direction}/edit', [DirectionController::class, 'edit'])->middleware('can:edit direction')->name('direction.edit');
        Route::put('/direction/{direction}', [DirectionController::class, 'update'])->middleware('can:edit direction')->name('direction.update');
        Route::delete('/direction/{direction}', [DirectionController::class, 'destroy'])->middleware('can:delete direction')->name('direction.destroy');
        Route::post('/direction/fetch-dependencies', [DirectionController::class, 'fetchDependencies'])->name('direction.fetch-dependencies');
        Route::post('/direction/fetch-for-datatable', [DirectionController::class, 'fetchForDatatable'])->middleware('can:view direction')->name('direction.fetch-for-datatable');
        Route::put('/direction/approve/{direction}', [DirectionController::class, 'approve'])->middleware('can:approve direction')->name('direction.approve');
        Route::put('/direction/publish/{direction}', [DirectionController::class, 'publish'])->middleware('can:publish direction')->name('direction.publish');

        // ------------------------------------
        // Letters Issued
        // ------------------------------------
        Route::get('/letters-issued', [LettersIssuedController::class, 'index'])->middleware('can:view letters_issued')->name('letters-issued.index');
        Route::get('/letters-issued/create', [LettersIssuedController::class, 'create'])->middleware('can:add letters_issued')->name('letters-issued.create');
        Route::post('/letters-issued', [LettersIssuedController::class, 'store'])->middleware('can:add letters_issued')->name('letters-issued.store');
        Route::get('/letters-issued/{lettersIssued}', [LettersIssuedController::class, 'show'])->middleware('can:view letters_issued')->name('letters-issued.show');
        Route::get('/letters-issued/{lettersIssued}/edit', [LettersIssuedController::class, 'edit'])->middleware('can:edit letters_issued')->name('letters-issued.edit');
        Route::put('/letters-issued/{lettersIssued}', [LettersIssuedController::class, 'update'])->middleware('can:edit letters_issued')->name('letters-issued.update');
        Route::delete('/letters-issued/{lettersIssued}', [LettersIssuedController::class, 'destroy'])->middleware('can:delete letters_issued')->name('letters-issued.destroy');
        Route::post('/letters-issued/fetch-for-datatable', [LettersIssuedController::class, 'fetchForDatatable'])->middleware('can:view letters_issued')->name('letters-issued.fetch-for-datatable');
        Route::put('/letters-issued/approve/{lettersIssued}', [LettersIssuedController::class, 'approve'])->middleware('can:approve letters_issued')->name('letters-issued.approve');
        Route::put('/letters-issued/publish/{lettersIssued}', [LettersIssuedController::class, 'publish'])->middleware('can:publish letters_issued')->name('letters-issued.publish');

        // ------------------------------------
        // Fortnightly Report
        // ------------------------------------
        Route::get('/fortnightly-reports', [FortnightlyReportController::class, 'index'])->middleware('can:view fortnightly report')->name('fortnightly-reports.index');
        Route::get('/fortnightly-reports/create', [FortnightlyReportController::class, 'create'])->middleware('can:add fortnightly report')->name('fortnightly-reports.create');
        Route::post('/fortnightly-reports', [FortnightlyReportController::class, 'store'])->middleware('can:add fortnightly report')->name('fortnightly-reports.store');
        Route::get('/fortnightly-reports/{fortnightlyReport}', [FortnightlyReportController::class, 'show'])->middleware('can:view fortnightly report')->name('fortnightly-reports.show');
        Route::get('/fortnightly-reports/{fortnightlyReport}/edit', [FortnightlyReportController::class, 'edit'])->middleware('can:edit fortnightly report')->name('fortnightly-reports.edit');
        Route::put('/fortnightly-reports/{fortnightlyReport}', [FortnightlyReportController::class, 'update'])->middleware('can:edit fortnightly report')->name('fortnightly-reports.update');
        Route::delete('/fortnightly-reports/{fortnightlyReport}', [FortnightlyReportController::class, 'destroy'])->middleware('can:delete fortnightly report')->name('fortnightly-reports.destroy');
        Route::post('/fortnightly-reports/fetch-for-datatable', [FortnightlyReportController::class, 'fetchForDatatable'])->middleware('can:view fortnightly report')->name('fortnightly-reports.fetch-for-datatable');
        Route::put('/fortnightly-reports/approve/{fortnightlyReport}', [FortnightlyReportController::class, 'approve'])->middleware('can:approve fortnightly report')->name('fortnightly-reports.approve');
        Route::put('/fortnightly-reports/publish/{fortnightlyReport}', [FortnightlyReportController::class, 'publish'])->middleware('can:publish fortnightly report')->name('fortnightly-reports.publish');

        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/change-password/{id}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        
        Route::get('/force-password-change', [ProfileController::class, 'forcePasswordChange'])->name('force-password-change');
        Route::post('/force-password-change', [ProfileController::class, 'updateForcedPassword'])->name('force-password-change.update');

        // ------------------------------------
        // Page
        // ------------------------------------
        Route::get('/pages', [PageController::class, 'index'])->middleware(['can:view page', 'check.menu.permission'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->middleware(['can:add page', 'check.menu.permission'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->middleware(['can:add page', 'check.menu.permission'])->name('pages.store');
        Route::get('/pages/{page}', [PageController::class, 'show'])->middleware(['can:view page', 'check.menu.permission'])->name('pages.show');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'destroy'])->middleware(['can:delete page', 'check.menu.permission'])->name('pages.destroy');
        Route::post('/pages/fetch-for-datatable', [PageController::class, 'fetchForDatatable'])->middleware(['can:view page', 'check.menu.permission'])->name('pages.fetch-for-datatable');
        Route::delete('/pages/files/{pageFile}', [PageController::class, 'destroyFile'])->middleware(['can:delete page', 'check.menu.permission'])->name('pages.files.destroy');
        Route::put('/pages/files/{pageFile}', [PageController::class, 'updateFile'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.files.update');
        Route::put('/pages/approve/{page}', [PageController::class, 'approve'])->middleware(['can:approve page', 'check.menu.permission'])->name('pages.approve');
        Route::put('/pages/publish/{page}', [PageController::class, 'publish'])->middleware(['can:publish page', 'check.menu.permission'])->name('pages.publish');
        Route::get('/pages/{page}/files/export', [PageController::class, 'exportFiles'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.files.export');
        Route::post('/pages/{page}/files/import', [PageController::class, 'importFiles'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.files.import');
        Route::get('/pages/files/import/template', [PageController::class, 'downloadFilesTemplate'])->middleware(['can:edit page', 'check.menu.permission'])->name('pages.files.import.template');

        // ------------------------------------
        // Page Category
        // ------------------------------------
        Route::get('/page_category', [PageCategoryController::class, 'index'])->middleware('can:view page category')->name('page_category.index');
        Route::get('/page_category/create', [PageCategoryController::class, 'create'])->middleware('can:add page category')->name('page_category.create');
        Route::post('/page_category', [PageCategoryController::class, 'store'])->middleware('can:add page category')->name('page_category.store');
        Route::get('/page_category/{pageCategory}', [PageCategoryController::class, 'show'])->middleware('can:view page category')->name('page_category.show');
        Route::get('/page_category/{pageCategory}/edit', [PageCategoryController::class, 'edit'])->middleware('can:edit page category')->name('page_category.edit');
        Route::put('/page_category/{pageCategory}', [PageCategoryController::class, 'update'])->middleware('can:edit page category')->name('page_category.update');
        Route::delete('/page_category/{pageCategory}', [PageCategoryController::class, 'destroy'])->middleware('can:delete page category')->name('page_category.destroy');
        Route::post('/page_category/fetch-for-datatable', [PageCategoryController::class, 'fetchForDatatable'])->middleware('can:view page category')->name('page_category.fetch-for-datatable');
        Route::put('/page_category/approve/{pageCategory}', [PageCategoryController::class, 'approve'])->middleware('can:approve page category')->name('page_category.approve');
        Route::put('/page_category/publish/{pageCategory}', [PageCategoryController::class, 'publish'])->middleware('can:publish page category')->name('page_category.publish');

        // ------------------------------------
        // Query Form Subject
        // ------------------------------------
        Route::get('/query_form_subject', [QueryFormSubjectController::class, 'index'])->middleware('can:view query form subject')->name('query_form_subject.index');
        Route::get('/query_form_subject/create', [QueryFormSubjectController::class, 'create'])->middleware('can:add query form subject')->name('query_form_subject.create');
        Route::post('/query_form_subject', [QueryFormSubjectController::class, 'store'])->middleware('can:add query form subject')->name('query_form_subject.store');
        Route::get('/query_form_subject/{queryFormSubject}', [QueryFormSubjectController::class, 'show'])->middleware('can:view query form subject')->name('query_form_subject.show');
        Route::get('/query_form_subject/{queryFormSubject}/edit', [QueryFormSubjectController::class, 'edit'])->middleware('can:edit query form subject')->name('query_form_subject.edit');
        Route::put('/query_form_subject/{queryFormSubject}', [QueryFormSubjectController::class, 'update'])->middleware('can:edit query form subject')->name('query_form_subject.update');
        Route::delete('/query_form_subject/{queryFormSubject}', [QueryFormSubjectController::class, 'destroy'])->middleware('can:delete query form subject')->name('query_form_subject.destroy');
        Route::post('/query_form_subject/fetch-for-datatable', [QueryFormSubjectController::class, 'fetchForDatatable'])->middleware('can:view query form subject')->name('query_form_subject.fetch-for-datatable');
        Route::put('/query_form_subject/approve/{queryFormSubject}', [QueryFormSubjectController::class, 'approve'])->middleware('can:approve query form subject')->name('query_form_subject.approve');
        Route::put('/query_form_subject/publish/{queryFormSubject}', [QueryFormSubjectController::class, 'publish'])->middleware('can:publish query form subject')->name('query_form_subject.publish');

        // ------------------------------------
        // Complaint Form Subject
        // ------------------------------------
        Route::get('/complaint_form_subject', [ComplaintFormSubjectController::class, 'index'])->middleware('can:view complaint form subject')->name('complaint_form_subject.index');
        Route::get('/complaint_form_subject/create', [ComplaintFormSubjectController::class, 'create'])->middleware('can:add complaint form subject')->name('complaint_form_subject.create');
        Route::post('/complaint_form_subject', [ComplaintFormSubjectController::class, 'store'])->middleware('can:add complaint form subject')->name('complaint_form_subject.store');
        Route::get('/complaint_form_subject/{complaintFormSubject}', [ComplaintFormSubjectController::class, 'show'])->middleware('can:view complaint form subject')->name('complaint_form_subject.show');
        Route::get('/complaint_form_subject/{complaintFormSubject}/edit', [ComplaintFormSubjectController::class, 'edit'])->middleware('can:edit complaint form subject')->name('complaint_form_subject.edit');
        Route::put('/complaint_form_subject/{complaintFormSubject}', [ComplaintFormSubjectController::class, 'update'])->middleware('can:edit complaint form subject')->name('complaint_form_subject.update');
        Route::delete('/complaint_form_subject/{complaintFormSubject}', [ComplaintFormSubjectController::class, 'destroy'])->middleware('can:delete complaint form subject')->name('complaint_form_subject.destroy');
        Route::post('/complaint_form_subject/fetch-for-datatable', [ComplaintFormSubjectController::class, 'fetchForDatatable'])->middleware('can:view complaint form subject')->name('complaint_form_subject.fetch-for-datatable');
        Route::put('/complaint_form_subject/approve/{complaintFormSubject}', [ComplaintFormSubjectController::class, 'approve'])->middleware('can:approve complaint form subject')->name('complaint_form_subject.approve');
        Route::put('/complaint_form_subject/publish/{complaintFormSubject}', [ComplaintFormSubjectController::class, 'publish'])->middleware('can:publish complaint form subject')->name('complaint_form_subject.publish');

        // ------------------------------------
        // Feedback
        // ------------------------------------
        Route::get('/feedback', [FeedbackController::class, 'index'])->middleware('can:view feedback')->name('feedback.index');
        Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->middleware('can:view feedback')->name('feedback.show');
        Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->middleware('can:edit feedback')->name('feedback.edit');
        Route::put('/feedback/{feedback}', [FeedbackController::class, 'update'])->middleware('can:edit feedback')->name('feedback.update');
        Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->middleware('can:delete feedback')->name('feedback.destroy');
        Route::post('/feedback/fetch-for-datatable', [FeedbackController::class, 'fetchForDatatable'])->middleware('can:view feedback')->name('feedback.fetch-for-datatable');
        Route::post('/feedback/{feedback}/send-revert', [FeedbackController::class, 'sendRevert'])->middleware('can:respond to feedback')->name('feedback.send-revert');

        // ------------------------------------
        // Complaint
        // ------------------------------------
        Route::get('/complaint', [ComplaintController::class, 'index'])->middleware('can:view complaint')->name('complaint.index');
        Route::get('/complaint/{complaint}', [ComplaintController::class, 'show'])->middleware('can:view complaint')->name('complaint.show');
        Route::get('/complaint/{complaint}/edit', [ComplaintController::class, 'edit'])->middleware('can:edit complaint')->name('complaint.edit');
        Route::put('/complaint/{complaint}', [ComplaintController::class, 'update'])->middleware('can:edit complaint')->name('complaint.update');
        Route::delete('/complaint/{complaint}', [ComplaintController::class, 'destroy'])->middleware('can:delete complaint')->name('complaint.destroy');
        Route::post('/complaint/fetch-for-datatable', [ComplaintController::class, 'fetchForDatatable'])->middleware('can:view complaint')->name('complaint.fetch-for-datatable');
        Route::post('/complaint/{complaint}/send-revert', [ComplaintController::class, 'sendRevert'])->middleware('can:respond to complaint')->name('complaint.send-revert');

        // ------------------------------------
        // Slider
        // ------------------------------------
        Route::get('/sliders', [SliderController::class, 'index'])->middleware('can:view slider')->name('sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->middleware('can:add slider')->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->middleware('can:add slider')->name('sliders.store');
        Route::get('/sliders/{slider}', [SliderController::class, 'show'])->middleware('can:view slider')->name('sliders.show');
        Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->middleware('can:edit slider')->name('sliders.edit');
        Route::put('/sliders/{slider}', [SliderController::class, 'update'])->middleware('can:edit slider')->name('sliders.update');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->middleware('can:delete slider')->name('sliders.destroy');
        Route::post('/sliders/fetch-for-datatable', [SliderController::class, 'fetchForDatatable'])->middleware('can:view slider')->name('sliders.fetch-for-datatable');
        Route::put('/sliders/approve/{slider}', [SliderController::class, 'approve'])->middleware('can:approve slider')->name('sliders.approve');
        Route::put('/sliders/publish/{slider}', [SliderController::class, 'publish'])->middleware('can:publish slider')->name('sliders.publish');

        // ------------------------------------
        // Announcement
        // ------------------------------------
        Route::get('/announcements', [AnnouncementController::class, 'index'])->middleware('can:view announcement')->name('announcements.index');
        Route::get('/announcements/create', [AnnouncementController::class, 'create'])->middleware('can:add announcement')->name('announcements.create');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->middleware('can:add announcement')->name('announcements.store');
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->middleware('can:view announcement')->name('announcements.show');
        Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->middleware('can:edit announcement')->name('announcements.edit');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->middleware('can:edit announcement')->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->middleware('can:delete announcement')->name('announcements.destroy');
        Route::post('/announcements/fetch-for-datatable', [AnnouncementController::class, 'fetchForDatatable'])->middleware('can:view announcement')->name('announcements.fetch-for-datatable');
        Route::put('/announcements/approve/{announcement}', [AnnouncementController::class, 'approve'])->middleware('can:approve announcement')->name('announcements.approve');
        Route::put('/announcements/publish/{announcement}', [AnnouncementController::class, 'publish'])->middleware('can:publish announcement')->name('announcements.publish');

        // ------------------------------------
        // Government Portal
        // ------------------------------------
        Route::get('/government-portals', [GovernmentPortalController::class, 'index'])->middleware('can:view government portal')->name('government-portals.index');
        Route::get('/government-portals/create', [GovernmentPortalController::class, 'create'])->middleware('can:add government portal')->name('government-portals.create');
        Route::post('/government-portals', [GovernmentPortalController::class, 'store'])->middleware('can:add government portal')->name('government-portals.store');
        Route::get('/government-portals/{governmentPortal}', [GovernmentPortalController::class, 'show'])->middleware('can:view government portal')->name('government-portals.show');
        Route::get('/government-portals/{governmentPortal}/edit', [GovernmentPortalController::class, 'edit'])->middleware('can:edit government portal')->name('government-portals.edit');
        Route::put('/government-portals/{governmentPortal}', [GovernmentPortalController::class, 'update'])->middleware('can:edit government portal')->name('government-portals.update');
        Route::delete('/government-portals/{governmentPortal}', [GovernmentPortalController::class, 'destroy'])->middleware('can:delete government portal')->name('government-portals.destroy');
        Route::post('/government-portals/fetch-for-datatable', [GovernmentPortalController::class, 'fetchForDatatable'])->middleware('can:view government portal')->name('government-portals.fetch-for-datatable');
        Route::put('/government-portals/approve/{governmentPortal}', [GovernmentPortalController::class, 'approve'])->middleware('can:approve government portal')->name('government-portals.approve');
        Route::put('/government-portals/publish/{governmentPortal}', [GovernmentPortalController::class, 'publish'])->middleware('can:publish government portal')->name('government-portals.publish');

        // ------------------------------------
        // Quick Links
        // ------------------------------------
        Route::get('/quick-links', [QuickLinkController::class, 'index'])->middleware('can:view quick link')->name('quick-links.index');
        Route::get('/quick-links/create', [QuickLinkController::class, 'create'])->middleware('can:add quick link')->name('quick-links.create');
        Route::post('/quick-links', [QuickLinkController::class, 'store'])->middleware('can:add quick link')->name('quick-links.store');
        Route::get('/quick-links/{quickLink}', [QuickLinkController::class, 'show'])->middleware('can:view quick link')->name('quick-links.show');
        Route::get('/quick-links/{quickLink}/edit', [QuickLinkController::class, 'edit'])->middleware('can:edit quick link')->name('quick-links.edit');
        Route::put('/quick-links/{quickLink}', [QuickLinkController::class, 'update'])->middleware('can:edit quick link')->name('quick-links.update');
        Route::delete('/quick-links/{quickLink}', [QuickLinkController::class, 'destroy'])->middleware('can:delete quick link')->name('quick-links.destroy');
        Route::post('/quick-links/fetch-for-datatable', [QuickLinkController::class, 'fetchForDatatable'])->middleware('can:view quick link')->name('quick-links.fetch-for-datatable');
        Route::put('/quick-links/approve/{quickLink}', [QuickLinkController::class, 'approve'])->middleware('can:approve quick link')->name('quick-links.approve');
        Route::put('/quick-links/publish/{quickLink}', [QuickLinkController::class, 'publish'])->middleware('can:publish quick link')->name('quick-links.publish');

        // ------------------------------------
        // Circulars
        // ------------------------------------
        Route::get('/circulars', [CircularController::class, 'index'])->middleware('can:view circular')->name('circulars.index');
        Route::get('/circulars/create', [CircularController::class, 'create'])->middleware('can:add circular')->name('circulars.create');
        Route::post('/circulars', [CircularController::class, 'store'])->middleware('can:add circular')->name('circulars.store');
        Route::get('/circulars/{circular}', [CircularController::class, 'show'])->middleware('can:view circular')->name('circulars.show');
        Route::get('/circulars/{circular}/edit', [CircularController::class, 'edit'])->middleware('can:edit circular')->name('circulars.edit');
        Route::put('/circulars/{circular}', [CircularController::class, 'update'])->middleware('can:edit circular')->name('circulars.update');
        Route::delete('/circulars/{circular}', [CircularController::class, 'destroy'])->middleware('can:delete circular')->name('circulars.destroy');
        Route::post('/circulars/fetch-for-datatable', [CircularController::class, 'fetchForDatatable'])->middleware('can:view circular')->name('circulars.fetch-for-datatable');
        Route::post('/circulars/fetch-for-datatable-backend', [CircularController::class, 'fetchForDatatableBackend'])->middleware('can:view circular')->name('circulars.fetch-for-datatable-backend');
        Route::put('/circulars/approve/{circular}', [CircularController::class, 'approve'])->middleware('can:approve circular')->name('circulars.approve');
        Route::put('/circulars/publish/{circular}', [CircularController::class, 'publish'])->middleware('can:publish circular')->name('circulars.publish');



        // EMPLOYEE CIRCUALR SECTION
        Route::get('circulars/{category}', [CircularController::class, 'employeeIndex'])->name('circulars.employee-index');


        // ------------------------------------
        // Events
        // ------------------------------------
        Route::get('/events', [EventController::class, 'index'])->middleware('can:view event')->name('events.index');
        Route::get('/events/create', [EventController::class, 'create'])->middleware('can:add event')->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->middleware('can:add event')->name('events.store');
        Route::get('/events/{event}', [EventController::class, 'show'])->middleware('can:view event')->name('events.show');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->middleware('can:edit event')->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->middleware('can:edit event')->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->middleware('can:delete event')->name('events.destroy');
        Route::post('/events/fetch-for-datatable', [EventController::class, 'fetchForDatatable'])->middleware('can:view event')->name('events.fetch-for-datatable');
        Route::put('/events/approve/{event}', [EventController::class, 'approve'])->middleware('can:approve event')->name('events.approve');
        Route::put('/events/publish/{event}', [EventController::class, 'publish'])->middleware('can:publish event')->name('events.publish');



        // ------------------------------------
        // Gallery Event
        // ------------------------------------
        Route::get('/gallery-events', [GalleryEventController::class, 'index'])->middleware('can:view gallery event')->name('gallery-event.index');
        Route::get('/gallery-events/create', [GalleryEventController::class, 'create'])->middleware('can:add gallery event')->name('gallery-event.create');
        Route::post('/gallery-events', [GalleryEventController::class, 'store'])->middleware('can:add gallery event')->name('gallery-event.store');
        Route::get('/gallery-events/{galleryEvent}', [GalleryEventController::class, 'show'])->middleware('can:view gallery event')->name('gallery-event.show');
        Route::get('/gallery-events/{galleryEvent}/edit', [GalleryEventController::class, 'edit'])->middleware('can:edit gallery event')->name('gallery-event.edit');
        Route::put('/gallery-events/{galleryEvent}', [GalleryEventController::class, 'update'])->middleware('can:edit gallery event')->name('gallery-event.update');
        Route::delete('/gallery-events/{galleryEvent}', [GalleryEventController::class, 'destroy'])->middleware('can:delete gallery event')->name('gallery-event.destroy');
        Route::post('/gallery-events/fetch-for-datatable', [GalleryEventController::class, 'fetchForDatatable'])->middleware('can:view gallery event')->name('gallery-event.fetch-for-datatable');
        Route::put('/gallery-events/approve/{galleryEvent}', [GalleryEventController::class, 'approve'])->middleware('can:approve gallery event')->name('gallery-event.approve');
        Route::put('/gallery-events/publish/{galleryEvent}', [GalleryEventController::class, 'publish'])->middleware('can:publish gallery event')->name('gallery-event.publish');

        // ------------------------------------
        // Photo Gallery
        // ------------------------------------
        Route::get('/photo-gallery', [PhotoGalleryController::class, 'index'])->middleware('can:view photo gallery')->name('photo-gallery.index');
        Route::get('/photo-gallery/create', [PhotoGalleryController::class, 'create'])->middleware('can:add photo gallery')->name('photo-gallery.create');
        Route::post('/photo-gallery', [PhotoGalleryController::class, 'store'])->middleware('can:add photo gallery')->name('photo-gallery.store');
        Route::get('/photo-gallery/{photoGallery}', [PhotoGalleryController::class, 'show'])->middleware('can:view photo gallery')->name('photo-gallery.show');
        Route::get('/photo-gallery/{photoGallery}/edit', [PhotoGalleryController::class, 'edit'])->middleware('can:edit photo gallery')->name('photo-gallery.edit');
        Route::put('/photo-gallery/{photoGallery}', [PhotoGalleryController::class, 'update'])->middleware('can:edit photo gallery')->name('photo-gallery.update');
        Route::delete('/photo-gallery/{photoGallery}', [PhotoGalleryController::class, 'destroy'])->middleware('can:delete photo gallery')->name('photo-gallery.destroy');
        Route::delete('/photo-gallery/files/{photoGalleryFile}', [PhotoGalleryController::class, 'destroyFile'])->middleware('can:delete photo gallery')->name('photo-gallery.files.destroy');
        Route::post('/photo-gallery/fetch-for-datatable', [PhotoGalleryController::class, 'fetchForDatatable'])->middleware('can:view photo gallery')->name('photo-gallery.fetch-for-datatable');
        Route::put('/photo-gallery/approve/{photoGallery}', [PhotoGalleryController::class, 'approve'])->middleware('can:approve photo gallery')->name('photo-gallery.approve');
        Route::put('/photo-gallery/publish/{photoGallery}', [PhotoGalleryController::class, 'publish'])->middleware('can:publish photo gallery')->name('photo-gallery.publish');

        // ------------------------------------
        // Video Gallery
        // ------------------------------------
        Route::get('/video-gallery', [VideoGalleryController::class, 'index'])->middleware('can:view video gallery')->name('video-gallery.index');
        Route::get('/video-gallery/create', [VideoGalleryController::class, 'create'])->middleware('can:add video gallery')->name('video-gallery.create');
        Route::post('/video-gallery', [VideoGalleryController::class, 'store'])->middleware('can:add video gallery')->name('video-gallery.store');
        Route::get('/video-gallery/{videoGallery}', [VideoGalleryController::class, 'show'])->middleware('can:view video gallery')->name('video-gallery.show');
        Route::get('/video-gallery/{videoGallery}/edit', [VideoGalleryController::class, 'edit'])->middleware('can:edit video gallery')->name('video-gallery.edit');
        Route::put('/video-gallery/{videoGallery}', [VideoGalleryController::class, 'update'])->middleware('can:edit video gallery')->name('video-gallery.update');
        Route::delete('/video-gallery/{videoGallery}', [VideoGalleryController::class, 'destroy'])->middleware('can:delete video gallery')->name('video-gallery.destroy');
        Route::delete('/video-gallery/files/{videoGalleryFile}', [VideoGalleryController::class, 'destroyFile'])->middleware('can:delete video gallery')->name('video-gallery.files.destroy');
        Route::post('/video-gallery/fetch-for-datatable', [VideoGalleryController::class, 'fetchForDatatable'])->middleware('can:view video gallery')->name('video-gallery.fetch-for-datatable');
        Route::put('/video-gallery/approve/{videoGallery}', [VideoGalleryController::class, 'approve'])->middleware('can:approve video gallery')->name('video-gallery.approve');
        Route::put('/video-gallery/publish/{videoGallery}', [VideoGalleryController::class, 'publish'])->middleware('can:publish video gallery')->name('video-gallery.publish');

        // ------------------------------------
        // Contact Details
        // ------------------------------------
        Route::get('/contact-details', [ContactDetailController::class, 'index'])->middleware('can:view contact detail')->name('contact-details.index');
        Route::get('/contact-details/create', [ContactDetailController::class, 'create'])->middleware('can:add contact detail')->name('contact-details.create');
        Route::post('/contact-details', [ContactDetailController::class, 'store'])->middleware('can:add contact detail')->name('contact-details.store');
        Route::get('/contact-details/{contactDetail}', [ContactDetailController::class, 'show'])->middleware('can:view contact detail')->name('contact-details.show');
        Route::get('/contact-details/{contactDetail}/edit', [ContactDetailController::class, 'edit'])->middleware('can:edit contact detail')->name('contact-details.edit');
        Route::put('/contact-details/{contactDetail}', [ContactDetailController::class, 'update'])->middleware('can:edit contact detail')->name('contact-details.update');
        Route::delete('/contact-details/{contactDetail}', [ContactDetailController::class, 'destroy'])->middleware('can:delete contact detail')->name('contact-details.destroy');
        Route::post('/contact-details/fetch-for-datatable', [ContactDetailController::class, 'fetchForDatatable'])->middleware('can:view contact detail')->name('contact-details.fetch-for-datatable');
        Route::put('/contact-details/approve/{contactDetail}', [ContactDetailController::class, 'approve'])->middleware('can:approve contact detail')->name('contact-details.approve');
        Route::put('/contact-details/publish/{contactDetail}', [ContactDetailController::class, 'publish'])->middleware('can:publish contact detail')->name('contact-details.publish');

        // ------------------------------------
        // Social Media
        // ------------------------------------
        Route::get('/social-medias', [SocialMediaController::class, 'index'])->middleware('can:view social media')->name('social-medias.index');
        Route::get('/social-medias/create', [SocialMediaController::class, 'create'])->middleware('can:add social media')->name('social-medias.create');
        Route::post('/social-medias', [SocialMediaController::class, 'store'])->middleware('can:add social media')->name('social-medias.store');
        Route::get('/social-medias/{socialMedia}', [SocialMediaController::class, 'show'])->middleware('can:view social media')->name('social-medias.show');
        Route::get('/social-medias/{socialMedia}/edit', [SocialMediaController::class, 'edit'])->middleware('can:edit social media')->name('social-medias.edit');
        Route::put('/social-medias/{socialMedia}', [SocialMediaController::class, 'update'])->middleware('can:edit social media')->name('social-medias.update');
        Route::delete('/social-medias/{socialMedia}', [SocialMediaController::class, 'destroy'])->middleware('can:delete social media')->name('social-medias.destroy');
        Route::post('/social-medias/fetch-for-datatable', [SocialMediaController::class, 'fetchForDatatable'])->middleware('can:view social media')->name('social-medias.fetch-for-datatable');
        Route::put('/social-medias/approve/{socialMedia}', [SocialMediaController::class, 'approve'])->middleware('can:approve social media')->name('social-medias.approve');
        Route::put('/social-medias/publish/{socialMedia}', [SocialMediaController::class, 'publish'])->middleware('can:publish social media')->name('social-medias.publish');

        // ------------------------------------
        // Additional Logos
        // ------------------------------------
        Route::get('/additional-logos', [AdditionalLogoController::class, 'index'])->middleware('can:view additional logo')->name('additional-logos.index');
        Route::get('/additional-logos/create', [AdditionalLogoController::class, 'create'])->middleware('can:add additional logo')->name('additional-logos.create');
        Route::post('/additional-logos', [AdditionalLogoController::class, 'store'])->middleware('can:add additional logo')->name('additional-logos.store');
        Route::get('/additional-logos/{additionalLogo}', [AdditionalLogoController::class, 'show'])->middleware('can:view additional logo')->name('additional-logos.show');
        Route::get('/additional-logos/{additionalLogo}/edit', [AdditionalLogoController::class, 'edit'])->middleware('can:edit additional logo')->name('additional-logos.edit');
        Route::put('/additional-logos/{additionalLogo}', [AdditionalLogoController::class, 'update'])->middleware('can:edit additional logo')->name('additional-logos.update');
        Route::delete('/additional-logos/{additionalLogo}', [AdditionalLogoController::class, 'destroy'])->middleware('can:delete additional logo')->name('additional-logos.destroy');
        Route::post('/additional-logos/fetch-for-datatable', [AdditionalLogoController::class, 'fetchForDatatable'])->middleware('can:view additional logo')->name('additional-logos.fetch-for-datatable');
        Route::put('/additional-logos/approve/{additionalLogo}', [AdditionalLogoController::class, 'approve'])->middleware('can:approve additional logo')->name('additional-logos.approve');
        Route::put('/additional-logos/publish/{additionalLogo}', [AdditionalLogoController::class, 'publish'])->middleware('can:publish additional logo')->name('additional-logos.publish');

        // ------------------------------------
        // Additional Logos
        // ------------------------------------
        Route::get('/who-is-who', [WhoIsWhoController::class, 'index'])->middleware('can:view who is who')->name('who-is-who.index');
        Route::get('/who-is-who/create', [WhoIsWhoController::class, 'create'])->middleware('can:add who is who')->name('who-is-who.create');
        Route::post('/who-is-who', [WhoIsWhoController::class, 'store'])->middleware('can:add who is who')->name('who-is-who.store');
        Route::get('/who-is-who/{whoIsWho}', [WhoIsWhoController::class, 'show'])->middleware('can:view who is who')->name('who-is-who.show');
        Route::get('/who-is-who/{whoIsWho}/edit', [WhoIsWhoController::class, 'edit'])->middleware('can:edit who is who')->name('who-is-who.edit');
        Route::put('/who-is-who/{whoIsWho}', [WhoIsWhoController::class, 'update'])->middleware('can:edit who is who')->name('who-is-who.update');
        Route::delete('/who-is-who/{whoIsWho}', [WhoIsWhoController::class, 'destroy'])->middleware('can:delete who is who')->name('who-is-who.destroy');
        Route::post('/who-is-who/fetch-for-datatable', [WhoIsWhoController::class, 'fetchForDatatable'])->middleware('can:view who is who')->name('who-is-who.fetch-for-datatable');
        Route::put('/who-is-who/approve/{whoIsWho}', [WhoIsWhoController::class, 'approve'])->middleware('can:approve who is who')->name('who-is-who.approve');
        Route::put('/who-is-who/publish/{whoIsWho}', [WhoIsWhoController::class, 'publish'])->middleware('can:publish who is who')->name('who-is-who.publish');

        // ------------------------------------
        // Media Library
        // ------------------------------------
        Route::get('/medias', [MediaController::class, 'index'])->middleware('can:view media')->name('medias.index');
        Route::get('/medias/create', [MediaController::class, 'create'])->middleware('can:add media')->name('medias.create');
        Route::post('/medias', [MediaController::class, 'store'])->middleware('can:add media')->name('medias.store');
        Route::get('/medias/{mediaLibrary}', [MediaController::class, 'show'])->middleware('can:view media')->name('medias.show');
        Route::get('/medias/{mediaLibrary}/edit', [MediaController::class, 'edit'])->middleware('can:edit media')->name('medias.edit');
        Route::put('/medias/{mediaLibrary}', [MediaController::class, 'update'])->middleware('can:edit media')->name('medias.update');
        Route::delete('/medias/{mediaLibrary}', [MediaController::class, 'destroy'])->middleware('can:delete media')->name('medias.destroy');
        Route::post('/medias/fetch-for-datatable', [MediaController::class, 'fetchForDatatable'])->middleware('can:view media')->name('medias.fetch-for-datatable');
        Route::put('/medias/approve/{mediaLibrary}', [MediaController::class, 'approve'])->middleware('can:approve media')->name('medias.approve');
        Route::put('/medias/publish/{mediaLibrary}', [MediaController::class, 'publish'])->middleware('can:publish media')->name('medias.publish');

        // ------------------------------------
        // Home About
        // ------------------------------------
        Route::get('/home-about', [HomeAboutController::class, 'index'])->middleware('can:view homepage about')->name('home-about.index');
        Route::get('/home-about/{homeAbout}/edit', [HomeAboutController::class, 'edit'])->middleware('can:edit homepage about')->name('home-about.edit');
        Route::put('/home-about/{homeAbout}', [HomeAboutController::class, 'update'])->middleware('can:edit homepage about')->name('home-about.update');
        // Route::delete('/home-about/{homeAbout}', [HomeAboutController::class, 'destroy'])->middleware('can:delete homepage about')->name('home-about.destroy');

        // ------------------------------------
        // Authentication Log
        // ------------------------------------
        Route::get('/authentication-logs', [AuthenticationLogController::class, 'index'])->middleware('can:view authentication log')->name('authentication-logs.index');
        Route::post('/authentication-logs/fetch-for-datatable', [AuthenticationLogController::class, 'fetchForDatatable'])->middleware('can:view authentication log')->name('authentication-logs.fetch-for-datatable');

        // ------------------------------------
        // Jobs
        // ------------------------------------
        Route::get('/jobs', [JobController::class, 'index'])->middleware('can:view job')->name('jobs.index');
        Route::get('/jobs/create', [JobController::class, 'create'])->middleware('can:add job')->name('jobs.create');
        Route::post('/jobs', [JobController::class, 'store'])->middleware('can:add job')->name('jobs.store');
        Route::get('/jobs/{job}', [JobController::class, 'show'])->middleware('can:view job')->name('jobs.show');
        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])->middleware('can:edit job')->name('jobs.edit');
        Route::put('/jobs/{job}', [JobController::class, 'update'])->middleware('can:edit job')->name('jobs.update');
        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])->middleware('can:delete job')->name('jobs.destroy');
        Route::post('/jobs/fetch-for-datatable', [JobController::class, 'fetchForDatatable'])->middleware('can:view job')->name('jobs.fetch-for-datatable');
        Route::put('/jobs/approve/{job}', [JobController::class, 'approve'])->middleware('can:approve job')->name('jobs.approve');
        Route::put('/jobs/publish/{job}', [JobController::class, 'publish'])->middleware('can:publish job')->name('jobs.publish');


        // ------------------------------------
        // Job Posts
        // ------------------------------------
        Route::get('/job-posts', [JobPostController::class, 'index'])->middleware('can:view job post')->name('job-posts.index');
        Route::get('/job-posts/create', [JobPostController::class, 'create'])->middleware('can:add job post')->name('job-posts.create');
        Route::post('/job-posts', [JobPostController::class, 'store'])->middleware('can:add job post')->name('job-posts.store');
        Route::get('/job-posts/{job_post}', [JobPostController::class, 'show'])->middleware('can:view job post')->name('job-posts.show');
        Route::get('/job-posts/{job_post}/edit', [JobPostController::class, 'edit'])->middleware('can:edit job post')->name('job-posts.edit');
        Route::put('/job-posts/{job_post}', [JobPostController::class, 'update'])->middleware('can:edit job post')->name('job-posts.update');
        Route::delete('/job-posts/{job_post}', [JobPostController::class, 'destroy'])->middleware('can:delete job post')->name('job-posts.destroy');
        Route::post('/job-posts/fetch-for-datatable', [JobPostController::class, 'fetchForDatatable'])->middleware('can:view job post')->name('job-posts.fetch-for-datatable');
        Route::put('/job-posts/approve/{job_post}', [JobPostController::class, 'approve'])->middleware('can:approve job post')->name('job-posts.approve');
        Route::put('/job-posts/publish/{job_post}', [JobPostController::class, 'publish'])->middleware('can:publish job post')->name('job-posts.publish');

        // ------------------------------------
        // Recruitment Announcements
        // ------------------------------------
        Route::get('/recruitment-announcements', [RecruitmentAnnouncementController::class, 'index'])->middleware('can:view recruitment announcement')->name('recruitment-announcements.index');
        Route::get('/recruitment-announcements/create', [RecruitmentAnnouncementController::class, 'create'])->middleware('can:add recruitment announcement')->name('recruitment-announcements.create');
        Route::post('/recruitment-announcements', [RecruitmentAnnouncementController::class, 'store'])->middleware('can:add recruitment announcement')->name('recruitment-announcements.store');
        Route::get('/recruitment-announcements/{recruitment_announcement}', [RecruitmentAnnouncementController::class, 'show'])->middleware('can:view recruitment announcement')->name('recruitment-announcements.show');
        Route::get('/recruitment-announcements/{recruitment_announcement}/edit', [RecruitmentAnnouncementController::class, 'edit'])->middleware('can:edit recruitment announcement')->name('recruitment-announcements.edit');
        Route::put('/recruitment-announcements/{recruitment_announcement}', [RecruitmentAnnouncementController::class, 'update'])->middleware('can:edit recruitment announcement')->name('recruitment-announcements.update');
        Route::delete('/recruitment-announcements/{recruitment_announcement}', [RecruitmentAnnouncementController::class, 'destroy'])->middleware('can:delete recruitment announcement')->name('recruitment-announcements.destroy');
        Route::put('/recruitment-announcements/approve/{recruitment_announcement}', [RecruitmentAnnouncementController::class, 'approve'])->middleware('can:approve recruitment announcement')->name('recruitment-announcements.approve');
        Route::put('/recruitment-announcements/publish/{recruitment_announcement}', [RecruitmentAnnouncementController::class, 'publish'])->middleware('can:publish recruitment announcement')->name('recruitment-announcements.publish');
        Route::post('/recruitment-announcements/fetch-for-datatable', [RecruitmentAnnouncementController::class, 'fetchForDatatable'])->middleware('can:view recruitment announcement')->name('recruitment-announcements.fetch-for-datatable');
        Route::post('/recruitment-announcements/get-posts', [RecruitmentAnnouncementController::class, 'getJobPostsByJob'])->name('recruitment-announcements.get-posts');
        
        Route::get('/recruitment-announcements/export/excel', [RecruitmentAnnouncementController::class, 'export'])->middleware('can:view recruitment announcement')->name('recruitment-announcements.export');
        Route::get('/recruitment-announcements/import/format', [RecruitmentAnnouncementController::class, 'downloadFormat'])->middleware('can:add recruitment announcement')->name('recruitment-announcements.import.format');
        Route::post('/recruitment-announcements/import/excel', [RecruitmentAnnouncementController::class, 'import'])->middleware('can:add recruitment announcement')->name('recruitment-announcements.import');



        // ------------------------------------
        // Division
        // ------------------------------------
        Route::get('/division', [DivisionController::class, 'index'])->middleware('can:view division')->name('division.index');
        Route::get('/division/import/format', [DivisionController::class, 'downloadFormat'])->middleware('can:add division')->name('division.import.format');
        Route::post('/division/import', [DivisionController::class, 'import'])->middleware('can:add division')->name('division.import');
        Route::get('/division/create', [DivisionController::class, 'create'])->middleware('can:add division')->name('division.create');
        Route::post('/division', [DivisionController::class, 'store'])->middleware('can:add division')->name('division.store');
        Route::get('/division/{division}', [DivisionController::class, 'show'])->middleware('can:view division')->name('division.show');
        Route::get('/division/{division}/edit', [DivisionController::class, 'edit'])->middleware('can:edit division')->name('division.edit');
        Route::put('/division/{division}', [DivisionController::class, 'update'])->middleware('can:edit division')->name('division.update');
        Route::delete('/division/{division}', [DivisionController::class, 'destroy'])->middleware('can:delete division')->name('division.destroy');
        Route::post('/division/fetch-for-datatable', [DivisionController::class, 'fetchForDatatable'])->middleware('can:view division')->name('division.fetch-for-datatable');
        Route::put('/division/approve/{division}', [DivisionController::class, 'approve'])->middleware('can:approve division')->name('division.approve');
        Route::put('/division/publish/{division}', [DivisionController::class, 'publish'])->middleware('can:publish division')->name('division.publish');

        // ------------------------------------
        // Designation
        // ------------------------------------
        Route::get('/designation', [DesignationController::class, 'index'])->middleware('can:view designation')->name('designation.index');
        Route::get('/designation/create', [DesignationController::class, 'create'])->middleware('can:add designation')->name('designation.create');
        Route::post('/designation', [DesignationController::class, 'store'])->middleware('can:add designation')->name('designation.store');
        Route::get('/designation/{designation}', [DesignationController::class, 'show'])->middleware('can:view designation')->name('designation.show');
        Route::get('/designation/{designation}/edit', [DesignationController::class, 'edit'])->middleware('can:edit designation')->name('designation.edit');
        Route::put('/designation/{designation}', [DesignationController::class, 'update'])->middleware('can:edit designation')->name('designation.update');
        Route::delete('/designation/{designation}', [DesignationController::class, 'destroy'])->middleware('can:delete designation')->name('designation.destroy');
        Route::post('/designation/fetch-for-datatable', [DesignationController::class, 'fetchForDatatable'])->middleware('can:view designation')->name('designation.fetch-for-datatable');
        Route::put('/designation/approve/{designation}', [DesignationController::class, 'approve'])->middleware('can:approve designation')->name('designation.approve');
        Route::put('/designation/publish/{designation}', [DesignationController::class, 'publish'])->middleware('can:publish designation')->name('designation.publish');

        // ------------------------------------
        // Subject Area
        // ------------------------------------
        Route::get('/subject-area', [SubjectAreaController::class, 'index'])->middleware('can:view subject area')->name('subject-area.index');
        Route::get('/subject-area/create', [SubjectAreaController::class, 'create'])->middleware('can:add subject area')->name('subject-area.create');
        Route::post('/subject-area', [SubjectAreaController::class, 'store'])->middleware('can:add subject area')->name('subject-area.store');
        Route::get('/subject-area/{subjectArea}', [SubjectAreaController::class, 'show'])->middleware('can:view subject area')->name('subject-area.show');
        Route::get('/subject-area/{subjectArea}/edit', [SubjectAreaController::class, 'edit'])->middleware('can:edit subject area')->name('subject-area.edit');
        Route::put('/subject-area/{subjectArea}', [SubjectAreaController::class, 'update'])->middleware('can:edit subject area')->name('subject-area.update');
        Route::delete('/subject-area/{subjectArea}', [SubjectAreaController::class, 'destroy'])->middleware('can:delete subject area')->name('subject-area.destroy');
        Route::post('/subject-area/fetch-for-datatable', [SubjectAreaController::class, 'fetchForDatatable'])->middleware('can:view subject area')->name('subject-area.fetch-for-datatable');
        Route::put('/subject-area/approve/{subjectArea}', [SubjectAreaController::class, 'approve'])->middleware('can:approve subject area')->name('subject-area.approve');
        Route::put('/subject-area/publish/{subjectArea}', [SubjectAreaController::class, 'publish'])->middleware('can:publish subject area')->name('subject-area.publish');


        // ------------------------------------
        // Technical Report
        // ------------------------------------
        Route::get('/technical_report', [TechnicalReportController::class, 'index'])->middleware('can:view technical report')->name('technical_report.index');
        Route::get('/technical_report/create', [TechnicalReportController::class, 'create'])->middleware('can:add technical report')->name('technical_report.create');
        Route::post('/technical_report', [TechnicalReportController::class, 'store'])->middleware('can:add technical report')->name('technical_report.store');
        Route::get('/technical_report/{technicalReport}', [TechnicalReportController::class, 'show'])->middleware('can:view technical report')->name('technical_report.show');
        Route::get('/technical_report/{technicalReport}/edit', [TechnicalReportController::class, 'edit'])->middleware('can:edit technical report')->name('technical_report.edit');
        Route::put('/technical_report/{technicalReport}', [TechnicalReportController::class, 'update'])->middleware('can:edit technical report')->name('technical_report.update');
        Route::delete('/technical_report/{technicalReport}', [TechnicalReportController::class, 'destroy'])->middleware('can:delete technical report')->name('technical_report.destroy');
        Route::post('/technical_report/fetch-for-datatable', [TechnicalReportController::class, 'fetchForDatatable'])->middleware('can:view technical report')->name('technical_report.fetch-for-datatable');
        Route::put('/technical_report/approve/{technicalReport}', [TechnicalReportController::class, 'approve'])->middleware('can:approve technical report')->name('technical_report.approve');
        Route::put('/technical_report/publish/{technicalReport}', [TechnicalReportController::class, 'publish'])->middleware('can:publish technical report')->name('technical_report.publish');

        // ------------------------------------
        // Annual Report
        // ------------------------------------
        Route::get('/annual_report', [AnnualReportController::class, 'index'])->middleware('can:view annual report')->name('annual_report.index');
        Route::get('/annual_report/create', [AnnualReportController::class, 'create'])->middleware('can:add annual report')->name('annual_report.create');
        Route::post('/annual_report', [AnnualReportController::class, 'store'])->middleware('can:add annual report')->name('annual_report.store');
        Route::get('/annual_report/{annualReport}', [AnnualReportController::class, 'show'])->middleware('can:view annual report')->name('annual_report.show');
        Route::get('/annual_report/{annualReport}/edit', [AnnualReportController::class, 'edit'])->middleware('can:edit annual report')->name('annual_report.edit');
        Route::put('/annual_report/{annualReport}', [AnnualReportController::class, 'update'])->middleware('can:edit annual report')->name('annual_report.update');
        Route::delete('/annual_report/{annualReport}', [AnnualReportController::class, 'destroy'])->middleware('can:delete annual report')->name('annual_report.destroy');
        Route::post('/annual_report/fetch-for-datatable', [AnnualReportController::class, 'fetchForDatatable'])->middleware('can:view annual report')->name('annual_report.fetch-for-datatable');
        Route::put('/annual_report/approve/{annualReport}', [AnnualReportController::class, 'approve'])->middleware('can:approve annual report')->name('annual_report.approve');
        Route::put('/annual_report/publish/{annualReport}', [AnnualReportController::class, 'publish'])->middleware('can:publish annual report')->name('annual_report.publish');

        // ------------------------------------
        // Publication Category
        // ------------------------------------
        Route::get('/publication_category', [PublicationCategoryController::class, 'index'])->middleware('can:view publication category')->name('publication_category.index');
        Route::get('/publication_category/create', [PublicationCategoryController::class, 'create'])->middleware('can:add publication category')->name('publication_category.create');
        Route::post('/publication_category', [PublicationCategoryController::class, 'store'])->middleware('can:add publication category')->name('publication_category.store');
        Route::get('/publication_category/{publicationCategory}', [PublicationCategoryController::class, 'show'])->middleware('can:view publication category')->name('publication_category.show');
        Route::get('/publication_category/{publicationCategory}/edit', [PublicationCategoryController::class, 'edit'])->middleware('can:edit publication category')->name('publication_category.edit');
        Route::put('/publication_category/{publicationCategory}', [PublicationCategoryController::class, 'update'])->middleware('can:edit publication category')->name('publication_category.update');
        Route::delete('/publication_category/{publicationCategory}', [PublicationCategoryController::class, 'destroy'])->middleware('can:delete publication category')->name('publication_category.destroy');
        Route::post('/publication_category/fetch-for-datatable', [PublicationCategoryController::class, 'fetchForDatatable'])->middleware('can:view publication category')->name('publication_category.fetch-for-datatable');
        Route::put('/publication_category/approve/{publicationCategory}', [PublicationCategoryController::class, 'approve'])->middleware('can:approve publication category')->name('publication_category.approve');
        Route::put('/publication_category/publish/{publicationCategory}', [PublicationCategoryController::class, 'publish'])->middleware('can:publish publication category')->name('publication_category.publish');

        // ------------------------------------
        // Publication
        // ------------------------------------
        Route::get('/publication', [PublicationController::class, 'index'])->middleware('can:view publication')->name('publication.index');
        Route::get('/publication/create', [PublicationController::class, 'create'])->middleware('can:add publication')->name('publication.create');
        Route::post('/publication', [PublicationController::class, 'store'])->middleware('can:add publication')->name('publication.store');
        Route::get('/publication/{publication}', [PublicationController::class, 'show'])->middleware('can:view publication')->name('publication.show');
        Route::get('/publication/{publication}/edit', [PublicationController::class, 'edit'])->middleware('can:edit publication')->name('publication.edit');
        Route::put('/publication/{publication}', [PublicationController::class, 'update'])->middleware('can:edit publication')->name('publication.update');
        Route::delete('/publication/{publication}', [PublicationController::class, 'destroy'])->middleware('can:delete publication')->name('publication.destroy');
        Route::post('/publication/fetch-for-datatable', [PublicationController::class, 'fetchForDatatable'])->middleware('can:view publication')->name('publication.fetch-for-datatable');
        Route::post('/publication/fetch-publication-category', [PublicationController::class, 'fetchPublicationCategory'])->middleware('can:view publication category')->name('publication.fetch-publication-category');
        Route::put('/publication/approve/{publication}', [PublicationController::class, 'approve'])->middleware('can:approve publication')->name('publication.approve');
        Route::put('/publication/publish/{publication}', [PublicationController::class, 'publish'])->middleware('can:publish publication')->name('publication.publish');

        // ------------------------------------
        // Regional Directory
        // ------------------------------------
        Route::get('/regional_directory', [RegionalDirectoriesController::class, 'index'])->middleware('can:view regional directory')->name('regional_directories.index');
        Route::get('/regional_directory/create', [RegionalDirectoriesController::class, 'create'])->middleware('can:add regional directory')->name('regional_directories.create');
        Route::post('/regional_directory', [RegionalDirectoriesController::class, 'store'])->middleware('can:add regional directory')->name('regional_directories.store');
        Route::get('/regional_directory/{regionalDirectories}', [RegionalDirectoriesController::class, 'show'])->middleware('can:view regional directory')->name('regional_directories.show');
        Route::get('/regional_directory/{regionalDirectories}/edit', [RegionalDirectoriesController::class, 'edit'])->middleware('can:edit regional directory')->name('regional_directories.edit');
        Route::put('/regional_directory/{regionalDirectories}', [RegionalDirectoriesController::class, 'update'])->middleware('can:edit regional directory')->name('regional_directories.update');
        Route::delete('/regional_directory/{regionalDirectories}', [RegionalDirectoriesController::class, 'destroy'])->middleware('can:delete regional directory')->name('regional_directories.destroy');
        Route::post('/regional_directory/fetch-for-datatable', [RegionalDirectoriesController::class, 'fetchForDatatable'])->middleware('can:view regional directory')->name('regional_directories.fetch-for-datatable');
        Route::put('/regional_directory/approve/{regionalDirectories}', [RegionalDirectoriesController::class, 'approve'])->middleware('can:approve regional directory')->name('regional_directories.approve');
        Route::put('/regional_directory/publish/{regionalDirectories}', [RegionalDirectoriesController::class, 'publish'])->middleware('can:publish regional directory')->name('regional_directories.publish');
        // FAQ
        // ------------------------------------
        Route::get('/faq', [FaqController::class, 'index'])->middleware('can:view faq')->name('faq.index');
        Route::get('/faq/create', [FaqController::class, 'create'])->middleware('can:add faq')->name('faq.create');
        Route::post('/faq', [FaqController::class, 'store'])->middleware('can:add faq')->name('faq.store');
        Route::get('/faq/{faq}', [FaqController::class, 'show'])->middleware('can:view faq')->name('faq.show');
        Route::get('/faq/{faq}/edit', [FaqController::class, 'edit'])->middleware('can:edit faq')->name('faq.edit');
        Route::put('/faq/{faq}', [FaqController::class, 'update'])->middleware('can:edit faq')->name('faq.update');
        Route::delete('/faq/{faq}', [FaqController::class, 'destroy'])->middleware('can:delete faq')->name('faq.destroy');
        Route::post('/faq/fetch-for-datatable', [FaqController::class, 'fetchForDatatable'])->middleware('can:view faq')->name('faq.fetch-for-datatable');
        Route::put('/faq/approve/{faq}', [FaqController::class, 'approve'])->middleware('can:approve faq')->name('faq.approve');
        Route::put('/faq/publish/{faq}', [FaqController::class, 'publish'])->middleware('can:publish faq')->name('faq.publish');


        // ------------------------------------
        //Labs Category
        // ------------------------------------
        Route::get('/labs_category', [LabsCategoryController::class, 'index'])->middleware('can:view laboratories category')->name('labs_category.index');
        Route::get('/labs_category/create', [LabsCategoryController::class, 'create'])->middleware('can:add laboratories category')->name('labs_category.create');
        Route::post('/labs_category', [LabsCategoryController::class, 'store'])->middleware('can:add laboratories category')->name('labs_category.store');
        Route::get('/labs_category/{laboratoriesCategory}', [LabsCategoryController::class, 'show'])->middleware('can:view laboratories category')->name('labs_category.show');
        Route::get('/labs_category/{laboratoriesCategory}/edit', [LabsCategoryController::class, 'edit'])->middleware('can:edit laboratories category')->name('labs_category.edit');
        Route::put('/labs_category/{laboratoriesCategory}', [LabsCategoryController::class, 'update'])->middleware('can:edit laboratories category')->name('labs_category.update');
        Route::delete('/labs_category/{laboratoriesCategory}', [LabsCategoryController::class, 'destroy'])->middleware('can:delete laboratories category')->name('labs_category.destroy');
        Route::post('/labs_category/fetch-for-datatable', [LabsCategoryController::class, 'fetchForDatatable'])->middleware('can:view laboratories category')->name('labs_category.fetch-for-datatable');
        Route::put('/labs_category/approve/{laboratoriesCategory}', [LabsCategoryController::class, 'approve'])->middleware('can:approve laboratories category')->name('labs_category.approve');
        Route::put('/labs_category/publish/{laboratoriesCategory}', [LabsCategoryController::class, 'publish'])->middleware('can:publish laboratories category')->name('labs_category.publish');
        // ------------------------------------
        //Labs
        // ------------------------------------
        Route::get('/labs/{laboratoriesCategory}', [LabsPageController::class, 'index'])->middleware('can:view laboratories page')->name('labs.index');
        Route::get('/labs/create', [LabsPageController::class, 'create'])->middleware('can:add laboratories page')->name('labs.create');
        Route::post('/labs', [LabsPageController::class, 'store'])->middleware('can:add laboratories page')->name('labs.store');
        Route::get('/labs/{laboratoriesPage}', [LabsPageController::class, 'show'])->middleware('can:view laboratories page')->name('labs.show');
        Route::get('/labs/{laboratoriesPage}/edit', [LabsPageController::class, 'edit'])->middleware('can:edit laboratories page')->name('labs.edit');
        Route::put('/labs/{laboratoriesPage}', [LabsPageController::class, 'update'])->middleware('can:edit laboratories page')->name('labs.update');
        Route::delete('/labs/{laboratoriesPage}', [LabsPageController::class, 'destroy'])->middleware('can:delete laboratories page')->name('labs.destroy');
        Route::post('/labs/fetch-for-datatable/{laboratoriesCategory}', [LabsPageController::class, 'fetchForDatatable'])->middleware('can:view laboratories page')->name('labs.fetch-for-datatable');
        Route::delete('/labs/files/{laboratoriesFile}', [LabsPageController::class, 'destroyFile'])->middleware('can:delete laboratories page file')->name('labs.files.destroy');
        Route::put('/labs/approve/{laboratoriesPage}', [LabsPageController::class, 'approve'])->middleware('can:approve laboratories page')->name('labs.approve');
        Route::put('/labs/publish/{laboratoriesPage}', [LabsPageController::class, 'publish'])->middleware('can:publish laboratories page')->name('labs.publish');



        // ------------------------------------
        // Government Portal
        // ------------------------------------
        Route::get('/cpcb-portals', [PortalController::class, 'index'])->middleware('can:view cpcb portal')->name('cpcb-portals.index');
        Route::get('/cpcb-portals/create', [PortalController::class, 'create'])->middleware('can:add cpcb portal')->name('cpcb-portals.create');
        Route::post('/cpcb-portals', [PortalController::class, 'store'])->middleware('can:add cpcb portal')->name('cpcb-portals.store');
        Route::get('/cpcb-portals/{portal}', [PortalController::class, 'show'])->middleware('can:view cpcb portal')->name('cpcb-portals.show');
        Route::get('/cpcb-portals/{portal}/edit', [PortalController::class, 'edit'])->middleware('can:edit cpcb portal')->name('cpcb-portals.edit');
        Route::put('/cpcb-portals/{portal}', [PortalController::class, 'update'])->middleware('can:edit cpcb portal')->name('cpcb-portals.update');
        Route::delete('/cpcb-portals/{portal}', [PortalController::class, 'destroy'])->middleware('can:delete cpcb portal')->name('cpcb-portals.destroy');
        Route::post('/cpcb-portals/fetch-for-datatable', [PortalController::class, 'fetchForDatatable'])->middleware('can:view cpcb portal')->name('cpcb-portals.fetch-for-datatable');
        Route::put('/cpcb-portals/approve/{portal}', [PortalController::class, 'approve'])->middleware('can:approve cpcb portal')->name('cpcb-portals.approve');
        Route::put('/cpcb-portals/publish/{portal}', [PortalController::class, 'publish'])->middleware('can:publish cpcb portal')->name('cpcb-portals.publish');

        // ------------------------------------
        // EPR Portals
        // ------------------------------------
        Route::get('/epr-portals', [EprPortalController::class, 'index'])->middleware('can:view epr portal')->name('epr-portals.index');
        Route::get('/epr-portals/create', [EprPortalController::class, 'create'])->middleware('can:add epr portal')->name('epr-portals.create');
        Route::post('/epr-portals', [EprPortalController::class, 'store'])->middleware('can:add epr portal')->name('epr-portals.store');
        Route::get('/epr-portals/{eprPortal}', [EprPortalController::class, 'show'])->middleware('can:view epr portal')->name('epr-portals.show');
        Route::get('/epr-portals/{eprPortal}/edit', [EprPortalController::class, 'edit'])->middleware('can:edit epr portal')->name('epr-portals.edit');
        Route::put('/epr-portals/{eprPortal}', [EprPortalController::class, 'update'])->middleware('can:edit epr portal')->name('epr-portals.update');
        Route::delete('/epr-portals/{eprPortal}', [EprPortalController::class, 'destroy'])->middleware('can:delete epr portal')->name('epr-portals.destroy');
        Route::post('/epr-portals/fetch-for-datatable', [EprPortalController::class, 'fetchForDatatable'])->middleware('can:view epr portal')->name('epr-portals.fetch-for-datatable');
        Route::put('/epr-portals/approve/{eprPortal}', [EprPortalController::class, 'approve'])->middleware('can:approve epr portal')->name('epr-portals.approve');
        Route::put('/epr-portals/publish/{eprPortal}', [EprPortalController::class, 'publish'])->middleware('can:publish epr portal')->name('epr-portals.publish');



        // ------------------------------------
        // Latest CPCB Section
        // ------------------------------------
        Route::get('/latest-cpcbs', [LatestCpcbController::class, 'index'])->middleware('can:view latest cpcb')->name('latest-cpcbs.index');
        Route::get('/latest-cpcbs/create', [LatestCpcbController::class, 'create'])->middleware('can:add latest cpcb')->name('latest-cpcbs.create');
        Route::post('/latest-cpcbs', [LatestCpcbController::class, 'store'])->middleware('can:add latest cpcb')->name('latest-cpcbs.store');
        Route::get('/latest-cpcbs/{latestCpcb}', [LatestCpcbController::class, 'show'])->middleware('can:view latest cpcb')->name('latest-cpcbs.show');
        Route::get('/latest-cpcbs/{latestCpcb}/edit', [LatestCpcbController::class, 'edit'])->middleware('can:edit latest cpcb')->name('latest-cpcbs.edit');
        Route::put('/latest-cpcbs/{latestCpcb}', [LatestCpcbController::class, 'update'])->middleware('can:edit latest cpcb')->name('latest-cpcbs.update');
        Route::delete('/latest-cpcbs/{latestCpcb}', [LatestCpcbController::class, 'destroy'])->middleware('can:delete latest cpcb')->name('latest-cpcbs.destroy');
        Route::post('/latest-cpcbs/fetch-for-datatable', [LatestCpcbController::class, 'fetchForDatatable'])->middleware('can:view latest cpcb')->name('latest-cpcbs.fetch-for-datatable');
        Route::put('/latest-cpcbs/approve/{latestCpcb}', [LatestCpcbController::class, 'approve'])->middleware('can:approve latest cpcb')->name('latest-cpcbs.approve');
        Route::put('/latest-cpcbs/publish/{latestCpcb}', [LatestCpcbController::class, 'publish'])->middleware('can:publish latest cpcb')->name('latest-cpcbs.publish');

        // ------------------------------------
        // NGT Court Cases Section
        // ------------------------------------
        Route::get('/ngt-court-cases', [NgtCourtCaseController::class, 'index'])->middleware('can:view ngt court case')->name('ngt-court-cases.index');
        Route::get('/ngt-court-cases/create', [NgtCourtCaseController::class, 'create'])->middleware('can:add ngt court case')->name('ngt-court-cases.create');
        Route::post('/ngt-court-cases', [NgtCourtCaseController::class, 'store'])->middleware('can:add ngt court case')->name('ngt-court-cases.store');
        Route::get('/ngt-court-cases/{ngtCourtCase}', [NgtCourtCaseController::class, 'show'])->middleware('can:view ngt court case')->name('ngt-court-cases.show');
        Route::get('/ngt-court-cases/{ngtCourtCase}/edit', [NgtCourtCaseController::class, 'edit'])->middleware('can:edit ngt court case')->name('ngt-court-cases.edit');
        Route::put('/ngt-court-cases/{ngtCourtCase}', [NgtCourtCaseController::class, 'update'])->middleware('can:edit ngt court case')->name('ngt-court-cases.update');
        Route::delete('/ngt-court-cases/{ngtCourtCase}', [NgtCourtCaseController::class, 'destroy'])->middleware('can:delete ngt court case')->name('ngt-court-cases.destroy');
        Route::post('/ngt-court-cases/fetch-for-datatable', [NgtCourtCaseController::class, 'fetchForDatatable'])->middleware('can:view ngt court case')->name('ngt-court-cases.fetch-for-datatable');
        Route::put('/ngt-court-cases/approve/{ngtCourtCase}', [NgtCourtCaseController::class, 'approve'])->middleware('can:approve ngt court case')->name('ngt-court-cases.approve');
        Route::put('/ngt-court-cases/publish/{ngtCourtCase}', [NgtCourtCaseController::class, 'publish'])->middleware('can:publish ngt court case')->name('ngt-court-cases.publish');

        // ------------------------------------
        // Quality Zone Section
        // ------------------------------------
        Route::get('/quality-zones', [QualityZoneController::class, 'index'])->middleware('can:view quality zone')->name('quality-zones.index');
        Route::get('/quality-zones/create', [QualityZoneController::class, 'create'])->middleware('can:add quality zone')->name('quality-zones.create');
        Route::post('/quality-zones', [QualityZoneController::class, 'store'])->middleware('can:add quality zone')->name('quality-zones.store');
        Route::get('/quality-zones/{qualityZone}', [QualityZoneController::class, 'show'])->middleware('can:view quality zone')->name('quality-zones.show');
        Route::get('/quality-zones/{qualityZone}/edit', [QualityZoneController::class, 'edit'])->middleware('can:edit quality zone')->name('quality-zones.edit');
        Route::put('/quality-zones/{qualityZone}', [QualityZoneController::class, 'update'])->middleware('can:edit quality zone')->name('quality-zones.update');
        Route::delete('/quality-zones/{qualityZone}', [QualityZoneController::class, 'destroy'])->middleware('can:delete quality zone')->name('quality-zones.destroy');
        Route::post('/quality-zones/fetch-for-datatable', [QualityZoneController::class, 'fetchForDatatable'])->middleware('can:view quality zone')->name('quality-zones.fetch-for-datatable');
        Route::put('/quality-zones/approve/{qualityZone}', [QualityZoneController::class, 'approve'])->middleware('can:approve quality zone')->name('quality-zones.approve');
        Route::put('/quality-zones/publish/{qualityZone}', [QualityZoneController::class, 'publish'])->middleware('can:publish quality zone')->name('quality-zones.publish');

        // ------------------------------------
        // Agra Air Quality Section
        // ------------------------------------
        Route::get('/agra-air-qualities', [AgraAirQualityController::class, 'index'])->middleware('can:view agra air quality')->name('agra-air-qualities.index');
        Route::get('/agra-air-qualities/create', [AgraAirQualityController::class, 'create'])->middleware('can:add agra air quality')->name('agra-air-qualities.create');
        Route::post('/agra-air-qualities', [AgraAirQualityController::class, 'store'])->middleware('can:add agra air quality')->name('agra-air-qualities.store');
        Route::get('/agra-air-qualities/{agraAirQuality}', [AgraAirQualityController::class, 'show'])->middleware('can:view agra air quality')->name('agra-air-qualities.show');
        Route::get('/agra-air-qualities/{agraAirQuality}/edit', [AgraAirQualityController::class, 'edit'])->middleware('can:edit agra air quality')->name('agra-air-qualities.edit');
        Route::put('/agra-air-qualities/{agraAirQuality}', [AgraAirQualityController::class, 'update'])->middleware('can:edit agra air quality')->name('agra-air-qualities.update');
        Route::delete('/agra-air-qualities/{agraAirQuality}', [AgraAirQualityController::class, 'destroy'])->middleware('can:delete agra air quality')->name('agra-air-qualities.destroy');
        Route::post('/agra-air-qualities/fetch-for-datatable', [AgraAirQualityController::class, 'fetchForDatatable'])->middleware('can:view agra air quality')->name('agra-air-qualities.fetch-for-datatable');
        Route::put('/agra-air-qualities/approve/{agraAirQuality}', [AgraAirQualityController::class, 'approve'])->middleware('can:approve agra air quality')->name('agra-air-qualities.approve');
        Route::put('/agra-air-qualities/publish/{agraAirQuality}', [AgraAirQualityController::class, 'publish'])->middleware('can:publish agra air quality')->name('agra-air-qualities.publish');

        // Agra Air Quality - Annual Average File Routes
        Route::get('/agra-air-quality/annual-average-file', [AgraAirQualityController::class, 'annualAverageFile'])->middleware('can:view agra air quality')->name('agra-air-quality.annual-average-file');
        Route::post('/agra-air-quality/upload-annual-average-file', [AgraAirQualityController::class, 'uploadAnnualAverageFile'])->middleware('can:add agra air quality')->name('agra-air-quality.upload-annual-average-file');
        Route::delete('/agra-air-quality/delete-annual-average-file', [AgraAirQualityController::class, 'deleteAnnualAverageFile'])->middleware('can:delete agra air quality')->name('agra-air-quality.delete-annual-average-file');


        // ------------------------------------
        // Direction Type
        // ------------------------------------
        Route::resource('direction-types', DirectionTypeController::class)->middleware([
            'index' => 'can:view direction type',
            'create' => 'can:add direction type',
            'store' => 'can:add direction type',
            'show' => 'can:view direction type',
            'edit' => 'can:edit direction type',
            'update' => 'can:edit direction type',
            'destroy' => 'can:delete direction type',
        ]);
        Route::post('/direction-types/fetch-for-datatable', [DirectionTypeController::class, 'fetchForDatatable'])->middleware('can:view direction type')->name('direction-types.fetch-for-datatable');
        Route::put('/direction-types/approve/{directionType}', [DirectionTypeController::class, 'approve'])->middleware('can:approve direction type')->name('direction-types.approve');
        Route::put('/direction-types/publish/{directionType}', [DirectionTypeController::class, 'publish'])->middleware('can:publish direction type')->name('direction-types.publish');


        // ------------------------------------
        // Direction Category
        // ------------------------------------
        Route::get('/direction_category', [DirectionCategoryController::class, 'index'])->middleware('can:view direction category')->name('direction_category.index');
        Route::get('/direction_category/create', [DirectionCategoryController::class, 'create'])->middleware('can:add direction category')->name('direction_category.create');
        Route::post('/direction_category', [DirectionCategoryController::class, 'store'])->middleware('can:add direction category')->name('direction_category.store');
        Route::get('/direction_category/{directionCategory}', [DirectionCategoryController::class, 'show'])->middleware('can:view direction category')->name('direction_category.show');
        Route::get('/direction_category/{directionCategory}/edit', [DirectionCategoryController::class, 'edit'])->middleware('can:edit direction category')->name('direction_category.edit');
        Route::put('/direction_category/{directionCategory}', [DirectionCategoryController::class, 'update'])->middleware('can:edit direction category')->name('direction_category.update');
        Route::delete('/direction_category/{directionCategory}', [DirectionCategoryController::class, 'destroy'])->middleware('can:delete direction category')->name('direction_category.destroy');
        Route::post('/direction_category/fetch-for-datatable', [DirectionCategoryController::class, 'fetchForDatatable'])->middleware('can:view direction category')->name('direction_category.fetch-for-datatable');
        Route::put('/direction_category/approve/{directionCategory}', [DirectionCategoryController::class, 'approve'])->middleware('can:approve direction category')->name('direction_category.approve');
        Route::put('/direction_category/publish/{directionCategory}', [DirectionCategoryController::class, 'publish'])->middleware('can:publish direction category')->name('direction_category.publish');

        // ------------------------------------
        // Direction State
        // ------------------------------------

        // ------------------------------------
        // Comment Report
        // ------------------------------------
        Route::get('/comment-reports', [CommentReportController::class, 'index'])->middleware('can:view comment report')->name('comment-reports.index');
        Route::get('/comment-reports/create', [CommentReportController::class, 'create'])->middleware('can:add comment report')->name('comment-reports.create');
        Route::post('/comment-reports', [CommentReportController::class, 'store'])->middleware('can:add comment report')->name('comment-reports.store');
        Route::get('/comment-reports/{commentReport}', [CommentReportController::class, 'show'])->middleware('can:view comment report')->name('comment-reports.show');
        Route::get('/comment-reports/{commentReport}/edit', [CommentReportController::class, 'edit'])->middleware('can:edit comment report')->name('comment-reports.edit');
        Route::put('/comment-reports/{commentReport}', [CommentReportController::class, 'update'])->middleware('can:edit comment report')->name('comment-reports.update');
        Route::delete('/comment-reports/{commentReport}', [CommentReportController::class, 'destroy'])->middleware('can:delete comment report')->name('comment-reports.destroy');
        Route::post('/comment-reports/fetch-for-datatable', [CommentReportController::class, 'fetchForDatatable'])->middleware('can:view comment report')->name('comment-reports.fetch-for-datatable');
        Route::put('/comment-reports/approve/{commentReport}', [CommentReportController::class, 'approve'])->middleware('can:approve comment report')->name('comment-reports.approve');
        Route::put('/comment-reports/publish/{commentReport}', [CommentReportController::class, 'publish'])->middleware('can:publish comment report')->name('comment-reports.publish');
        Route::get('/direction_state', [DirectionStateController::class, 'index'])->middleware('can:view direction state')->name('direction_state.index');
        Route::get('/direction_state/create', [DirectionStateController::class, 'create'])->middleware('can:add direction state')->name('direction_state.create');
        Route::post('/direction_state', [DirectionStateController::class, 'store'])->middleware('can:add direction state')->name('direction_state.store');
        Route::get('/direction_state/{directionState}', [DirectionStateController::class, 'show'])->middleware('can:view direction state')->name('direction_state.show');
        Route::get('/direction_state/{directionState}/edit', [DirectionStateController::class, 'edit'])->middleware('can:edit direction state')->name('direction_state.edit');
        Route::put('/direction_state/{directionState}', [DirectionStateController::class, 'update'])->middleware('can:edit direction state')->name('direction_state.update');
        Route::delete('/direction_state/{directionState}', [DirectionStateController::class, 'destroy'])->middleware('can:delete direction state')->name('direction_state.destroy');
        Route::post('/direction_state/fetch-for-datatable', [DirectionStateController::class, 'fetchForDatatable'])->middleware('can:view direction state')->name('direction_state.fetch-for-datatable');
        Route::put('/direction_state/approve/{directionState}', [DirectionStateController::class, 'approve'])->middleware('can:approve direction state')->name('direction_state.approve');
        Route::put('/direction_state/publish/{directionState}', [DirectionStateController::class, 'publish'])->middleware('can:publish direction state')->name('direction_state.publish');

        // ------------------------------------
        // Direction Issued To
        // ------------------------------------
        Route::get('/direction_issued_to', [DirectionIssuedToController::class, 'index'])->middleware('can:view direction issued to')->name('direction_issued_to.index');
        Route::get('/direction_issued_to/create', [DirectionIssuedToController::class, 'create'])->middleware('can:add direction issued to')->name('direction_issued_to.create');
        Route::post('/direction_issued_to', [DirectionIssuedToController::class, 'store'])->middleware('can:add direction issued to')->name('direction_issued_to.store');
        Route::get('/direction_issued_to/{directionIssuedTo}', [DirectionIssuedToController::class, 'show'])->middleware('can:view direction issued to')->name('direction_issued_to.show');
        Route::get('/direction_issued_to/{directionIssuedTo}/edit', [DirectionIssuedToController::class, 'edit'])->middleware('can:edit direction issued to')->name('direction_issued_to.edit');
        Route::put('/direction_issued_to/{directionIssuedTo}', [DirectionIssuedToController::class, 'update'])->middleware('can:edit direction issued to')->name('direction_issued_to.update');
        Route::delete('/direction_issued_to/{directionIssuedTo}', [DirectionIssuedToController::class, 'destroy'])->middleware('can:delete direction issued to')->name('direction_issued_to.destroy');
        Route::post('/direction_issued_to/fetch-for-datatable', [DirectionIssuedToController::class, 'fetchForDatatable'])->middleware('can:view direction issued to')->name('direction_issued_to.fetch-for-datatable');
        Route::put('/direction_issued_to/approve/{directionIssuedTo}', [DirectionIssuedToController::class, 'approve'])->middleware('can:approve direction issued to')->name('direction_issued_to.approve');
        Route::put('/direction_issued_to/publish/{directionIssuedTo}', [DirectionIssuedToController::class, 'publish'])->middleware('can:publish direction issued to')->name('direction_issued_to.publish');

        // ------------------------------------
        // Direction Subject
        // ------------------------------------
        Route::get('/direction_subject', [DirectionSubjectController::class, 'index'])->middleware('can:view direction subject')->name('direction_subject.index');
        Route::get('/direction_subject/create', [DirectionSubjectController::class, 'create'])->middleware('can:add direction subject')->name('direction_subject.create');
        Route::post('/direction_subject', [DirectionSubjectController::class, 'store'])->middleware('can:add direction subject')->name('direction_subject.store');
        Route::get('/direction_subject/{directionSubject}', [DirectionSubjectController::class, 'show'])->middleware('can:view direction subject')->name('direction_subject.show');
        Route::get('/direction_subject/{directionSubject}/edit', [DirectionSubjectController::class, 'edit'])->middleware('can:edit direction subject')->name('direction_subject.edit');
        Route::put('/direction_subject/{directionSubject}', [DirectionSubjectController::class, 'update'])->middleware('can:edit direction subject')->name('direction_subject.update');
        Route::delete('/direction_subject/{directionSubject}', [DirectionSubjectController::class, 'destroy'])->middleware('can:delete direction subject')->name('direction_subject.destroy');
        Route::post('/direction_subject/fetch-for-datatable', [DirectionSubjectController::class, 'fetchForDatatable'])->middleware('can:view direction subject')->name('direction_subject.fetch-for-datatable');
        Route::put('/direction_subject/approve/{directionSubject}', [DirectionSubjectController::class, 'approve'])->middleware('can:approve direction subject')->name('direction_subject.approve');
        Route::put('/direction_subject/publish/{directionSubject}', [DirectionSubjectController::class, 'publish'])->middleware('can:publish direction subject')->name('direction_subject.publish');

        // ------------------------------------
        // Direction Act Type
        // ------------------------------------
        Route::get('/direction_act_type', [DirectionActTypeController::class, 'index'])->middleware('can:view direction act type')->name('direction_act_type.index');
        Route::get('/direction_act_type/create', [DirectionActTypeController::class, 'create'])->middleware('can:add direction act type')->name('direction_act_type.create');
        Route::post('/direction_act_type', [DirectionActTypeController::class, 'store'])->middleware('can:add direction act type')->name('direction_act_type.store');
        Route::get('/direction_act_type/{directionActType}', [DirectionActTypeController::class, 'show'])->middleware('can:view direction act type')->name('direction_act_type.show');
        Route::get('/direction_act_type/{directionActType}/edit', [DirectionActTypeController::class, 'edit'])->middleware('can:edit direction act type')->name('direction_act_type.edit');
        Route::put('/direction_act_type/{directionActType}', [DirectionActTypeController::class, 'update'])->middleware('can:edit direction act type')->name('direction_act_type.update');
        Route::delete('/direction_act_type/{directionActType}', [DirectionActTypeController::class, 'destroy'])->middleware('can:delete direction act type')->name('direction_act_type.destroy');
        Route::post('/direction_act_type/fetch-for-datatable', [DirectionActTypeController::class, 'fetchForDatatable'])->middleware('can:view direction act type')->name('direction_act_type.fetch-for-datatable');
        Route::put('/direction_act_type/approve/{directionActType}', [DirectionActTypeController::class, 'approve'])->middleware('can:approve direction act type')->name('direction_act_type.approve');
        Route::put('/direction_act_type/publish/{directionActType}', [DirectionActTypeController::class, 'publish'])->middleware('can:publish direction act type')->name('direction_act_type.publish');


        // ------------------------------------
        // Environmental Regulation
        // ------------------------------------
        Route::get('/environmental-regulation', [EnvironmentalRegulationController::class, 'index'])->middleware('can:view environmental regulation')->name('environmental-regulation.index');
        Route::get('/environmental-regulation/create', [EnvironmentalRegulationController::class, 'create'])->middleware('can:add environmental regulation')->name('environmental-regulation.create');
        Route::post('/environmental-regulation', [EnvironmentalRegulationController::class, 'store'])->middleware('can:add environmental regulation')->name('environmental-regulation.store');
        Route::get('/environmental-regulation/{environmentalRegulation}', [EnvironmentalRegulationController::class, 'show'])->middleware('can:view environmental regulation')->name('environmental-regulation.show');
        Route::get('/environmental-regulation/{environmentalRegulation}/edit', [EnvironmentalRegulationController::class, 'edit'])->middleware('can:edit environmental regulation')->name('environmental-regulation.edit');
        Route::put('/environmental-regulation/{environmentalRegulation}', [EnvironmentalRegulationController::class, 'update'])->middleware('can:edit environmental regulation')->name('environmental-regulation.update');
        Route::delete('/environmental-regulation/{environmentalRegulation}', [EnvironmentalRegulationController::class, 'destroy'])->middleware('can:delete environmental regulation')->name('environmental-regulation.destroy');
        Route::post('/environmental-regulation/fetch-for-datatable', [EnvironmentalRegulationController::class, 'fetchForDatatable'])->middleware('can:view environmental regulation')->name('environmental-regulation.fetch-for-datatable');
        Route::put('/environmental-regulation/approve/{environmentalRegulation}', [EnvironmentalRegulationController::class, 'approve'])->middleware('can:approve environmental regulation')->name('environmental-regulation.approve');
        Route::put('/environmental-regulation/publish/{environmentalRegulation}', [EnvironmentalRegulationController::class, 'publish'])->middleware('can:publish environmental regulation')->name('environmental-regulation.publish');
        // ------------------------------------
        // Environmental Regulation Details
        // ------------------------------------
        Route::get('/environmental-regulation-details', [EnvironmentalRegulationDetailController::class, 'index'])->middleware('can:view environmental regulation detail')->name('environmental-regulation-details.index');
        Route::get('/environmental-regulation-details/create', [EnvironmentalRegulationDetailController::class, 'create'])->middleware('can:add environmental regulation detail')->name('environmental-regulation-details.create');
        Route::post('/environmental-regulation-details', [EnvironmentalRegulationDetailController::class, 'store'])->middleware('can:add environmental regulation detail')->name('environmental-regulation-details.store');
        Route::get('/environmental-regulation-details/{environmentalRegulationDetail}', [EnvironmentalRegulationDetailController::class, 'show'])->middleware('can:view environmental regulation detail')->name('environmental-regulation-details.show');
        Route::get('/environmental-regulation-details/{environmentalRegulationDetail}/edit', [EnvironmentalRegulationDetailController::class, 'edit'])->middleware('can:edit environmental regulation detail')->name('environmental-regulation-details.edit');
        Route::put('/environmental-regulation-details/{environmentalRegulationDetail}', [EnvironmentalRegulationDetailController::class, 'update'])->middleware('can:edit environmental regulation detail')->name('environmental-regulation-details.update');
        Route::delete('/environmental-regulation-details/{environmentalRegulationDetail}', [EnvironmentalRegulationDetailController::class, 'destroy'])->middleware('can:delete environmental regulation detail')->name('environmental-regulation-details.destroy');
        Route::post('/environmental-regulation-details/fetch-for-datatable', [EnvironmentalRegulationDetailController::class, 'fetchForDatatable'])->middleware('can:view environmental regulation detail')->name('environmental-regulation-details.fetch-for-datatable');
        Route::put('/environmental-regulation-details/approve/{environmentalRegulationDetail}', [EnvironmentalRegulationDetailController::class, 'approve'])->middleware('can:approve environmental regulation detail')->name('environmental-regulation-details.approve');
        Route::put('/environmental-regulation-details/publish/{environmentalRegulationDetail}', [EnvironmentalRegulationDetailController::class, 'publish'])->middleware('can:publish environmental regulation detail')->name('environmental-regulation-details.publish');

        // ------------------------------------
        // Environmental Regulation Detail Files
        // ------------------------------------
        Route::delete('/environmental-regulation-details/files/{id}', [EnvironmentalRegulationDetailController::class, 'destroyFile'])->middleware('can:delete environmental regulation detail')->name('environmental-regulation-details.files.destroy');

        // ------------------------------------
        // Information Centers
        // ------------------------------------
        Route::get('/information-centers', [InformationCenterController::class, 'index'])->middleware('can:view information centers')->name('information-centers.index');
        Route::get('/information-centers/create', [InformationCenterController::class, 'create'])->middleware('can:add information centers')->name('information-centers.create');
        Route::post('/information-centers', [InformationCenterController::class, 'store'])->middleware('can:add information centers')->name('information-centers.store');
        Route::post('/information-centers/fetch-for-datatable', [InformationCenterController::class, 'fetchForDatatable'])->middleware('can:view information centers')->name('information-centers.fetch-for-datatable');
        Route::get('/information-centers/{informationCenter}', [InformationCenterController::class, 'show'])->middleware('can:view information centers')->name('information-centers.show');
        Route::get('/information-centers/{informationCenter}/edit', [InformationCenterController::class, 'edit'])->middleware('can:edit information centers')->name('information-centers.edit');
        Route::put('/information-centers/{informationCenter}', [InformationCenterController::class, 'update'])->middleware('can:edit information centers')->name('information-centers.update');
        Route::delete('/information-centers/{informationCenter}', [InformationCenterController::class, 'destroy'])->middleware('can:delete information centers')->name('information-centers.destroy');
        Route::put('/information-centers/approve/{informationCenter}', [InformationCenterController::class, 'approve'])->middleware('can:approve information centers')->name('information-centers.approve');
        Route::put('/information-centers/publish/{informationCenter}', [InformationCenterController::class, 'publish'])->middleware('can:publish information centers')->name('information-centers.publish');
        Route::post('/information-centers/{informationCenter}/restore', [InformationCenterController::class, 'restore'])->middleware('can:delete information centers')->name('information-centers.restore');
        Route::delete('/information-centers/{informationCenter}/force-delete', [InformationCenterController::class, 'forceDelete'])->middleware('can:delete information centers')->name('information-centers.force-delete');

        // ------------------------------------
        // Information Center Details
        // ------------------------------------
        Route::get('/information-center-details', [InformationCenterDetailController::class, 'index'])->middleware('can:view information center detail')->name('information-center-details.index');
        Route::get('/information-center-details/create', [InformationCenterDetailController::class, 'create'])->middleware('can:add information center detail')->name('information-center-details.create');
        Route::post('/information-center-details', [InformationCenterDetailController::class, 'store'])->middleware('can:add information center detail')->name('information-center-details.store');
        Route::post('/information-center-details/fetch-for-datatable', [InformationCenterDetailController::class, 'fetchForDatatable'])->middleware('can:view information center detail')->name('information-center-details.fetch-for-datatable');
        Route::get('/information-center-details/{informationCenterDetail}', [InformationCenterDetailController::class, 'show'])->middleware('can:view information center detail')->name('information-center-details.show');
        Route::get('/information-center-details/{informationCenterDetail}/edit', [InformationCenterDetailController::class, 'edit'])->middleware('can:edit information center detail')->name('information-center-details.edit');
        Route::put('/information-center-details/{informationCenterDetail}', [InformationCenterDetailController::class, 'update'])->middleware('can:edit information center detail')->name('information-center-details.update');
        Route::delete('/information-center-details/{informationCenterDetail}', [InformationCenterDetailController::class, 'destroy'])->middleware('can:delete information center detail')->name('information-center-details.destroy');
        Route::put('/information-center-details/approve/{informationCenterDetail}', [InformationCenterDetailController::class, 'approve'])->middleware('can:approve information center detail')->name('information-center-details.approve');
        Route::put('/information-center-details/publish/{informationCenterDetail}', [InformationCenterDetailController::class, 'publish'])->middleware('can:publish information center detail')->name('information-center-details.publish');
        Route::post('/information-center-details/{informationCenterDetail}/restore', [InformationCenterDetailController::class, 'restore'])->middleware('can:delete information center detail')->name('information-center-details.restore');
        Route::delete('/information-center-details/{informationCenterDetail}/force-delete', [InformationCenterDetailController::class, 'forceDelete'])->middleware('can:delete information center detail')->name('information-center-details.force-delete');

        // ------------------------------------
        // Studies and Reports
        // ------------------------------------
        Route::get('/studies_reports', [StudiesReportController::class, 'index'])->middleware('can:view studies report')->name('studies_reports.index');
        Route::get('/studies_reports/create', [StudiesReportController::class, 'create'])->middleware('can:add studies report')->name('studies_reports.create');
        Route::post('/studies_reports', [StudiesReportController::class, 'store'])->middleware('can:add studies report')->name('studies_reports.store');
        Route::get('/studies_reports/{studiesReport}', [StudiesReportController::class, 'show'])->middleware('can:view studies report')->name('studies_reports.show');
        Route::get('/studies_reports/{studiesReport}/edit', [StudiesReportController::class, 'edit'])->middleware('can:edit studies report')->name('studies_reports.edit');
        Route::put('/studies_reports/{studiesReport}', [StudiesReportController::class, 'update'])->middleware('can:edit studies report')->name('studies_reports.update');
        Route::delete('/studies_reports/{studiesReport}', [StudiesReportController::class, 'destroy'])->middleware('can:delete studies report')->name('studies_reports.destroy');
        Route::post('/studies_reports/fetch-for-datatable', [StudiesReportController::class, 'fetchForDatatable'])->middleware('can:view studies report')->name('studies_reports.fetch-for-datatable');
        Route::put('/studies_reports/approve/{studiesReport}', [StudiesReportController::class, 'approve'])->middleware('can:approve studies report')->name('studies_reports.approve');
        Route::put('/studies_reports/publish/{studiesReport}', [StudiesReportController::class, 'publish'])->middleware('can:publish studies report')->name('studies_reports.publish');

        // ------------------------------------
        // Head Office Setup
        // ------------------------------------
        Route::get('/head_offices', [HeadOfficeController::class, 'index'])->middleware('can:view head office')->name('head_offices.index');
        Route::get('/head_offices/create', [HeadOfficeController::class, 'create'])->middleware('can:add head office')->name('head_offices.create');
        Route::post('/head_offices', [HeadOfficeController::class, 'store'])->middleware('can:add head office')->name('head_offices.store');
        Route::get('/head_offices/{headOffice}', [HeadOfficeController::class, 'show'])->middleware('can:view head office')->name('head_offices.show');
        Route::get('/head_offices/{headOffice}/edit', [HeadOfficeController::class, 'edit'])->middleware('can:edit head office')->name('head_offices.edit');
        Route::put('/head_offices/{headOffice}', [HeadOfficeController::class, 'update'])->middleware('can:edit head office')->name('head_offices.update');
        Route::delete('/head_offices/{headOffice}', [HeadOfficeController::class, 'destroy'])->middleware('can:delete head office')->name('head_offices.destroy');
        Route::post('/head_offices/fetch-for-datatable', [HeadOfficeController::class, 'fetchForDatatable'])->middleware('can:view head office')->name('head_offices.fetch-for-datatable');
        Route::put('/head_offices/approve/{headOffice}', [HeadOfficeController::class, 'approve'])->middleware('can:approve head office')->name('head_offices.approve');
        Route::put('/head_offices/publish/{headOffice}', [HeadOfficeController::class, 'publish'])->middleware('can:publish head office')->name('head_offices.publish');

        // ------------------------------------
        // Regional Directorate Setup
        // ------------------------------------
        Route::get('/regional_directorates', [RegionalDirectorateController::class, 'index'])->middleware('can:view regional directorate')->name('regional_directorates.index');
        Route::get('/regional_directorates/create', [RegionalDirectorateController::class, 'create'])->middleware('can:add regional directorate')->name('regional_directorates.create');
        Route::post('/regional_directorates', [RegionalDirectorateController::class, 'store'])->middleware('can:add regional directorate')->name('regional_directorates.store');
        Route::get('/regional_directorates/{regionalDirectorate}', [RegionalDirectorateController::class, 'show'])->middleware('can:view regional directorate')->name('regional_directorates.show');
        Route::get('/regional_directorates/{regionalDirectorate}/edit', [RegionalDirectorateController::class, 'edit'])->middleware('can:edit regional directorate')->name('regional_directorates.edit');
        Route::put('/regional_directorates/{regionalDirectorate}', [RegionalDirectorateController::class, 'update'])->middleware('can:edit regional directorate')->name('regional_directorates.update');
        Route::delete('/regional_directorates/{regionalDirectorate}', [RegionalDirectorateController::class, 'destroy'])->middleware('can:delete regional directorate')->name('regional_directorates.destroy');
        Route::post('/regional_directorates/fetch-for-datatable', [RegionalDirectorateController::class, 'fetchForDatatable'])->middleware('can:view regional directorate')->name('regional_directorates.fetch-for-datatable');
        Route::put('/regional_directorates/approve/{regionalDirectorate}', [RegionalDirectorateController::class, 'approve'])->middleware('can:approve regional directorate')->name('regional_directorates.approve');
        Route::put('/regional_directorates/publish/{regionalDirectorate}', [RegionalDirectorateController::class, 'publish'])->middleware('can:publish regional directorate')->name('regional_directorates.publish');


        // ------------------------------------
        // Directories Setup Routes
        // ------------------------------------
        Route::post('/directories/fetch-for-datatable', [DirectoryController::class, 'fetchForDatatable'])->middleware('can:view directory')->name('directories.fetch-for-datatable');
        Route::put('/directories/approve/{directory}', [DirectoryController::class, 'approve'])->middleware('can:approve directory')->name('directories.approve');
        Route::put('/directories/publish/{directory}', [DirectoryController::class, 'publish'])->middleware('can:publish directory')->name('directories.publish');
        Route::get('/directories', [DirectoryController::class, 'index'])->middleware('can:view directory')->name('directories.index');
        Route::get('/directories/create', [DirectoryController::class, 'create'])->middleware('can:add directory')->name('directories.create');
        Route::post('/directories', [DirectoryController::class, 'store'])->middleware('can:add directory')->name('directories.store');
        Route::get('/directories/{directory}', [DirectoryController::class, 'show'])->middleware('can:view directory')->name('directories.show');
        Route::get('/directories/{directory}/edit', [DirectoryController::class, 'edit'])->middleware('can:edit directory')->name('directories.edit');
        Route::match(['put', 'post'], '/directories/{directory}', [DirectoryController::class, 'update'])->middleware('can:edit directory')->name('directories.update');
        Route::delete('/directories/{directory}', [DirectoryController::class, 'destroy'])->middleware('can:delete directory')->name('directories.destroy');



        // ------------------------------------
        // File View
        // ------------------------------------
        Route::get('/file-view', [FileViewController::class, 'showBackendFile'])->name('backend.file.view');


        // ------------------------------------
        // Translator content
        // ------------------------------------
        Route::post('/translate', [TranslationController::class, 'translate'])->name('translate');

        // ------------------------------------
        // Dynamic Employee Page Fallback
        // ------------------------------------
        Route::get('/{url}', [EmployeeDynamicPageController::class, 'show'])
            ->where('url', '.*')
            ->name('secure.employee.dynamic_page');
    });

    // 5 request every 10 minutes
    // Route::middleware('throttle:5,10')->group(function () {
        Route::post('/login/check', [LoginController::class, 'checkLogin'])->middleware('geoFence')->name('login.check');
    // });

    // OTP Verification for backend login
    Route::middleware(['otp.ratelimit', 'geoFence'])->group(function () {
        Route::post('/login/verify-otp', [LoginController::class, 'verifyOtp'])->name('login.verify-otp');
    });

    Route::post('/login/resend-otp', [LoginController::class, 'resendOtp'])->middleware('geoFence')->name('login.resend-otp');
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [LoginController::class, 'index'])->name('login');


    // check db connection route
    Route::get('/check-db', function () {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'status' => true,
                'message' => 'Database connection is stable',
                'driver' => DB::connection()->getDriverName(),
                'database' => DB::connection()->getDatabaseName(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database connection failed',
                'error' => 'An error occurred. Please try again later.',
            ], 500);
        }
    });



    // Check session ping
    Route::post('/session/ping', [LoginController::class, 'checkSession'])->middleware(['auth', 'CheckConcurrentLogin'])->name('session.ping');
    Route::get('/logout-other-devices', [LoginController::class, 'logOutOtherDevices'])->name('logout.other-devices');

    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');
        return "Cache cleared successfully!";
    });
});

Route::get('/test-mail', function () {
    Mail::raw('SMTP test from Laravel', function ($message) {
        $message->to('subhashkumar9540123@gmail.com')
                ->subject('SMTP Test');
    });

    return 'Mail send attempted';
});

Route::get('/diagnose', function () {
    $results = [];

    // 1. Check DB Connection
    try {
        DB::connection()->getPdo();
        $results['database_connection'] = 'OK';
    } catch (\Exception $e) {
        $results['database_connection'] = 'FAILED: ' . $e->getMessage();
    }

    // 2. Check Recently Added Tables
    $tables = ['otps', 'email_logs', 'sms_logs', 'ip_rate_limits'];
    foreach ($tables as $table) {
        try {
            $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
            $results['tables'][$table] = $exists ? 'EXISTS' : 'MISSING (Needs Migration)';
        } catch (\Exception $e) {
            $results['tables'][$table] = 'ERROR: ' . $e->getMessage();
        }
    }

    // 3. Check Log File Permissions
    try {
        Log::info('Diagnostic log test entry');
        $results['log_writing'] = 'OK (Check storage/logs/laravel.log)';
    } catch (\Exception $e) {
        $results['log_writing'] = 'FAILED: ' . $e->getMessage();
    }

    // 4. Check Cache Read/Write
    try {
        \Illuminate\Support\Facades\Cache::put('diagnose_test', 'ok', 10);
        $value = \Illuminate\Support\Facades\Cache::get('diagnose_test');
        $results['cache_store'] = ($value === 'ok') ? 'OK' : 'FAILED (Read/Write mismatch)';
    } catch (\Exception $e) {
        $results['cache_store'] = 'FAILED: ' . $e->getMessage();
    }

    // 5. Safely Check Env Variables
    $results['env'] = [
        'APP_ENV' => env('APP_ENV'),
        'APP_DEBUG' => env('APP_DEBUG'),
        'LOG_CHANNEL' => env('LOG_CHANNEL'),
        'CACHE_STORE' => env('CACHE_STORE') ?: env('CACHE_DRIVER'),
        'DB_CONNECTION' => env('DB_CONNECTION'),
        'MAIL_MAILER' => env('MAIL_MAILER'),
    ];

    // 6. Test Outgoing SMS Gateway Connectivity
    try {
        $endpoint = config('services.sms.endpoint') ?: 'https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT';
        $parsedUrl = parse_url($endpoint);
        $host = $parsedUrl['host'] ?? 'msdgweb.mgov.gov.in';
        $port = isset($parsedUrl['scheme']) && $parsedUrl['scheme'] === 'https' ? 443 : 80;
        
        $connection = @fsockopen($host, $port, $errno, $errstr, 2.0);
        if (is_resource($connection)) {
            $results['sms_gateway_connectivity'] = "CONNECTED to $host:$port";
            fclose($connection);
        } else {
            $results['sms_gateway_connectivity'] = "FAILED to connect to $host:$port ($errstr)";
        }
    } catch (\Exception $e) {
        $results['sms_gateway_connectivity'] = 'ERROR: ' . $e->getMessage();
    }

    // 7. Test Outgoing SMTP Mail Server Connectivity
    try {
        $host = env('MAIL_HOST', 'sandbox.smtp.mailtrap.io');
        $port = env('MAIL_PORT', 2525);
        $connection = @fsockopen($host, $port, $errno, $errstr, 2.0);
        if (is_resource($connection)) {
            $results['mail_server_connectivity'] = "CONNECTED to $host:$port";
            fclose($connection);
        } else {
            $results['mail_server_connectivity'] = "FAILED to connect to $host:$port ($errstr)";
        }
    } catch (\Exception $e) {
        $results['mail_server_connectivity'] = 'ERROR: ' . $e->getMessage();
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

Route::get('/run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'status' => true,
            'message' => 'Migrations run successfully',
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Migration failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/view-logs', function () {
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) {
        return 'Log file does not exist.';
    }
    
    $file = file($logPath);
    $lines = array_slice($file, -100); // last 100 lines
    
    return response(implode("", $lines), 200, ['Content-Type' => 'text/plain']);
});

// Website routes
// require base_path('routes/website.php');
