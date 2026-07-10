<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['id' => 'CAT-UMUM', 'slug' => 'umum', 'name' => 'Umum', 'icon' => 'tag'],
            ['id' => 'CAT-BUDAYA', 'slug' => 'budaya', 'name' => 'Budaya & Seni Tradisi', 'icon' => 'landmark'],
            ['id' => 'CAT-KULINER', 'slug' => 'kuliner', 'name' => 'Kuliner', 'icon' => 'utensils'],
            ['id' => 'CAT-MUSIK-HIBURAN', 'slug' => 'musik-hiburan', 'name' => 'Musik & Hiburan', 'icon' => 'music'],
            ['id' => 'CAT-PAMERAN-BAZAR', 'slug' => 'pameran-bazar', 'name' => 'Pameran & Bazar UMKM', 'icon' => 'store'],
            ['id' => 'CAT-OLAHRAGA-PETUALANGAN', 'slug' => 'olahraga-petualangan', 'name' => 'Olahraga & Petualangan Alam', 'icon' => 'mountain'],
            ['id' => 'CAT-EDUKASI-WORKSHOP', 'slug' => 'edukasi-workshop', 'name' => 'Edukasi & Workshop', 'icon' => 'graduation-cap'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            );
        }
    }
}
