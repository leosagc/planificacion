<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\School as School;
use App\District as District;
use App\SchoolType as SchoolType;
use App\Management as Management;
use App\Apafa as Apafa;
use App\Conei as Conei;
use Goutte\Client;
use Session;
use Redirect;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->schools = new School();
        $this->districts = new District();
        $this->schoolTypes = new SchoolType();
        $this->apafas = new Apafa();
        $this->coneis = new Conei();
    }

    public function index(Request $request)
    {   
        $type = $request->get('type');
        $keywords = $request->get('keywords');
        $district = $request->get('district');
        $period = $request->get('period');
        $perPage = $request->get('perPage');

        $default['perPage'] = $perPage;

        $schools = $this->schools->schools($type, $keywords, $district, $perPage);
        
        if($request->ajax()){
            if($perPage){
                return response()->json(view('schools.schools', [
                    'schools' => $schools[0],
                    'default' => $default,
                    'countResults' => $schools[1],
                    'firstRowIndex' => $schools[2],
                    'lastRowIndex' => $schools[3],
                ])->render());
            }else {
                return response()->json(view('schools.list', [
                    'schools' => $schools[0],
                    'countResults' => $schools[1],
                    'firstRowIndex' => $schools[2],
                    'lastRowIndex' => $schools[3],
                ])->render());
            }
            
        }

        return view('schools.index', [
            'schools' => $schools[0],
            'countResults' => $schools[1],
            'firstRowIndex' => $schools[2],
            'lastRowIndex' => $schools[3],
            'districts' => $this->districts->get(),
            'schoolTypes' => $this->schoolTypes->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->schools->code = $request->input('code');
        $this->schools->name = $request->input('name');
        $this->schools->phone = $request->input('phone');
        $this->schools->district_id = $request->input('district');
        $this->schools->school_type_id = $request->input('type');
        $this->schools->save();

        $this->apafas->school_id = $this->schools->id;
        $this->apafas->save();
        
        $lastRecord = Conei::select('number')
                        ->join('school','school.id','=','conei.school_id')
                        ->where('school_type_id', $request->input('type'))
                        ->whereNotNull('number')
                        ->orderBy('number', 'desc')
                        ->first();
        if($lastRecord){
            $lastNumber = $lastRecord->number;
        } else {
            $lastNumber = '0';
        }
        $nextNumber = intval($lastNumber) + 1; 
        $this->coneis->school_id = $this->schools->id;
        $this->coneis->number = $nextNumber;
        $this->coneis->save();

        $lastRecord = Apafa::select('number')
                        ->join('school','school.id','=','apafa.school_id')
                        ->where('school_type_id', $request->input('type'))
                        ->whereNotNull('number')
                        ->orderBy('number', 'desc')
                        ->first();
        if($lastRecord){
            $lastNumber = $lastRecord->number;
        } else {
            $lastNumber = '0';
        }
        $nextNumber = intval($lastNumber) + 1; 
        $this->apafas->school_id = $this->schools->id;
        $this->apafas->number = $nextNumber;
        $this->apafas->save();

        return Redirect::back()->with('message', 'La institución se registró correctamente');
    }

    public function edit(Request $request, School $institucione)
    {   
        return response()->json(view('schools.edit', [
            'school' => $institucione,
            'districts'=> $this->districts->get(),
            'schoolTypes' => $this->schoolTypes->get(),
        ])->render());
    }

    public function update(Request $request, School $institucione)
    {
        $institucione->code = $request->input('code');
        $institucione->name = $request->input('name');
        $institucione->phone = $request->input('phone');
        $institucione->district_id = $request->input('district_id');
        $institucione->school_type_id = $request->input('type_id');
        $institucione->save();

        return Redirect::back()->with('message', 'Los datos se actualizaron correctamente');
    }

    public function destroy($id)
    {
        $schools = $this->schools::find($id);
        $schools->apafas()->delete();
        $schools->coneis()->delete();
        $schools->delete();

        return Redirect::back()->with('message', 'La institución fue eliminada satisfactoriamente');
    }

    public function show(Client $client, Request $request, $code)
    {   
        /* General */
        $crawler = $client->request('GET',"http://escale.minedu.gob.pe/padron/rest/instituciones/$code/0/?expandLevel=4");
        $codigo = $crawler->filter('codMod')->text();
        $nombre = $crawler->filter('cenEdu')->text();
        $director = $crawler->filter('director')->text();
        $nivel = $crawler->filter('nivelModalidad > valor')->text();        
        $gestion = $crawler->filter('gestion')->text();

        /* Contacto */
            
        $telefono = $crawler->filter('telefono')->text();
        $web = $crawler->filter('pagweb')->text();
        $email = $crawler->filter('email')->text();

        /* Ubicación */
        $direccion = $crawler->filter('dirCen')->text();
        $localidad = $crawler->filter('localidad')->text();
        $poblado = $crawler->filter('cenPob')->text();
        $distrito = $crawler->filter('nombreDistrito')->text();

        $fieldsArr = [
            'General' => [
                'Cod. Modular' => $codigo,
                'Nombre' => $nombre,
                'Nivel / Modalidad' => $nivel,
                'Director' => $director, 
            ],
            'Contacto' => [
                'Telefono' => $telefono,
                'Pagina Web' => $web,
                'Correo electronico' => $email
            ],
            'Ubicación' => [
                'Direccion' => $direccion,
                'Localidad' => $localidad,
                'Centro poblado' => $poblado,
                'Distrito' => $distrito
            ]
        ];

        foreach($fieldsArr as $section => $fields){
            foreach($fields as $name => $value){
                if($value == ''){
                    $fieldsArr[$section][$name] = 'No especificado';
                } else {
                    $wordsToReplace = [' Del ', ' De La ', ' De Los', ' De ', ' Y ', 'S/n'];
                    $replacementWords = [' del ', ' de la ', ' de los', ' de ', ' y ', 'S/N'];
                    $value = ucwords(mb_strtolower($value));
                    $value = str_replace($wordsToReplace, $replacementWords, $value);
                    $fieldsArr[$section][$name] = $value;
                }
            }
        }

        return response()->json(view('schools.show', [
            'fieldsArr' => $fieldsArr
        ])->render());
    }
}
