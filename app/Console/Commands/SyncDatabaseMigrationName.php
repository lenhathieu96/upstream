<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SyncDatabaseMigrationName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-database-migration-name {--sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // finished migration
        return null;
        
        $dir = dirname(__DIR__, 3) . '/database/migrations/';
        $paths = $this->getDirContents($dir);
        foreach ($paths as $path) {
            $fileName = $this->getFileName($path);
            if ($this->option('sync')) {
                $isExistFileName = DB::table('migrations')->where('migration', $fileName)->exists();
                if (!$isExistFileName) {
                    $this->info('sync file: ' . $fileName);
                    DB::table('migrations')->insert(['migration' => $fileName, 'batch' => 1]);
                }
            } else {
                $this->info('file: ' . $fileName);
            }
        }
        
        $this->info('finished');
    }

    public function getFileName($path) {
        $path = str_replace('.php', '', $path);
        $path = str_replace('/', '|', $path);  // replace for linux
        $path = str_replace('\\', '|', $path);  // replace for windows
        $collection = Str::of($path)->explode('|');
        return $collection->last();
    }

    /**
     * Get all the files and folders in a directory
     */
    public function getDirContents($dir, &$results = array()) {
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $results[] = $path;
            } else if ($value != "." && $value != "..") {
                $this->getDirContents($path, $results);
                $results[] = $path;
            }
        }

        return $results;
    }
}
