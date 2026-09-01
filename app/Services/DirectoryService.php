<?php

namespace App\Services;

use App\DTO\DirectoryDto;
use App\Models\Directory;
use App\Repositories\DirectoryRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DirectoryService
{
    use FileUploadTrait;

    private const TEMP_ORDER_OFFSET = 1000000000;

    private $directoryRepository;

    public function __construct()
    {
        $this->directoryRepository = new DirectoryRepository();
    }

    public function findAll()
    {
        return $this->directoryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directoryRepository->findById($id);
    }

    public function create(DirectoryDto $dto)
    {
        if ($dto->image && !is_string($dto->image)) {
            $file = $this->uploadFile($dto->image, Config::get('file_paths')['DIRECTORY_IMAGE_PATH']);
            $dto->image = $file['file_name'];
        }

        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();
            $orderNo = (int) ($data['order_no'] ?: 1);
            $this->lockGroup($orderNo);

            $targetOrder = $this->resolveInsertShowOrder($orderNo, $data['show_order'] ?? null);

            $this->shiftOrdersDown($orderNo, $targetOrder);

            $data['order_no'] = $orderNo;
            $data['show_order'] = $targetOrder;

            return $this->directoryRepository->create($data);
        });
    }

    public function update(DirectoryDto $dto, $id)
    {
        if ($dto->image && !is_string($dto->image)) {
            $file = $this->uploadFile($dto->image, Config::get('file_paths')['DIRECTORY_IMAGE_PATH']);
            $dto->image = $file['file_name'];
        }

        return DB::transaction(function () use ($dto, $id) {
            $directory = Directory::lockForUpdate()->findOrFail($id);
            $data = $dto->toArray();
            unset($data['created_by']);

            if (!$dto->image) {
                unset($data['image']);
            }

            $oldOrderNo = (int) $directory->order_no;
            $oldShowOrder = (int) $directory->show_order;
            $newOrderNo = (int) ($data['order_no'] ?: 1);

            $this->lockGroup($oldOrderNo);
            if ($newOrderNo !== $oldOrderNo) {
                $this->lockGroup($newOrderNo);
            }

            $targetOrder = $this->resolveUpdateShowOrder(
                $newOrderNo,
                $data['show_order'] ?? null,
                $directory->id,
                $oldOrderNo === $newOrderNo
            );

            // Move the current row out of the positive ordering range before shifting others.
            $directory->forceFill(['show_order' => -abs((int) $directory->id)])->save();

            if ($oldOrderNo === $newOrderNo) {
                if ($targetOrder < $oldShowOrder) {
                    $this->shiftOrdersDown($oldOrderNo, $targetOrder, $oldShowOrder - 1, $directory->id);
                } elseif ($targetOrder > $oldShowOrder) {
                    $this->shiftOrdersUp($oldOrderNo, $oldShowOrder + 1, $targetOrder, $directory->id);
                }
            } else {
                $this->shiftOrdersUp($oldOrderNo, $oldShowOrder + 1, null, $directory->id);
                $this->shiftOrdersDown($newOrderNo, $targetOrder, null, $directory->id);
            }

            // Reset on update
            $data['order_no'] = $newOrderNo;
            $data['show_order'] = $targetOrder;
            $data['is_approved'] = 0;
            $data['is_published'] = 0;
            $data['remarks'] = null;
            $data['publish_remark'] = null;

            return $this->directoryRepository->update($data, $id);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $directory = Directory::lockForUpdate()->findOrFail($id);
            $orderNo = (int) $directory->order_no;
            $showOrder = (int) $directory->show_order;

            $this->lockGroup($orderNo);

            // Preserve the unique key while soft-deleting by moving this row outside active order space.
            $directory->forceFill(['show_order' => -abs((int) $directory->id)])->save();
            $deleted = $directory->delete();

            $this->reorderAfterDelete($orderNo, $showOrder);

            return $deleted;
        });
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        return $this->directoryRepository->update([
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ], $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        return $this->directoryRepository->update([
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ], $id);
    }

    public function findForPublic($limit = null)
    {
        return $this->directoryRepository->findForPublic($limit);
    }

    /**
     * Shift active rows at or after a position down by one slot.
     */
    public function shiftOrdersDown(int $orderNo, int $from, ?int $to = null, ?int $excludeId = null): void
    {
        $query = Directory::query()
            ->where('order_no', $orderNo)
            ->whereNull('deleted_at')
            ->where('show_order', '>=', $from);

        if ($to !== null) {
            $query->where('show_order', '<=', $to);
        }

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $ids = $query->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        Directory::whereIn('id', $ids)->update([
            'show_order' => DB::raw('show_order + ' . self::TEMP_ORDER_OFFSET),
        ]);

        Directory::whereIn('id', $ids)->update([
            'show_order' => DB::raw('show_order - ' . (self::TEMP_ORDER_OFFSET - 1)),
        ]);
    }

    /**
     * Shift active rows in a range up by one slot.
     */
    public function shiftOrdersUp(int $orderNo, int $from, ?int $to = null, ?int $excludeId = null): void
    {
        $query = Directory::query()
            ->where('order_no', $orderNo)
            ->whereNull('deleted_at')
            ->where('show_order', '>=', $from);

        if ($to !== null) {
            $query->where('show_order', '<=', $to);
        }

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        $ids = $query->pluck('id');
        if ($ids->isEmpty()) {
            return;
        }

        Directory::whereIn('id', $ids)->update([
            'show_order' => DB::raw('show_order + ' . self::TEMP_ORDER_OFFSET),
        ]);

        Directory::whereIn('id', $ids)->update([
            'show_order' => DB::raw('show_order - ' . (self::TEMP_ORDER_OFFSET + 1)),
        ]);
    }

    /**
     * Close a gap after a row is removed from an active section.
     */
    public function reorderAfterDelete(int $orderNo, int $deletedShowOrder): void
    {
        $this->shiftOrdersUp($orderNo, $deletedShowOrder + 1);
    }

    private function resolveInsertShowOrder(int $orderNo, mixed $requestedOrder): int
    {
        $max = $this->maxActiveShowOrder($orderNo);

        if ($requestedOrder === null || $requestedOrder === '') {
            return $max + 1;
        }

        return max(1, min((int) $requestedOrder, $max + 1));
    }

    private function resolveUpdateShowOrder(
        int $orderNo,
        mixed $requestedOrder,
        int $directoryId,
        bool $sameGroup
    ): int {
        $max = $this->maxActiveShowOrder($orderNo, $directoryId);

        if ($requestedOrder === null || $requestedOrder === '') {
            return $max + 1;
        }

        $upperLimit = $sameGroup ? $max + 1 : $max + 1;
        return max(1, min((int) $requestedOrder, $upperLimit));
    }

    private function maxActiveShowOrder(int $orderNo, ?int $excludeId = null): int
    {
        $query = Directory::query()
            ->where('order_no', $orderNo)
            ->whereNull('deleted_at');

        if ($excludeId !== null) {
            $query->whereKeyNot($excludeId);
        }

        return (int) $query->max('show_order');
    }

    private function lockGroup(int $orderNo): void
    {
        DB::table('division_orders')
            ->where('order_no', $orderNo)
            ->lockForUpdate()
            ->first();

        Directory::query()
            ->where('order_no', $orderNo)
            ->whereNull('deleted_at')
            ->lockForUpdate()
            ->pluck('id');
    }
}
