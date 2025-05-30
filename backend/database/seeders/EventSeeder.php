<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::all();

        foreach ($organizations as $organization) {
            // Create 3-5 events per organization
            $events = [
                [
                    'title' => 'Annual Tech Conference 2025',
                    'description' => 'Join us for the biggest tech conference of the year featuring industry leaders and cutting-edge technologies.',
                    'venue' => 'Convention Center, Main Hall',
                    'date' => Carbon::now()->addDays(30),
                    'price' => 150.00,
                    'max_attendees' => 500,
                    'current_attendees' => 0,
                ],
                [
                    'title' => 'Startup Pitch Night',
                    'description' => 'Watch innovative startups pitch their ideas to investors and industry experts.',
                    'venue' => 'Innovation Hub, Auditorium',
                    'date' => Carbon::now()->addDays(15),
                    'price' => 25.00,
                    'max_attendees' => 200,
                    'current_attendees' => 0,
                ],
                [
                    'title' => 'Networking Mixer',
                    'description' => 'Connect with professionals in your industry over drinks and appetizers.',
                    'venue' => 'Rooftop Lounge, Downtown',
                    'date' => Carbon::now()->addDays(7),
                    'price' => 35.00,
                    'max_attendees' => 100,
                    'current_attendees' => 0,
                ],
                [
                    'title' => 'Workshop: Digital Marketing Trends',
                    'description' => 'Learn the latest digital marketing strategies and tools from industry experts.',
                    'venue' => 'Business Center, Room 201',
                    'date' => Carbon::now()->addDays(45),
                    'price' => 75.00,
                    'max_attendees' => 50,
                    'current_attendees' => 0,
                ],
            ];

            foreach ($events as $eventData) {
                $organization->events()->create($eventData);
            }
        }
    }
}
