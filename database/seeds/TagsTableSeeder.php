<?php

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tag::create(['name' => 'Vegetarisch', 'color' => 'green']);
        Tag::create(['name' => 'Vis', 'color' => 'blue']);
        Tag::create(['name' => 'Kip', 'color' => 'grey']);
        Tag::create(['name' => 'Varken', 'color' => 'pink']);
        Tag::create(['name' => 'Koe', 'color' => 'brown']);
    }
}
