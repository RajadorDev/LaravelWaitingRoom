<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-key-if-not-exists')]
#[Description('Generates a key')]
class GenerateKeyIfNotExists extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentKey = env('APP_KEY', null);
        if ($currentKey === null || empty($currentKey)) {
            $this->info('Generating app key...');
            exec('php artisan key:generate');
            $this->info("Key generated sucessfully");
        } else {
            $this->info('App key is already set');
        }
    }
}
