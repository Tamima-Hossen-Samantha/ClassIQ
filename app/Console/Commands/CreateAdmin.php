<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Create a new admin user and print a secret login URL';

    public function handle(): int
    {
        $username = $this->ask('Enter admin username');
        $password = $this->secret('Enter admin password');

        if (empty($username) || empty($password)) {
            $this->error('Username and password cannot be empty.');
            return 1;
        }

        if (Admin::where('username', $username)->exists()) {
            $this->error('Username already exists!');
            return 1;
        }

        $admin = Admin::create([
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        $token = Str::random(40);
        $admin->update(['login_token' => $token]);

        $url = URL::to('/admin/login/' . $token);

        $this->info('Admin user created successfully!');
        $this->line('Use this URL to log in as admin (one-time link):');
        $this->line($url);

        return 0;
    }
}
