<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EntryCategory;

class EntryCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Project',
            'Certification',
            'Freelance',
            'Internship',
            'Competition'
        ];

        foreach ($categories as $category) {
            EntryCategory::create(['name' => $category]);
        }
    }
}
