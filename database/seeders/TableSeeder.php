<?php
namespace Database\Seeders;
use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Table::create(['table_number' => 'T1', 'status' => 'available']);
        Table::create(['table_number' => 'T2', 'status' => 'available']);
        Table::create(['table_number' => 'T3', 'status' => 'occupied']);
    }
}