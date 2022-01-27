<?php

namespace App\Console\Commands;

use App\Models\CrmFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AddCrmFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make-crm-file-import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $files = collect(json_decode(Storage::disk('local')->get('crm_files.json'), true));
                foreach ($files as $file) {
                    try {
                        $file = collect($file);

                        CrmFile::create([
                            'id'                    => $file->get('id'),
                            'user_id'               => $file->get('user_id'),
                            'uuid'                  => $file->get('uuid'),
                            'size'                  => $file->get('size', 0),
                            'file_type'             => $file->get('file_type'),
                            'publication'           => $file->get('publication'),
                            'file_share'            => $file->get('file_share'),
                            'extension'             => $file->get('extension'),
                            'file_source'           => $file->get('file_source'),
                            'file_original_name'    => $file->get('file_original_name'),
                            'deleted_at'            => $file->get('deleted_at'),
                            'created_at'            => $file->get('created_at'),
                            'updated_at'            => $file->get('updated_at'),
                        ]);
                    } catch (\Throwable $exception) {
                        $this->info($exception->getMessage());
                        $this->info('File not added id:' . $file->get('id'));
                    }
                }
        } catch (\Throwable $exception) {
            $this->info($exception->getMessage());
        }

        return Command::SUCCESS;
    }
}
