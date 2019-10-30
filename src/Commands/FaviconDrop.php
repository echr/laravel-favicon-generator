<?php

namespace Coderello\FaviconGenerator\Commands;

use Coderello\FaviconGenerator\FaviconManipulator;
use Illuminate\Console\Command;

class FaviconDrop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favicon:drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop favicon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Dropping favicon...');

        try {
            app(FaviconManipulator::class)->drop();

            $this->info('Favicon has been dropped successfully.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
