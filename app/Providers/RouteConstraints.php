<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * IDOR Protection: Enforce integer constraints on common route parameters.
 *
 * This prevents non-numeric values (e.g., SQL fragments, path traversals)
 * from reaching controller methods that expect numeric IDs.
 */
class RouteConstraints extends ServiceProvider
{
    public function boot(): void
    {
        // Common route parameter names used across all CRUD controllers
        Route::pattern('id', '[0-9]+');
        Route::pattern('user', '[0-9]+');
        Route::pattern('role', '[0-9]+');
        Route::pattern('menu', '[0-9]+');
        Route::pattern('page', '[0-9]+');
        Route::pattern('pageFile', '[0-9]+');
        Route::pattern('pageCategory', '[0-9]+');
        Route::pattern('slider', '[0-9]+');
        Route::pattern('announcement', '[0-9]+');
        Route::pattern('tender', '[0-9]+');
        Route::pattern('tenderCategory', '[0-9]+');
        Route::pattern('tenderCorrigendum', '[0-9]+');
        Route::pattern('zonalOffice', '[0-9]+');
        Route::pattern('direction', '[0-9]+');
        Route::pattern('directionType', '[0-9]+');
        Route::pattern('directionCategory', '[0-9]+');
        Route::pattern('directionState', '[0-9]+');
        Route::pattern('directionIssuedTo', '[0-9]+');
        Route::pattern('directionSubject', '[0-9]+');
        Route::pattern('directionActType', '[0-9]+');
        Route::pattern('lettersIssued', '[0-9]+');
        Route::pattern('fortnightlyReport', '[0-9]+');
        Route::pattern('event', '[0-9]+');
        Route::pattern('galleryEvent', '[0-9]+');
        Route::pattern('photoGallery', '[0-9]+');
        Route::pattern('photoGalleryFile', '[0-9]+');
        Route::pattern('videoGallery', '[0-9]+');
        Route::pattern('videoGalleryFile', '[0-9]+');
        Route::pattern('contactDetail', '[0-9]+');
        Route::pattern('socialMedia', '[0-9]+');
        Route::pattern('additionalLogo', '[0-9]+');
        Route::pattern('whoIsWho', '[0-9]+');
        Route::pattern('mediaLibrary', '[0-9]+');
        Route::pattern('homeAbout', '[0-9]+');
        Route::pattern('job', '[0-9]+');
        Route::pattern('jobResult', '[0-9]+');
        Route::pattern('division', '[0-9]+');
        Route::pattern('subjectArea', '[0-9]+');
        Route::pattern('technicalReport', '[0-9]+');
        Route::pattern('annualReport', '[0-9]+');
        Route::pattern('publicationCategory', '[0-9]+');
        Route::pattern('publication', '[0-9]+');
        Route::pattern('regionalDirectories', '[0-9]+');
        Route::pattern('faq', '[0-9]+');
        Route::pattern('laboratoriesCategory', '[0-9]+');
        Route::pattern('laboratoriesPage', '[0-9]+');
        Route::pattern('labsFile', '[0-9]+');
        Route::pattern('portal', '[0-9]+');
        Route::pattern('latestCpcb', '[0-9]+');
        Route::pattern('ngtCourtCase', '[0-9]+');
        Route::pattern('qualityZone', '[0-9]+');
        Route::pattern('agraAirQuality', '[0-9]+');
        Route::pattern('governmentPortal', '[0-9]+');
        Route::pattern('quickLink', '[0-9]+');
        Route::pattern('circular', '[0-9]+');
        Route::pattern('queryFormSubject', '[0-9]+');
        Route::pattern('complaintFormSubject', '[0-9]+');
        Route::pattern('feedback', '[0-9]+');
        Route::pattern('complaint', '[0-9]+');
        Route::pattern('studiesReport', '[0-9]+');
        Route::pattern('environmentalRegulation', '[0-9]+');
        Route::pattern('environmentalRegulationDetail', '[0-9]+');
        Route::pattern('siteSetting', '[0-9]+');
        Route::pattern('directory', '[0-9]+');
        Route::pattern('fortnightlyReport', '[0-9]+');
        Route::pattern('commentReport', '[0-9]+');
    }
}
