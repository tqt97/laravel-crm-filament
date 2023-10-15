<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\LeadSource;
use App\Models\PipelineStage;
use App\Models\Tag;
use App\Models\User;
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

        User::factory()->create([
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
            LeadSource::create(['name' => $leadSource]);
        }

        $tags = [
            'Priority',
            'VIP'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

        $pipelineStages = [
            [
                'name' => 'Lead',
                'position' => 1,
                'is_default' => true,
            ],
            [
                'name' => 'Contact Made',
                'position' => 2,
            ],
            [
                'name' => 'Proposal Made',
                'position' => 3,
            ],
            [
                'name' => 'Proposal Rejected',
                'position' => 4,
            ],
            [
                'name' => 'Customer',
                'position' => 5,
            ]
        ];

        foreach ($pipelineStages as $stage) {
            PipelineStage::create($stage);
        }

        $defaultPipelineStage = PipelineStage::where('is_default', true)->first()->id;
        Customer::factory()->count(10)->create([
            'pipeline_stage_id' => $defaultPipelineStage,
        ]);
    }
}
