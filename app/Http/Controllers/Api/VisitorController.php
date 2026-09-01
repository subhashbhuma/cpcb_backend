<?php

namespace App\Http\Controllers\Api;

use App\DTO\VisitorDto;
use App\Http\Controllers\Controller;
use App\Services\VisitorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    protected $visitorService;

    public function __construct()
    {
        $this->visitorService = new VisitorService();
    }

    /**
     * POST /api/visitor/add
     * Called by Next.js middleware on every page load.
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'nullable|ip',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid IP address.',
            ], 422);
        }

        try {
            $ipAddress = $request->input('ip_address', $request->ip());
            $dto = new VisitorDto(
                $ipAddress,
                $request->header('User-Agent'),
                $request->input('page_url'),
                $request->input('session_id')
            );

            $result = $this->visitorService->trackVisit($dto);

            return response()->json([
                'success' => true,
                'recorded' => $result['recorded'],
                'message' => $result['message'],
            ], $result['recorded'] ? 201 : 200);
        } catch (\Exception $e) {
            Log::error('Visitor tracking failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to track visit.',
            ], 500);
        }
    }

    /**
     * GET /api/visitor/stats
     * Returns total visitors, today's count, and last updated date for the Footer.
     */
    public function stats()
    {
        try {
            $stats = $this->visitorService->getStats();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Visitor stats fetch failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch visitor stats.',
            ], 500);
        }
    }
}
