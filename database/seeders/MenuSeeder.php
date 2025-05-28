<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu; 

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     

        Menu::create([
            'name' => 'Pizza Margherita',
            'description' => 'Classic pizza with tomato and mozzarella.',
            'price' => 12.99,
            'category' => 'main',
            'image_path' => null,
        ]);

        Menu::create([
            'name' => 'Caesar Salad',
            'description' => 'Fresh lettuce with Caesar dressing.',
            'price' => 8.50,
            'category' => 'starter',
            'image_path' => null,
        ]);

        Menu::create([
            'name' => 'Coca-Cola',
            'description' => 'Refreshing soft drink.',
            'price' => 2.00,
            'category' => 'drink',
            'image_path' => null,
        ]);

        Menu::create([
            'name' => 'Chocolate Cake',
            'description' => 'Rich chocolate cake.',
            'price' => 6.00,
            'category' => 'dessert',
            'image_path' => null,
        ]);
    }
}