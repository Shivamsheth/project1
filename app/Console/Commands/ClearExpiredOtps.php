<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired OTPs from users table';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredCount = User::where('otp_expires_at', '<', now())
            ->whereNotNull('otp')
            ->update([
                'otp' => null,
                'otp_expires_at' => null,
            ]);

        $this->info("Cleared {$expiredCount} expired OTPs.");

        return Command::SUCCESS;
    }
}
