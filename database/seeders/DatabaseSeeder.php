<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'tuantq',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12341234'),
        ]);



        $leadSources = [
            'Website',
            'Online AD',
            'Twitter',
            'LinkedIn',
            'Webinar',
            'Trade Show',
            'Referral',
        ];
        foreach ($leadSources as $leadSource) {
            \App\Models\LeadSource::create(['name' => $leadSource]);
        }

        \App\Models\Customer::factory()
        ->count(10)
        ->create();
    }
}
