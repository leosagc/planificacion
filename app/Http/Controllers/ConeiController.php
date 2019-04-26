<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School as School;
use App\District as District;
use App\Conei as Conei;
use App\SchoolType as SchoolType;
use Session;
use Redirect;

class ConeiController extends Controller
{   
    protected $districts;
    protected $schoolTypes;
    protected $years;

    public function __construct()
    {
        $this->middleware('auth');
        $this->schools = new School();
        $this->districts = new District();
        $this->schoolTypes = new SchoolType();
        $this->coneis = new Conei();
        
        $currentYear = date("Y");
        $nextYear = date("Y", strtotime('+1 years'));
        $period = $currentYear.'-'.$nextYear;
        $this->years = explode('-', $period);
    }

    public function index(Request $request)
    {   
        $type = $request->get('type');
        $keywords = $request->get('keywords');
        $district = $request->get('district');
        $perPage = $request->get('perPage');

        $default['perPage'] = $perPage;

        $schools = $this->schools->schools($type, $keywords, $district, $perPage);

        if($request->ajax()){
            return response()->json(view('coneis.coneis', [
                'schools' => $schools[0],
                'countResults' => $schools[1],
                'firstRowIndex' => $schools[2],
                'lastRowIndex' => $schools[3],
                'years' => $this->years,
                'default' => $default,
            ])->render());  
        }

        return view('coneis.index', [
            'schools' => $schools[0],
            'countResults' => $schools[1],
            'firstRowIndex' => $schools[2],
            'lastRowIndex' => $schools[3],
            'years' => $this->years,
            'districts' => $this->districts->get(),
            'schoolTypes' => $this->schoolTypes->get()
        ]);
    }

    public function edit(Request $request, Conei $conei)
    {   
        $school = School::select('*')->where('id', $conei->school_id)->first();

        return response()->json(view('coneis.edit', [
            'conei' => $conei,
            'school' => $school,
            'districts' => $this->districts->get(),
            'schoolTypes' => $this->schoolTypes->get()
        ])->render());
    }

    public function update(Request $request, Conei $conei)
    {   
        $folder = $request->input('folder');
        $binder = $request->input('binder');
        $period = $request->input('period');
        
        if ($request->has('folder')) {
            $conei->folder = $folder;
            $conei->binder = $binder;
        }

        $conei->period = $period;
        $conei->save();

        if($request->ajax()){
            $school = School::select('*')->where('id', $conei->school_id)->first();

            return response()->json(view('coneis.conei', [
                'years' => $this->years,
                'conei' => $conei,
                'school' => $school
            ])->render());
        }

        return Redirect::back()->with('message', 'Los datos se actualizaron correctamente');
    }
}
