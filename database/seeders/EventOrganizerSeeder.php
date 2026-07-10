<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\EventSlot;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventOrganizerSeeder extends Seeder
{
    use WithoutModelEvents;

    private const ADMIN_ID = 'USR-ADMIN-001';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->readCsv('eo_users.csv') as $row) {
            User::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password_hash' => $row['password_hash'],
                    'role' => $row['role'],
                    'status' => $row['status'],
                    'is_verified' => (bool) $row['is_verified'],
                ],
            );
        }

        foreach ($this->readCsv('event_organizers.csv') as $row) {
            EventOrganizer::updateOrCreate(
                ['id' => $row['id']],
                [
                    'user_id' => $row['user_id'],
                    'organization_name' => $row['organization_name'],
                    'contact_person' => $row['contact_person'],
                    'contact_phone' => $row['contact_phone'],
                    'address' => $row['address'],
                ],
            );
        }

        $approvedAt = now();
        $categoryIdsBySlug = Category::query()->pluck('id', 'slug');

        foreach ($this->readCsv('eo_events.csv') as $row) {
            $eventStart = now()->addDays((int) $row['start_offset_days'])->setTime(9, 0);
            $eventEnd = $eventStart->copy()->addDays((int) $row['duration_days'])->setTime(17, 0);
            $registrationDeadline = $eventStart->copy()->subDays((int) $row['reg_deadline_days_before_start']);

            Event::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'organizer_id' => $row['organizer_id'],
                    'category_id' => $categoryIdsBySlug->get($row['category_slug'], $categoryIdsBySlug->get('umum')),
                    'venue_type' => 'INTERNAL',
                    'venue_id' => $row['venue_id'],
                    'external_venue_name' => null,
                    'external_venue_address' => null,
                    'external_venue_capacity' => null,
                    'event_start' => $eventStart,
                    'event_end' => $eventEnd,
                    'registration_deadline' => $registrationDeadline,
                    'slot_size' => (int) $row['slot_size'],
                    'capacity' => (int) $row['capacity'],
                    'banner_url' => $row['banner_filename'] ? '/storage/event-banners/'.$row['banner_filename'] : null,
                    'status' => 'APPROVED',
                    'approved_by' => self::ADMIN_ID,
                    'approved_at' => $approvedAt,
                ],
            );
        }

        foreach ($this->readCsv('event_slots.csv') as $row) {
            $event = Event::find($row['event_id']);

            if (! $event) {
                continue;
            }

            EventSlot::updateOrCreate(
                ['id' => $row['id']],
                [
                    'event_id' => $row['event_id'],
                    'slot_number' => (int) $row['slot_number'],
                    'slot_label' => $row['slot_label'],
                    'date_start' => $event->event_start,
                    'date_end' => $event->event_end,
                    'slot_width' => (float) $row['slot_width'],
                    'slot_long' => (float) $row['slot_long'],
                    'price' => (float) $row['price'],
                    'is_booked' => false,
                ],
            );
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $filename): array
    {
        $path = __DIR__.'/data/'.$filename;
        $handle = fopen($path, 'rb');
        $header = fgetcsv($handle);
        $rows = [];

        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }

        fclose($handle);

        return $rows;
    }
}
