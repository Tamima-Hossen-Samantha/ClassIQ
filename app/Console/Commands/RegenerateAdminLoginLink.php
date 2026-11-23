<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class RegenerateAdminLoginLink extends Command
{
    protected $signature = 'admin:link {username}';
    protected $description = 'Regenerate a secret login URL for an existing admin';

    public function handle(): int
    {
        $username = $this->argument('username');

        $admin = Admin::where('username', $username)->first();

        if (! $admin) {
            $this->error('Admin not found.');
            return 1;
        }

        $token = Str::random(40);
        $admin->update(['login_token' => $token]);

        $url = URL::to('/admin/login/' . $token);

        $this->info('New admin login URL:');
        $this->line($url);

        return 0;
    }
}
