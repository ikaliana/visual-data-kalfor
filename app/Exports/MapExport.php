<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MapExport implements FromArray, WithHeadings
{
    protected $mapdata;
    protected $dataheader;

    public function __construct(array $mapdata)
    {
        $this->mapdata = $mapdata;
        $this->dataheader = array_keys($mapdata[0]);
    }

    public function array(): array
    {
        return $this->mapdata;
    }

    public function headings() :array
    {
        return $this->dataheader;
    }
}
