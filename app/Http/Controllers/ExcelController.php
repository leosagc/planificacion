<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\Export as Export;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\School as School;
use App\Apafa as Apafa;
use App\Conei as Conei;
use App\District as District;
use App\SchoolType as SchoolType;

class ExcelController extends Controller
{
    public function export($table){
        return (new Export($table))->download($table.'.xlsx');
    }
}
