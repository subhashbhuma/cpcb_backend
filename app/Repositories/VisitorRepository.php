<?php

namespace App\Repositories;

use App\Models\Visitor;
use Illuminate\Support\Carbon;

class VisitorRepository
{
    /**
     * Create a new visitor record.
     */
    public function create(array $data)
    {
        return Visitor::create($data);
    }

    /**
     * Check if a visitor with the given IP already visited today.
     */
    public function hasVisitedToday(string $ipAddress): bool
    {
        return Visitor::where('ip_address', $ipAddress)
            ->where('visit_date', Carbon::today()->toDateString())
            ->exists();
    }

    /**
     * Get total visitor count (all time).
     */
    public function getTotalCount(): int
    {
        return Visitor::count();
    }

    /**
     * Get today's visitor count.
     */
    public function getTodayCount(): int
    {
        return Visitor::where('visit_date', Carbon::today()->toDateString())->count();
    }

    /**
     * Get the timestamp of the last visitor record.
     */
    public function getLastVisitedAt()
    {
        return Visitor::latest('created_at')->value('created_at');
    }
}
