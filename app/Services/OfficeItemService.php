<?php

namespace App\Services;

use App\Models\OfficePersonnel;
use App\Models\OfficeProfileActivity;
use App\Models\RegionalDirectorateState;

class OfficeItemService
{
    /**
     * Synchronize personnels for a given office.
     *
     * @param string $officeType ('head_offices' or 'regional_directorates')
     * @param int $officeId
     * @param array $items
     * @param int|null $userId
     * @return void
     */
    public function syncPersonnels(string $officeType, int $officeId, array $items = [], ?int $userId = null): void
    {
        $keptIds = [];
        $userId = $userId ?? auth()->id();

        foreach ($items as $index => $item) {
            $title = isset($item['title']) ? trim(strip_tags(html_entity_decode($item['title']))) : '';
            if ($title === '') {
                continue;
            }

            $titleHi = isset($item['title_hi']) ? trim(strip_tags(html_entity_decode($item['title_hi']))) : null;
            $designation = isset($item['designation']) ? trim(strip_tags(html_entity_decode($item['designation']))) : null;
            $designationHi = isset($item['designation_hi']) ? trim(strip_tags(html_entity_decode($item['designation_hi']))) : null;
            $order = isset($item['order']) && $item['order'] !== '' ? (int) $item['order'] : (int) $index;
            $recordStatus = isset($item['record_status']) ? (int) $item['record_status'] : 1;

            if (!empty($item['id'])) {
                $record = OfficePersonnel::where('id', $item['id'])
                    ->where('office_type', $officeType)
                    ->where('office_id', $officeId)
                    ->first();

                if ($record) {
                    $record->update([
                        'title' => $title,
                        'title_hi' => $titleHi,
                        'designation' => $designation,
                        'designation_hi' => $designationHi,
                        'order' => $order,
                        'record_status' => $recordStatus,
                        'updated_by' => $userId,
                    ]);
                    $keptIds[] = $record->id;
                    continue;
                }
            }

            $newRecord = OfficePersonnel::create([
                'office_type' => $officeType,
                'office_id' => $officeId,
                'title' => $title,
                'title_hi' => $titleHi,
                'designation' => $designation,
                'designation_hi' => $designationHi,
                'order' => $order,
                'record_status' => $recordStatus,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $keptIds[] = $newRecord->id;
        }


        // Delete removed items
        OfficePersonnel::where('office_type', $officeType)
            ->where('office_id', $officeId)
            ->whereNotIn('id', $keptIds)
            ->delete();
    }

    /**
     * Synchronize profile & activities for a given office.
     *
     * @param string $officeType ('head_offices' or 'regional_directorates')
     * @param int $officeId
     * @param array $items
     * @param int|null $userId
     * @return void
     */
    public function syncProfileActivities(string $officeType, int $officeId, array $items = [], ?int $userId = null): void
    {
        $keptIds = [];
        $userId = $userId ?? auth()->id();

        foreach ($items as $index => $item) {
            $title = isset($item['title']) ? trim(strip_tags(html_entity_decode($item['title']))) : '';
            if ($title === '') {
                continue;
            }

            $titleHi = isset($item['title_hi']) ? trim(strip_tags(html_entity_decode($item['title_hi']))) : null;
            $order = isset($item['order']) && $item['order'] !== '' ? (int) $item['order'] : (int) $index;
            $recordStatus = isset($item['record_status']) ? (int) $item['record_status'] : 1;

            if (!empty($item['id'])) {
                $record = OfficeProfileActivity::where('id', $item['id'])
                    ->where('office_type', $officeType)
                    ->where('office_id', $officeId)
                    ->first();

                if ($record) {
                    $record->update([
                        'title' => $title,
                        'title_hi' => $titleHi,
                        'order' => $order,
                        'record_status' => $recordStatus,
                        'updated_by' => $userId,
                    ]);
                    $keptIds[] = $record->id;
                    continue;
                }
            }

            $newRecord = OfficeProfileActivity::create([
                'office_type' => $officeType,
                'office_id' => $officeId,
                'title' => $title,
                'title_hi' => $titleHi,
                'order' => $order,
                'record_status' => $recordStatus,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $keptIds[] = $newRecord->id;
        }

        // Delete removed items
        OfficeProfileActivity::where('office_type', $officeType)
            ->where('office_id', $officeId)
            ->whereNotIn('id', $keptIds)
            ->delete();
    }

    /**
     * Synchronize states for a given regional directorate.
     *
     * @param int $regionalDirectorateId
     * @param array $items
     * @param int|null $userId
     * @return void
     */
    public function syncRegionalDirectorateStates(int $regionalDirectorateId, array $items = [], ?int $userId = null): void
    {
        $keptIds = [];
        $userId = $userId ?? auth()->id();

        foreach ($items as $index => $item) {
            $title = isset($item['title']) ? trim(strip_tags(html_entity_decode($item['title']))) : '';
            if ($title === '') {
                continue;
            }

            $titleHi = isset($item['title_hi']) ? trim(strip_tags(html_entity_decode($item['title_hi']))) : null;
            $order = isset($item['order']) && $item['order'] !== '' ? (int) $item['order'] : (int) $index;
            $recordStatus = isset($item['record_status']) ? (int) $item['record_status'] : 1;

            if (!empty($item['id'])) {
                $record = RegionalDirectorateState::where('id', $item['id'])
                    ->where('regional_directorate_id', $regionalDirectorateId)
                    ->first();

                if ($record) {
                    $record->update([
                        'title' => $title,
                        'title_hi' => $titleHi,
                        'order' => $order,
                        'record_status' => $recordStatus,
                        'updated_by' => $userId,
                    ]);
                    $keptIds[] = $record->id;
                    continue;
                }
            }

            $newRecord = RegionalDirectorateState::create([
                'regional_directorate_id' => $regionalDirectorateId,
                'title' => $title,
                'title_hi' => $titleHi,
                'order' => $order,
                'record_status' => $recordStatus,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);

            $keptIds[] = $newRecord->id;
        }

        // Delete removed items
        RegionalDirectorateState::where('regional_directorate_id', $regionalDirectorateId)
            ->whereNotIn('id', $keptIds)
            ->delete();
    }
}
