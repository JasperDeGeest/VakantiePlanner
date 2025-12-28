<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetAnnualHours extends Command
{
    protected $signature = 'hours:reset-annual';

    protected $description = 'Resets all user pot hours to null at the start of the year';

    public function handle()
    {
        User::query()->update([
            'totalAHours' => null,
            'totalBHours' => null,
            'totalCHours' => null,
        ]);

        $this->info('Annual hours have been reset successfully.');
    }
}
