@extends('layouts.app')
@section('content')
@include('banner')
<div class="overlay"></div>
<div class="filters">
    <div class="overlay-header px-4 row">
        <div class="title col">Filtros</div>
        <div class="col-auto close-button">
            <a class="material-icons close-button">close</a>
        </div>
    </div>
    <div class="overlay-body px-4">
        <section class="mt-3">
            <div class="form-group pt-2 mt-1">
                <label class="font-weight-bold">Nivel / Modalidad</label>
                <div class="dropdown-wrapper" data-input="type">
                    <a class="btn btn-light dropdown-toggle">Todos</a>
                    <div class="dropdown-list">
                        <a class="dropdown-list-item selected" data-value="all">Todos</a>
                        @foreach ($schoolTypes as $schoolType)
                            <a class="dropdown-list-item" data-value="{{$schoolType->id}}">{{$schoolType->name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group pt-2 mt-1">
                <label class="font-weight-bold">Distrito</label>
                <div class="dropdown-wrapper" data-input="district">
                    <a class="btn btn-light dropdown-toggle">Todos</a>
                    <div class="dropdown-list">
                        <a class="dropdown-list-item selected" data-value="all">Todos</a>
                        @foreach ($districts as $district)
                            <a class="dropdown-list-item" data-value="{{$district->id}}">{{$district->name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>   
        </section>
    </div>
    <div class="overlay-footer row mx-0 px-4 py-3 mb-2">
        <a class="btn btn-light close-button">Cancelar</a>
        <input type="submit" class="btn btn-primary submit-filters" value="Aplicar Filtros">
    </div>
</div>
<div class="tabs">
    <a href="/instituciones">Instituciones</a>
    <a href="/apafas" class="selected">Apafas</a>
    <a href="/coneis">Coneis</a>
    <a href="/importar">Importar</a>
</div>
    <div class="content">
        <div class="box py-3">
            @include('apafas.list')
        </div>
    </div>
@endsection