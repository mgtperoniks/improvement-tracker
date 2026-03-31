<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Improvement', 'slug' => 'improvement'],
            ['name' => 'Problem Solving', 'slug' => 'problem'],
            ['name' => 'Maintenance', 'slug' => 'maintenance'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
