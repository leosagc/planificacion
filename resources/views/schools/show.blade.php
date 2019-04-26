<div class="overlay-header px-4 row">
    <div class="title col">Ficha de datos</div>
    <div class="col-auto close-button">
        <a class="material-icons close-button">close</a>
    </div>
</div>
<div class="overlay-body px-4 pt-3">
    @foreach ($fieldsArr as $section => $fields)
        <span class="font-weight-bold pt-2 d-block">{{$section}}</span>
        <div class="row py-2">
        @foreach($fields as $name => $value)
            <div class="col-5 field-name pr-0">{{$name}}</div>
            <div class="col-7 pl-3">{{$value}}</div>
        @endforeach
        </div>
    @endforeach
</div>
<div class="overlay-footer row mx-0 px-4 py-3">
    <a class="btn btn-light close-button">Cerrar</a>
    <a href="http://escale.minedu.gob.pe/PadronWeb/info/ce?cod_mod={{$fieldsArr['General']['Cod. Modular']}}&anexo=0" target=”_blank” class="btn btn-primary">Ver en Escale</a>
</div>