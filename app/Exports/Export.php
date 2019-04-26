<?php

namespace App\Exports;

use App\Exports\SheetsPerType;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Export implements WithMultipleSheets
{
    use Exportable;

    protected $table;

    public function __construct(string $table){
        $this->table = $table;
    }

    public function sheets(): array
    {
        $sheets = [];

        $schoolType = ['1' => 'INICIAL', '2' => 'PRIMARIA', '3'=>'SECUNDARIA', '4'=> 'CEBA', '5'=>'CEBE', '6'=>'CETPRO'];
        $countSheets = count($schoolType);

        foreach($schoolType as $key => $value){
            $sheets[] = new SheetsPerType($this->table, $key, $value);
        }

        return $sheets;

        $excel->setActiveSheetIndex(0);
    }
}
