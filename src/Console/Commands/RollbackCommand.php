<?php

declare(strict_types=1);

namespace Cortex\Categories\Console\Commands;

use Rinvex\Categories\Console\Commands\RollbackCommand as BaseRollbackCommand;

class RollbackCommand extends BaseRollbackCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:rollback:categories {--force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Cortex Categories Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->call('migrate:reset', ['--path' => 'app/cortex/categories/database/migrations', '--force' => $this->option('force')]);

        parent::handle();
    }
}
