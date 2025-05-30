<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create organizations
        $organizations = [
            [
                'name' => 'Tech Events Co',
                'slug' => 'tech-events',
                'domain' => 'tech-events.localhost',
                'email' => 'admin@tech-events.com',
                'is_active' => true,
            ],
            [
                'name' => 'Cultural Events',
                'slug' => 'cultural-events',
                'domain' => 'cultural-events.localhost',
                'email' => 'admin@cultural-events.com',
                'is_active' => true,
            ],
            [
                'name' => 'Business Meetups',
                'slug' => 'business-meetups',
                'domain' => 'business-meetups.localhost',
                'email' => 'admin@business-meetups.com',
                'is_active' => true,
            ]
        ];

        foreach ($organizations as $orgData) {
            $organization = Organization::create($orgData);

            // Create admin user for each organization
            User::create([
                'name' => 'Admin ' . $organization->name,
                'email' => $orgData['email'],
                'password' => Hash::make('password'),
                'role' => 'admin',
                'organization_id' => $organization->id,
            ]);
        }
    }
}
