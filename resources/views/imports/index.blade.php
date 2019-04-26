@extends('layouts.app')
@section('content')
@include('banner')
<div class="tabs">
    <a href="/instituciones">Instituciones</a>
    <a href="/apafas">Apafas</a>
    <a href="/coneis">Coneis</a>
    <a href="/importar" class="selected">Importar</a>
</div>
    <div class="content">
        <div class="box py-3">
            <div class="box-header py-3 mb-2">Importar</div>
            <div class="box-body">
                <div class="my-3 py-2 px-3 mx-2">
                    <form action="{{route('import')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col">
                                <div class="border p-4 h-100">
                                    <div class="step text-center pb-3 mb-1">Elige lo que deseas importar</div>
                                    <input type="hidden" name="import-type" id="import-type" value="instituciones">
                                    <div class="dropdown-wrapper w-100" data-input="import-type">
                                        <a class="btn btn-light dropdown-toggle">Instituciones</a>
                                        <div class="dropdown-list">
                                            <a class="dropdown-list-item selected" data-value="instituciones">Instituciones</a>
                                            <a class="dropdown-list-item" data-value="apafas">Apafas</a>
                                            <a class="dropdown-list-item" data-value="coneis">Coneis</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border p-4 h-100 text-center">
                                    <div class="step text-center pb-3 mb-2">Descarga la plantilla correspondiente</div>
                                    <div class="pt-1">
                                        <a href="{{route('template', ['table' => 'instituciones'])}}" class="link">Instituciones</a>
                                        <a href="{{route('template', ['table' => 'apafas'])}}" class="link mx-4">Apafas</a>
                                        <a href="{{route('template', ['table' => 'coneis'])}}" class="link">Coneis</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border p-4 h-100">
                                    <div class="step text-center pb-3 mb-1">Carga tu archivo a importar</div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="import_file" id="customFile">
                                        <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-4 pb-2 mt-3">
                            <div class="col-12 px-4 d-flex justify-content-center">
                                <input class="btn btn-light w-auto" type="submit" value="Importar archivo">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection