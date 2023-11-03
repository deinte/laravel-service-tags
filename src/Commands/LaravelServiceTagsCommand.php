<?php

namespace Deinte\LaravelServiceTags\Commands;

use Illuminate\Console\Command;

class LaravelServiceTagsCommand extends Command
{
    public $signature = 'laravel-service-tags';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
