<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContactDetailTableSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate to start fresh
        DB::table('contact_details')->truncate();

        $legacyRecord = DB::connection('mysql_legacy_en')
            ->table('cmsm_content_props')
            ->where('prop_name', 'content_en')
            ->where('content_id', 64)
            ->first();

        if (!$legacyRecord) {
            $this->command->error('Legacy record not found.');
            return;
        }

        // Parse English and Hindi HTML
        $englishContacts = $this->parseHtmlContent($legacyRecord->content, 'en');
        $hindiContacts = $this->parseHtmlContent($legacyRecord->hindicontent, 'hi');

        $count = 0;
        foreach ($englishContacts as $index => $en) {
            $hi = $hindiContacts[$index] ?? null;

            DB::table('contact_details')->insert([
                'title'          => $en['name'],
                'title_hi'       => $hi['name'] ?? $en['name'],
                'department'     => $en['dept'],
                'department_hi'  => $hi['dept'] ?? ($en['dept'] ?? null),
                'address'        => $en['address'],
                'address_hi'     => $hi['address'] ?? ($en['address'] ?? null),
                'phone_numbers'  => $en['phone'],
                'email_ids'      => $en['email'],
                'myorder'        => $index + 1,
                'is_approved'    => 1,
                'is_published'   => 1,
                'created_by'     => 1,
                'created_at'     => $this->parseTimestamp($legacyRecord->modified_date),
                'updated_at'     => $this->parseTimestamp($legacyRecord->modified_date),
            ]);
            $count++;
        }

        $this->command->info("Successfully migrated $count contacts with Hindi details and Emails.");
    }

    private function parseHtmlContent($html, $lang)
    {
        if (empty($html)) return [];

        $contacts = [];
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        // Ensure UTF-8 is handled properly for Hindi characters
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        // Look for any div that contains contact info
        $nodes = $xpath->query("//div[contains(@class, 'col-6') or contains(@class, 'co6')]");

        foreach ($nodes as $node) {
            $data = ['name' => '', 'dept' => '', 'address' => '', 'phone' => '', 'email' => ''];

            // 1. Name Extraction
            $h5 = $xpath->query(".//h5", $node);
            if ($h5->length > 0) {
                $data['name'] = trim($h5->item(0)->textContent);
            }

            // 2. Data Extraction via DT/DD pairs
            $dts = $xpath->query(".//dt", $node);
            $dds = $xpath->query(".//dd", $node);

            for ($i = 0; $i < $dts->length; $i++) {
                $label = trim($dts->item($i)->textContent);
                $ddNode = $dds->item($i);
                $value = trim($ddNode->textContent);

                // Handle English OR Hindi Labels
                if (in_array($label, ['Contact Details', 'संपर्क विवरण'])) {
                    $data['dept'] = $value;
                } elseif (in_array($label, ['Postal Address', 'डाक पता'])) {
                    $data['address'] = $value;
                } elseif (in_array($label, ['EPABX No.', 'Phone No.', 'दूरभाष', 'ईपीएबीएक्स नंबर'])) {
                    $data['phone'] = $value;
                } elseif (in_array($label, ['E-mail', 'ई-मेल'])) {
                    $mailAnchor = $xpath->query(".//a", $ddNode);
                    if ($mailAnchor->length > 0) {
                        $emailRaw = $mailAnchor->item(0)->textContent;
                    } else {
                        $emailRaw = $value;
                    }
                    $data['email'] = str_replace(['[dot]', '[at]', ' '], ['.', '@', ''], $emailRaw);
                }
            }

            if (!empty($data['name'])) {
                $contacts[] = $data;
            }
        }
        return $contacts;
    }

    private function parseTimestamp($date)
    {
        try { return $date ? Carbon::parse($date) : now(); } 
        catch (\Exception $e) { return now(); }
    }
}