<?php

namespace App\Services;

use App\DTO\EprPortalDto;
use App\Models\EprPortal;
use App\Repositories\EprPortalRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EprPortalService
{
    use FileUploadTrait;

    private const TEMP_ORDER_OFFSET = 1000000000;

    private $eprPortalRepository;

    public function __construct()
    {
        $this->eprPortalRepository = new EprPortalRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->eprPortalRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->eprPortalRepository->findAll();
    }

    public function findById($id)
    {
        return $this->eprPortalRepository->findById($id);
    }

    /**
     * Get the next available order number (max + 1).
     */
    public function getNextOrder(): int
    {
        $maxOrder = (int) EprPortal::query()->whereNull('deleted_at')->max('order');
        return max(1, $maxOrder + 1);
    }

    /**
     * Re-sequence all active records to 1, 2, 3, ... N without gaps or duplicates.
     */
    public function resequenceOrders(?int $excludeId = null): void
    {
        $query = EprPortal::query()->whereNull('deleted_at');
        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $portals = $query->orderBy('order', 'asc')->orderBy('id', 'asc')->get(['id', 'order']);

        $expectedOrder = 1;
        foreach ($portals as $portal) {
            if ($portal->order !== $expectedOrder) {
                EprPortal::where('id', $portal->id)->update(['order' => $expectedOrder]);
            }
            $expectedOrder++;
        }
    }

    /**
     * Ensure baseline ordering is clean (re-sequences if any 0s, negatives, or duplicate orders exist).
     */
    public function ensureSynced(): void
    {
        $hasInvalid = EprPortal::query()
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('order', '<=', 0)
                  ->orWhereNull('order');
            })
            ->exists();

        if ($hasInvalid) {
            $this->resequenceOrders();
            return;
        }

        $totalCount = EprPortal::query()->whereNull('deleted_at')->count();
        $distinctCount = EprPortal::query()->whereNull('deleted_at')->distinct('order')->count('order');

        if ($totalCount !== $distinctCount) {
            $this->resequenceOrders();
        }
    }

    /**
     * Shift active rows in a range down (+1).
     */
    public function shiftOrdersDown(int $from, ?int $to = null, ?int $excludeId = null): void
    {
        $query = EprPortal::query()
            ->whereNull('deleted_at')
            ->where('order', '>=', $from);

        if ($to !== null) {
            $query->where('order', '<=', $to);
        }

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $ids = $query->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        EprPortal::whereIn('id', $ids)->update([
            'order' => DB::raw('"order" + ' . self::TEMP_ORDER_OFFSET),
        ]);

        EprPortal::whereIn('id', $ids)->update([
            'order' => DB::raw('"order" - ' . (self::TEMP_ORDER_OFFSET - 1)),
        ]);
    }

    /**
     * Shift active rows in a range up (-1).
     */
    public function shiftOrdersUp(int $from, ?int $to = null, ?int $excludeId = null): void
    {
        $query = EprPortal::query()
            ->whereNull('deleted_at')
            ->where('order', '>=', $from);

        if ($to !== null) {
            $query->where('order', '<=', $to);
        }

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $ids = $query->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        EprPortal::whereIn('id', $ids)->update([
            'order' => DB::raw('"order" + ' . self::TEMP_ORDER_OFFSET),
        ]);

        EprPortal::whereIn('id', $ids)->update([
            'order' => DB::raw('"order" - ' . (self::TEMP_ORDER_OFFSET + 1)),
        ]);
    }

    public function create(EprPortalDto $eprPortalDto)
    {
        return DB::transaction(function () use ($eprPortalDto) {
            $this->ensureSynced();

            EprPortal::query()->whereNull('deleted_at')->lockForUpdate()->pluck('id');

            $maxOrder = (int) EprPortal::query()->whereNull('deleted_at')->max('order');
            $requestedOrder = $eprPortalDto->order;

            if ($requestedOrder === null || $requestedOrder === '' || (int) $requestedOrder <= 0) {
                $targetOrder = $maxOrder + 1;
            } else {
                $targetOrder = max(1, min((int) $requestedOrder, $maxOrder + 1));
            }

            // Shift existing records down if inserted into an existing slot
            $this->shiftOrdersDown($targetOrder);

            $result = $this->eprPortalRepository->create([
                'title' => $eprPortalDto->title,
                'title_hi' => $eprPortalDto->title_hi,
                'link' => $eprPortalDto->link,
                'order' => $targetOrder,
                'is_live' => $eprPortalDto->is_live,
                'created_by' => $eprPortalDto->created_by,
                'updated_by' => $eprPortalDto->updated_by,
            ]);

            $this->resequenceOrders();

            return $result;
        });
    }

    public function update(EprPortalDto $eprPortalDto, $id)
    {
        return DB::transaction(function () use ($eprPortalDto, $id) {
            $this->ensureSynced();

            $portal = EprPortal::lockForUpdate()->findOrFail($id);
            $oldOrder = (int) $portal->order;

            $maxOrder = (int) EprPortal::query()->whereNull('deleted_at')->whereKeyNot($id)->max('order');
            $requestedOrder = $eprPortalDto->order;

            if ($requestedOrder === null || $requestedOrder === '' || (int) $requestedOrder <= 0) {
                $targetOrder = $maxOrder + 1;
            } else {
                $targetOrder = max(1, min((int) $requestedOrder, $maxOrder + 1));
            }

            // Temporarily move current row out of positive range
            $portal->forceFill(['order' => -abs((int) $portal->id)])->save();

            if ($oldOrder > 0) {
                if ($targetOrder < $oldOrder) {
                    $this->shiftOrdersDown($targetOrder, $oldOrder - 1, $portal->id);
                } elseif ($targetOrder > $oldOrder) {
                    $this->shiftOrdersUp($oldOrder + 1, $targetOrder, $portal->id);
                }
            } else {
                $this->shiftOrdersDown($targetOrder, null, $portal->id);
            }

            $updateData = [
                'title' => $eprPortalDto->title,
                'title_hi' => $eprPortalDto->title_hi,
                'link' => $eprPortalDto->link,
                'order' => $targetOrder,
                'is_live' => $eprPortalDto->is_live,
                'is_approved' => 0,
                'is_published' => 0,
                'remarks' => null,
                'publish_remark' => null,
                'updated_by' => $eprPortalDto->updated_by,
            ];

            $result = $this->eprPortalRepository->update($updateData, $id);

            $this->resequenceOrders();

            return $result;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $this->ensureSynced();

            $portal = EprPortal::lockForUpdate()->findOrFail($id);
            $deletedOrder = (int) $portal->order;

            $portal->forceFill(['order' => -abs((int) $portal->id)])->save();
            $deleted = $portal->delete();

            if ($deletedOrder > 0) {
                $this->shiftOrdersUp($deletedOrder + 1);
            }

            $this->resequenceOrders();

            return $deleted;
        });
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->eprPortalRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->eprPortalRepository->update($updateData, $id);
    }
}
