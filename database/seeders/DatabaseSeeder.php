<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Comment;
use Database\Factories\UserFactory;
use Database\Factories\EventFactory;
use Database\Factories\CommentFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $customUser = User::factory()->create([
            'name' => 'AdminFirst AdminLast',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        $users = User::factory()->count(10)->create();

        $events = Event::factory()->count(3)->create([
            'user_id' => $customUser->id,
        ]);

        foreach ($events as $event) {
            $event->attendees()->attach(
                $users->random(rand(0, 5))->pluck('id')->toArray()
            );
        }

        $users->each(function ($user) use ($users) {
            $userEvents = Event::factory()->count(2)->create(['user_id' => $user->id]);

            foreach ($userEvents as $event) {
                $attendees = $users->where('id', '!=', $user->id)->random(rand(0, 2));
                $event->attendees()->attach($attendees->pluck('id')->toArray());
            }
        });

        $adminEventsToJoin = Event::all()->random(rand(2, 4));
        foreach ($adminEventsToJoin as $event) {
            if ($event->user_id !== $customUser->id) {
                $event->attendees()->attach($customUser->id);
            }
        }

        Event::with('attendees')->get()->each(function ($event) {
            foreach ($event->attendees as $user) {
                if (rand(0, 1)) {
                    $randomTimestamp = $this->generateRandomTimestamp($event->date);
                    
                    Comment::factory()->create([
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'created_at' => $randomTimestamp,
                        'updated_at' => $randomTimestamp,
                    ]);
                }
            }
        });
    }

    private function generateRandomTimestamp($eventDate)
    {
        $eventTimestamp = strtotime($eventDate);
        $nowTimestamp = time();
        $randomSeconds = rand($eventTimestamp, $nowTimestamp);
        return date('Y-m-d H:i:s', $randomSeconds);
    }


}