<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default users (per spec §3)
        $users = [
            ['username' => 'admin', 'email' => 'admin@web.wsa', 'password' => 'adminpass', 'role' => 'admin'],
            ['username' => 'user1', 'email' => 'user1@web.wsa', 'password' => 'user1pass', 'role' => 'user'],
            ['username' => 'user2', 'email' => 'user2@web.wsa', 'password' => 'user2pass', 'role' => 'user'],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['username' => $u['username']],
                $u + ['is_banned' => false]
            );
        }

        // Default labels (8 genres per spec §3)
        $labels = ['Pop', 'Rock', 'Hip-Hop', 'Electronic', 'Jazz', 'Classical', 'Chillout', 'Country'];
        foreach ($labels as $name) {
            Label::updateOrCreate(['name' => $name]);
        }
    }
}
