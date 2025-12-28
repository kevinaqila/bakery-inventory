<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Donat',
            'description' => 'Camilan yang terbuat dari campuran tepung terigu, gula, telur, dan mentega, yang kemudian digoreng.',
            'slug' => 'donat',
        ]);

        Category::create([
            'name' => 'Roti Manis',
            'description' => 'Roti dengan rasa manis, cocok untuk sarapan pagi.',
            'slug' => 'roti-manis',
        ]);

        Category::create([
            'name' => 'Roti Tawar',
            'description' => 'Roti putih tawar untuk sandwich dan kebutuhan sehari-hari.',
            'slug' => 'roti-tawar',
        ]);

        Category::create([
            'name' => 'Kue Kering',
            'description' => 'Kue kering yang tahan lama, cocok untuk oleh-oleh.',
            'slug' => 'kue-kering',
        ]);

        Category::create([
            'name' => 'Kue Basah',
            'description' => 'Kue basah segar, harus dikonsumsi dalam waktu singkat.',
            'slug' => 'kue-basah',
        ]);

        Category::create([
            'name' => 'Pastry',
            'description' => 'Produk pastry premium dengan berbagai varian isi.',
            'slug' => 'pastry',
        ]);
    }
}
