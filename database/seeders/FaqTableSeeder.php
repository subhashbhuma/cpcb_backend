<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use DOMXPath;
use Carbon\Carbon;

class FaqTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faqs')->truncate();
        $legacyContent = DB::connection('mysql_legacy_en')
            ->table('cmsm_content_props')
            ->where('content_id', '376')
            ->where('prop_name', 'content_en')
            ->get();

        if (!$legacyContent[0]) {
            $this->command->error("No content found for ID 376.");
            return;
        }

        $this->command->info("Found content. Parsing HTML...");

        // 2. Parse the HTML to extract Questions and Answers
        $faqs = $this->parseHtmlToFaqArray($legacyContent[0]->content);
        $modified_date=$this->parseTimestamp($legacyContent[0]->modified_date);

        $count = count($faqs);
        $this->command->info("Extracted {$count} FAQs. Starting import...");

        // 3. Insert into new database
        foreach ($faqs as $index => $faq) {
            $englishTitle = $faq['title'];
            
            // Assuming you have the helper function from previous steps
            $hindiTitle = function_exists('translateToHindi') 
                ? translateToHindi($englishTitle) 
                : $englishTitle; 

            DB::table('faqs')->insert([
                'question'          => $englishTitle,
                'question_hi'       => $hindiTitle,
                'answer'    => $faq['description'],
                'answer_hi' => $faq['description']??$faq['description_hi'],
                'is_approved'    => 1,
                'is_published'   => 1,
                'created_by'     => 1,
                'updated_by'     => 1,
                'created_at'     => $modified_date,
                'updated_at'     => $modified_date,
            ]);

            $this->command->getOutput()->write('.');
        }

        $this->command->info("\nFAQ Migration completed successfully.");
    }

    /**
     * Parses the HTML string to extract Title (Button) and Description (Panel)
     */
    private function parseHtmlToFaqArray($html)
    {
        $faqs = [];
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);
        $buttons = $xpath->query("//button[contains(@class, 'accordion')]");

        foreach ($buttons as $button) {
            $rawTitle = trim($button->nodeValue);
            $cleanTitle = preg_replace('/^\d+\.\s+/', '', $rawTitle); 
            $description = '';
            $nextNode = $button->nextSibling;
            while ($nextNode) {
                if ($nextNode->nodeName === 'div' && strpos($nextNode->getAttribute('class'), 'panel') !== false) {
                    $description = $this->getInnerHtml($nextNode);
                    break;
                }
                $nextNode = $nextNode->nextSibling;
            }
            if (!empty($cleanTitle)) {
                $faqs[] = [
                    'title' => $cleanTitle,
                    'description' => $description,
                    'description_hi' => translateToHindi($description),
                ];
            }
        }

        libxml_clear_errors();

        return $faqs;
    }

    /**
     * Helper to get inner HTML of a DOMNode (stripping the wrapper <div class="panel">)
     */
    private function getInnerHtml($node)
    {
        $innerHTML = "";
        $children = $node->childNodes;
        foreach ($children as $child) {
            $innerHTML .= $node->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    private function parseTimestamp($date)
    {
        if (!$date || $date == '0000-00-00 00:00:00') {
            return now();
        }
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return now();
        }
    }
}