<?php

namespace Coderello\FaviconGenerator\Commands;

use Coderello\FaviconGenerator\FaviconManipulator;
use Illuminate\Console\Command;

class FaviconGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favicon:generate {picture?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate favicon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Generating favicon...');

        try {
            app(FaviconManipulator::class)->generate(
                $this->argument('picture')
            );

            $this->info('Favicon has been generated successfully.');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
