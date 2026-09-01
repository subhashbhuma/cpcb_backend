<?php

namespace App\Http\Controllers\Secure;

use App\DTO\SiteSettingDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use App\Services\SiteSettingService;
use Illuminate\Http\Request;
use App\Http\Resources\SiteSettingResource;

class SiteSettingController extends Controller
{
    protected $siteSettingService;

    public function __construct()
    {
        $this->siteSettingService = new SiteSettingService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Site Settings';
        $settings = $this->siteSettingService->findFirst();
        return view('secure.site_settings.index', compact('pageTitle', 'settings'));
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $pageTitle = 'Site Setting';
        $siteSetting = $this->siteSettingService->findFirst();
        return view('secure.site_settings.show', compact('pageTitle', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteSetting $siteSetting)
    {
        $pageTitle = 'Site Setting';
        $siteSetting = $this->siteSettingService->findFirst();
        return view('secure.site_settings.edit', compact('pageTitle', 'settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSiteSettingRequest $request, SiteSetting $siteSetting)
    {
        try {
            $siteSettingDto = new SiteSettingDto(
                $request->input('site_name'),
                $request->input('site_name_hi'),
                $request->input('seo_keywords'),
                $request->input('seo_description'),
                $request->hasFile('header_logo') ? $request->file('header_logo') : null,
                $request->hasFile('header_img_1') ? $request->file('header_img_1') : null,
                $request->input('header_name_1'),
                $request->input('header_name_1_hi'),
                $request->hasFile('header_img_2') ? $request->file('header_img_2') : null,
                $request->input('header_name_2'),
                $request->input('header_name_2_hi'),
                $request->hasFile('header_img_3') ? $request->file('header_img_3') : null,
                $request->input('header_name_3'),
                $request->input('header_name_3_hi'),
                $request->hasFile('footer_logo') ? $request->file('footer_logo') : null,
                $request->hasFile('favicon') ? $request->file('favicon') : null,
                $request->hasFile('admin_panel_logo') ? $request->file('admin_panel_logo') : null,
                $request->input('site_address'),
                $request->input('site_address_hi'),
                $request->input('disclaimer'),
                $request->input('disclaimer_hi'),
                $request->input('copyright_text'),
                $request->input('maintained_by_text'),
                $request->input('maintained_by_text_hi'),
                $request->input('copyright_text_hi'),
                $siteSetting->created_by,
                auth()->user()->id
            );

            $siteSetting = $this->siteSettingService->update($siteSettingDto, $siteSetting->id);

            if (!$siteSetting) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updating site setting.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Site setting updated successfully!',
                'data' => $request->all()
            ], 200);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'Security Error') || str_contains($msg, 'Blocked upload')) {
                return response()->json([
                    'success' => false,
                    'message' => $msg
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function findAllForPublic()
    {
        $siteSetting = $this->siteSettingService->findFirst();
        if ($siteSetting) {
            return response()->json([
                'success' => true,
                'message' => 'Site setting found.',
                'data' => SiteSettingResource::make($siteSetting)
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Site setting not found.',
        ], 404);
    }
}

