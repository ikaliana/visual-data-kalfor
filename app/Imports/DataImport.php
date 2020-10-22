<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;

class DataImport implements ToArray
{
    use Importable;

    /**
    * @param Collection $collection
    */
    public function array(Array $array)
    {
        //
    }
}
