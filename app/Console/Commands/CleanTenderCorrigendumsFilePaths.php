<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanTenderCorrigendumsFilePaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-tender-corrigendums-file-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes directory prefixes from tender corrigendum file names';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting path cleanup...');

        // Fetch only records that contain the slash prefix to save memory
        $records = DB::table('tender_corrigendums')
            ->where('file_name', 'like', '%/%')
            ->orWhere('file_name_hi', 'like', '%/%')
            ->get();

        if ($records->isEmpty()) {
            $this->info('No paths need cleaning.');
            return;
        }

        $bar = $this->output->createProgressBar(count($records));

        foreach ($records as $record) {
            // PHP's basename() function extracts just the filename from a path
            $cleanFile = basename($record->file_name);
            $cleanFileHi = basename($record->file_name_hi);

            DB::table('tender_corrigendums')
                ->where('id', $record->id)
                ->update([
                    'file_name' => $cleanFile,
                    'file_name_hi' => $cleanFileHi
                ]);

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nPaths cleaned successfully!");
    }
}
