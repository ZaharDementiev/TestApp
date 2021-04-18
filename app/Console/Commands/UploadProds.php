<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProdController;
use App\Models\ProdDoc;
use Illuminate\Console\Command;

class UploadProds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:prods';

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
        $doc = ProdDoc::orderBy('id', 'DESC')->first();
        $jsonString = file_get_contents(base_path($doc->path));

        $data = json_decode($jsonString, true);
        ProdController::uploadData($data, $doc->path);
    }
}
