<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\School as School;
use App\Apafa as Apafa;
use App\Conei as Conei;
use App\District as District;
use App\SchoolType as SchoolType;

class ImportController extends Controller
{
    public function index(Request $request)
    {   
        return view('imports.index');
    }

    public function template(Request $request, $table){
        $fileName = '';

        switch ($table) {
            case 'instituciones':
                $fileName = 'formato-instituciones';
                break;
            case 'coneis':
                $fileName = 'formato-coneis';
                break;
            case 'apafas':
                $fileName = 'formato-apafas';
                break;
        }

        return response()->download("files/templates/$fileName.xlsx");
    }

    public function import(Request $request){
        $path = $request->file('import_file')->store('files');
        $table = $request->input('import-type');

        $schoolTypes = ['1', '2', '3', '4', '5', '6'];

        foreach ($schoolTypes as $schoolType) {
            $import = (new FastExcel)->sheet(intval($schoolType))->import(storage_path('app/' . $path), function ($line) use ($schoolType, $table){
                if(array_key_exists('CODIGO', $line) && ctype_space($line['CODIGO']) == FALSE && $line['CODIGO'] !== ''){
                    $schoolColumns = [
                        'name' => 'NOMBRE',
                        'phone' => 'TELEFONO',
                        'district_id' => 'DISTRITO',
                    ];

                    $schoolArr = ['school_type_id' => $schoolType];

                    // Verify existence of columns
                    foreach ($schoolColumns as $attribute => $column) {
                        if(array_key_exists($column, $line)){
                            // Don't push empty columns into SchoolArr
                            if(ctype_space($line[$column]) == FALSE && $line[$column] !== ''){
                                $schoolArr[$attribute] = $line[$column];             
                            }
                        }
                    }

                    // Verify existence of district
                    if(array_key_exists('district_id', $schoolArr)){
                        $schoolArr['district_id'] = null;
                        $district = District::select('id')->where('name', $line['DISTRITO'])->first();
                        if ($district) {
                            $schoolArr['district_id'] = $district->id;
                        }
                    }

                    $school = School::updateOrCreate(['code' => $line['CODIGO']], $schoolArr);

                    if($table == 'instituciones'){
                        $conei = Conei::updateOrCreate(['school_id' => $school->id]);
                        $apafa = Apafa::updateOrCreate(['school_id' => $school->id]);
                    }

                    if($table == 'apafas' || $table == 'coneis'){
                        $minYear = 2011;
                        $maxYear = date("Y", strtotime('+2 years'));
                        $period = '';

                        for($year=$minYear; $year<=$maxYear; $year++){
                            if(array_key_exists($year, $line) == TRUE){
                                if(ctype_space($line[$year]) == FALSE && $line[strval($year)] !== ''){
                                    if($period==''){
                                        $period = $year;
                                    } else {
                                        $period = $period.'-'.$year;
                                    }
                                }
                            }
                        }

                        $columns = [
                            'number' => 'NUMERO',
                            'folder' => 'ANILLADO', 
                            'binder' => 'ARCHIVO'
                        ];

                        $tableArr = ['period' => $period];

                        // Verify existence of columns
                        foreach ($columns as $attribute => $column) {
                            if(array_key_exists($column, $line)){
                                // Don't push empty columns into tableArr
                                if(ctype_space($line[$column]) == FALSE && $line[$column] !== ''){
                                    $tableArr[$attribute] = $line[$column];             
                                }
                            }
                        }
                    }

                    if($table == 'apafas'){
                        $apafa = Apafa::updateOrCreate(['school_id' => $school->id], $tableArr);
                        $conei = Conei::updateOrCreate(['school_id' => $school->id]);
                    }

                    if($table == 'coneis'){
                        $conei = Conei::updateOrCreate(['school_id' => $school->id], $tableArr);
                        $apafa = Apafa::updateOrCreate(['school_id' => $school->id]);
                    }

                    // If a number was not set
                    if($conei->number == null){
                        $lastRecord = Conei::select('number')
                            ->join('school','school.id','=','conei.school_id')
                            ->where('school_type_id', $schoolType)
                            ->whereNotNull('number')
                            ->orderBy('number', 'desc')
                            ->first();

                        if($lastRecord){
                            $lastNumber = $lastRecord->number;
                        } else {
                            $lastNumber = '0';
                        }

                        $nextNumber = intval($lastNumber) + 1; 

                        Conei::updateOrCreate(
                            ['school_id' => $conei->school_id], 
                            ['number' => $nextNumber]
                        );
                    }

                    if($apafa->number == null){
                        $lastRecord = Apafa::select('number')
                            ->join('school','school.id','=','apafa.school_id')
                            ->where('school_type_id', $schoolType)
                            ->orderBy('number', 'desc')
                            ->first();

                        if($lastRecord){
                            $lastNumber = $lastRecord->number;
                        } else {
                            $lastNumber = '0';
                        }

                        $nextNumber = intval($lastNumber) + 1; 

                        Apafa::updateOrCreate(
                            ['school_id' => $apafa->school_id], 
                            ['number' => $nextNumber]
                        );
                    }



                    /* if($table=='instituciones'){
                        $conei = Conei::updateOrCreate(['school_id' => $school->id]);
                        $apafa = Apafa::updateOrCreate(['school_id' => $school->id]);
                                        
                        // If a number was not set
                        if($conei->number == null){
                            $lastRecord = Conei::select('number')
                                ->join('school','school.id','=','conei.school_id')
                                ->where('school_type_id', $schoolType)
                                ->whereNotNull('number')
                                ->orderBy('number', 'desc')
                                ->first();

                            if($lastRecord){
                                $lastNumber = $lastRecord->number;
                            } else {
                                $lastNumber = '0';
                            }

                            $nextNumber = intval($lastNumber) + 1; 

                            Conei::updateOrCreate(
                                ['school_id' => $conei->school_id], 
                                ['number' => $nextNumber]
                            );
                        }

                        if($apafa->number == null){
                            $lastRecord = Apafa::select('number')
                                ->join('school','school.id','=','apafa.school_id')
                                ->where('school_type_id', $schoolType)
                                ->orderBy('number', 'desc')
                                ->first();

                            if($lastRecord){
                                $lastNumber = $lastRecord->number;
                            } else {
                                $lastNumber = '0';
                            }

                            $nextNumber = intval($lastNumber) + 1; 

                            Apafa::updateOrCreate(
                                ['school_id' => $apafa->school_id], 
                                ['number' => $nextNumber]
                            );
                        }
                    } else {
                        $minYear = 2011;
                        $maxYear = date("Y", strtotime('+2 years'));
                        $period = '';

                        for($year=$minYear; $year<=$maxYear; $year++){
                            if(array_key_exists($year, $line) == TRUE){
                                if(ctype_space($line[$year]) == FALSE && $line[strval($year)] !== ''){
                                    if($period==''){
                                        $period = $year;
                                    } else {
                                        $period = $period.'-'.$year;
                                    }
                                }
                            }
                        }

                        $columns = [
                            'number' => 'NUMERO',
                            'folder' => 'ANILLADO', 
                            'document' => 'ARCHIVO'
                        ];

                        $tableArr = ['period' => $period];

                        // Verify existence of columns
                        foreach ($columns as $attribute => $column) {
                            if(array_key_exists($column, $line)){
                                // Don't push empty columns into tableArr
                                if(ctype_space($line[$column]) == FALSE && $line[$column] !== ''){
                                    $tableArr[$attribute] = $line[$column];             
                                }
                            }
                        }

                        if($table == 'apafas'){
                            $apafa = Apafa::updateOrCreate(['school_id' => $school->id], $tableArr);
                            $conei = Conei::updateOrCreate(['school_id' => $school->id]);

                            // If a number was not set
                            if($apafa->number == null){
                                Conei::updateOrCreate(['school_id' => $school->id]);

                                $lastRecord = Apafa::select('number')
                                    ->join('school','school.id','=','apafa.school_id')
                                    ->where('school_type_id', $schoolType)
                                    ->orderBy('number', 'desc')
                                    ->first();
                                $lastNumber = $lastRecord->number;
                                $nextNumber = intval($lastNumber) + 1;

                                Apafa::updateOrCreate(['school_id' => $apafa->school_id], ['number' => $nextNumber]);
                            } else {
                                Conei::updateOrCreate(['school_id' => $school->id], ['number' => $tableArr['number']]);
                            }                       
                        } else {
                            $conei = Conei::updateOrCreate(['school_id' => $school->id], $tableArr);
                            Apafa::updateOrCreate(['school_id' => $school->id]);
                            // If a number was not set
                            if($conei->number == null){
                                $lastRecord = Conei::select('number')
                                    ->join('school','school.id','=','conei.school_id')
                                    ->where('school_type_id', $schoolType)
                                    ->orderBy('number', 'desc')
                                    ->first();
                                $lastNumber = $lastRecord->number;
                                $number = intval($lastNumber) + 1; 
                                Conei::updateOrCreate(
                                    ['school_id' => $conei->school_id], 
                                    ['number' => $number]
                                );
                            }
                        }*/
                    
                }
            });
        }

        $redirectTo = $table.'.index';

        return redirect()->route($redirectTo);
    }
}
