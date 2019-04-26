<?php

namespace App\Exports;

use App\School;
use App\Apafa;
use App\Conei;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;

use App\Providers\MacroServiceProvider;

use Illuminate\Support\Facades\DB;

class SheetsPerType implements FromCollection, ShouldAutoSize, WithTitle, WithHeadings, WithEvents
{
    use Exportable;

    private $table;
    private $typeKey;
    private $typeValue;
    private $lastRow;
    private $lastColumn;

    public function __construct(string $table, string $typeKey, string $typeValue){
        $this->table = $table;
        $this->typeKey = $typeKey;
        $this->typeValue = $typeValue;
    }

    public function headings(): array
    {
        global $lastColumn;

        $headings = ['CODIGO', 'NOMBRE', 'TELEFONO', 'DISTRITO'];

        if($this->table !== 'Instituciones'){
            array_unshift($headings, "NUMERO");
            array_push($headings, 'ANILLADO', 'ARCHIVO');
            $nextYear = date("Y", strtotime('+1 years'));
            for($i = 2011; $i<=$nextYear; $i++){
                array_push($headings, "$i");
            }
        }

        $countHeadings = count($headings);
        $charIndex = 64 + $countHeadings;
        $lastColumn = chr($charIndex);

        return $headings;
    }

    public function collection()
    {   
        global $lastRow;

        $nextYear = date("Y", strtotime('+1 years'));

        switch ($this->table) {
            case 'Instituciones':
                $schools = School::select('school.code', 'school.name AS school','school.phone','district.name AS district')
                    ->join('district','district.id','=','school.district_id')
                    ->where('school.school_type_id', $this->typeKey)
                    ->orderBy('district.name');
                $lastRow = $schools->count();
                $schools = $schools->get();
                return $schools;
                break;
            case 'Coneis':
                $coneis = Conei::select('number', 'school.code', 'school.name AS school', 'school.phone', 'district.name AS district', 'folder', 'binder', 'period')
                    ->join('school','school.id','=','conei.school_id')
                    ->join('district','district.id','=','school.district_id')
                    ->where('school.school_type_id', $this->typeKey)
                    ->orderBy('district.name');
                $lastRow = $coneis->count();
                $coneis = $coneis->get();
                foreach ($coneis as $key => $conei) {
                    for($i = 2011; $i<=$nextYear; $i++){
                        if(strpos($conei->period, "$i")!==false){
                            $coneis[$key]['year'.$i] = 'X';
                        } else {
                            $coneis[$key]['year'.$i] = '';
                        }
                    }
                    unset($coneis[$key]['period']);
                }
                return $coneis;
                break;
            case 'Apafas':
                $apafas = Apafa::select('number', 'school.code', 'school.name AS school', 'school.phone', 'district.name AS district', 'folder', 'binder', 'period')
                    ->join('school','school.id','=','apafa.school_id')
                    ->join('district','district.id','=','school.district_id')
                    ->where('school.school_type_id', $this->typeKey)
                    ->orderBy('district.name');
                $lastRow = $apafas->count();
                $apafas = $apafas->get();
                foreach ($apafas as $key => $apafa) {
                    for($i = 2011; $i<=$nextYear; $i++){
                        if(strpos($apafa->period, "$i")!==false){
                            $apafas[$key]['year'.$i] = 'X';
                        } else {
                            $apafas[$key]['year'.$i] = '';
                        }
                    }
                    unset($apafas[$key]['period']);
                }
                return $apafas;
                break;
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                global $lastRow;
                global $lastColumn;
                $lastRow = $lastRow + 1;
                $cellA = 'A1';
                $cellB = $lastColumn.$lastRow;
                $range = $cellA.':'.$cellB;
                $rangeHeadings = $cellA.':'.$lastColumn.'1';
                $rangeNoHeadings = 'A2:'.$cellB;
                $event->sheet->setFont($rangeHeadings, 'Calibri', '000000', '11', 'true');
                $event->sheet->setFont($rangeNoHeadings, 'Calibri', '000000', '11', '');
                $event->sheet->setBorders($range,'000000','thin');
                $event->sheet->setAlignment($range,'center','left');
                $event->sheet->setRowHeight('1',$lastRow,20);
                $event->sheet->setBackground($rangeHeadings,'d9d9d9','solid');
                $event->sheet->getDelegate()->setSelectedCells('A1');
            }
        ];
    }

    public function title(): string
    {
        return $this->typeValue;
    }
}
