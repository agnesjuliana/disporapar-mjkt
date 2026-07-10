<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class CommunityUserSeeder extends Seeder
{
    use WithoutModelEvents;

    private const ADMIN_ID = 'USR-ADMIN-001';

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIdsBySlug = Category::query()->pluck('id', 'slug');

        foreach ($this->readCsv('masyarakat_users.csv') as $row) {
            $user = User::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password_hash' => $row['password_hash'],
                    'role' => 'MASYARAKAT',
                    'status' => $row['status'],
                    'is_verified' => (bool) $row['is_verified'],
                ],
            );

            $user->interestCategories()->sync(
                $this->resolveCategoryIds($row['interest_slugs'], $categoryIdsBySlug),
            );
        }

        $approvedAt = now();

        foreach ($this->readCsv('tenant_users.csv') as $row) {
            $user = User::updateOrCreate(
                ['id' => $row['id']],
                [
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password_hash' => $row['password_hash'],
                    'role' => 'TENANT',
                    'status' => 'ACTIVE',
                    'is_verified' => true,
                ],
            );

            Tenant::updateOrCreate(
                ['id' => $row['tenant_id']],
                [
                    'user_id' => $user->id,
                    'organization_name' => $row['org_name'],
                    'contact_person' => $row['contact_person'],
                    'contact_phone' => $row['contact_phone'],
                    'address' => $row['address'],
                    'registration_status' => 'APPROVED',
                    'registered_at' => $approvedAt,
                    'approved_by' => self::ADMIN_ID,
                    'approved_at' => $approvedAt,
                ],
            );

            $user->interestCategories()->sync(
                $this->resolveCategoryIds($row['interest_slugs'], $categoryIdsBySlug),
            );
        }
    }

    /**
     * @param  Collection<string, string>  $categoryIdsBySlug
     * @return array<int, string>
     */
    private function resolveCategoryIds(string $interestSlugs, Collection $categoryIdsBySlug): array
    {
        return collect(explode(',', $interestSlugs))
            ->map(fn (string $slug) => $categoryIdsBySlug->get(trim($slug)))
            ->filter()
            ->values()
            ->all();
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
