<?php

namespace App\Services;

use App\DTO\VisitorDto;
use App\Repositories\VisitorRepository;
use Illuminate\Support\Carbon;

class VisitorService
{
    private $visitorRepository;

    public function __construct()
    {
        $this->visitorRepository = new VisitorRepository();
    }

    /**
     * Track a visitor. Deduplicates by IP per day — same IP won't be counted twice on the same day.
     */

    public function trackVisit(VisitorDto $visitorDto): array
    {
        // Check if this IP has already been recorded today
        if ($this->visitorRepository->hasVisitedToday($visitorDto->ip_address)) {
            return [
                'recorded' => false,
                'message' => 'Already tracked today',
            ];
        }

        $data = [
            'ip_address' => $visitorDto->ip_address,
            'visit_date' => Carbon::today()->toDateString(),
        ];

        $this->visitorRepository->create($data);

        return [
            'recorded' => true,
            'message' => 'Visit recorded',
        ];
    }

    /**
     * Get visitor statistics for the footer display.
     */
    public function getStats(): array
    {
        return cache()->remember('visitor_stats_footer', 300, function () {
            $total = $this->visitorRepository->getTotalCount();
            $today = $this->visitorRepository->getTodayCount();
            $lastVisited = $this->visitorRepository->getLastVisitedAt();

            return [
                'total_visitors' => $total,
                'today_visitors' => $today,
                'last_updated'   => getLastUpdatedOn(),
            ];
        });
    }
}
