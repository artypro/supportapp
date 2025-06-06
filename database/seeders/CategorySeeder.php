<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Technical Support', 'slug' => 'technical-support'],
            ['name' => 'Billing', 'slug' => 'billing'],
            ['name' => 'General Inquiry', 'slug' => 'general-inquiry'],
            ['name' => 'Feature Request', 'slug' => 'feature-request'],
            ['name' => 'Account Issues', 'slug' => 'account-issues'],
        ];

        DB::table('categories')->insert($categories);
    }
}