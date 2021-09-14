<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Geotype;

class GeotypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Geotype::create(['name' => 'Point']);
        Geotype::create(['name' => 'LineString']);
        Geotype::create(['name' => 'Polygon']);
        Geotype::create(['name' => 'MultiPoint']);
        Geotype::create(['name' => 'MultiLineString']);
        Geotype::create(['name' => 'MultiPolygon']);
        Geotype::create(['name' => 'GeometryCollection']);
    }
}
