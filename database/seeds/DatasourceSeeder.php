<?php

use Illuminate\Database\Seeder;
use App\Models\Datasource;

class DatasourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ds = new Datasource();
        $ds->name = "Global CO2 Emissions";
        $ds->query = "select * from GlobalCO2Emissions";
        $ds->save();
    }
}
