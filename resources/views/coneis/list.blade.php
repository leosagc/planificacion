@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="box-header py-3 mb-2">Lista de coneis</div>
<div class="box-body">
    <div class="my-2 py-2 px-3 mx-1">
        <div class="row">
            <div class="search-box col-5">
                <label for="keywords" class="pr-2 ml-1"></label>
                <input type="text" name="keywords" placeholder="Buscar institución" class="py-1" id="keywords">
                <input type="hidden" id="type" value="all">
                <input type="hidden" id="district" value="all">
                <input type="hidden" id="perPage" value="10">
            </div>
            <div class="col-7 d-flex justify-content-end">
                <a href="{{route('export', ['table' => 'Coneis'])}}" class="btn btn-light mx-2">
                    <i class="material-icons download">get_app</i>
                </a>
                <a class="btn btn-light filter-button mx-1">
                    <span class="mr-1">FILTROS</span>
                    <span class="filter-icon"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="data">
        <table class="w-100">
            <thead class="table-header">
                <tr class="row mx-0 px-2">
                    <th class="col-4 px-3">Nombre de la Institución</th>
                    <th class="col px-3">Código</th>
                    <th class="col px-3">Archivo</th>
                    <th class="col px-3">Número</th>
                    <th class="col px-3">Nivel</th>
                    <th class="col px-3">Distrito</th>
                    @foreach($years as $year)
                        <th class="col-1 px-3 d-flex justify-content-end">{{$year}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="table-body">
                @foreach ($schools as $school)
                    @foreach ($school->coneis as $conei)
                        <tr class="row mx-0 px-2" id="conei{{$conei->id}}">
                            <td class="col-4 px-3">
                                @php
                                    $wordsToReplace = [' Del ', ' De La ', ' De Los', ' De ', ' Y ', 'S/n'];
                                    $replacementWords = [' del ', ' de la ', ' de los', ' de ', ' y ', 'S/N'];
                                    $value = ucwords(mb_strtolower($school->name));
                                @endphp
                                 <a class="link preventDefault" href="{{route('coneis.edit', $conei->id)}}">{{str_replace($wordsToReplace, $replacementWords, $value)}}</a>
                            </td>
                            <td class="col px-3">
                                    <a class="link preventDefault" href="{{route('instituciones.show', $school->code)}}">{{$school->code}}</a>
                            </td>
                            <td class="col px-3">{{$conei->binder}}</td>
                            <td class="col px-3">{{$conei->number}}</td>
                            <td class="col px-3">{{$school->schoolType->name}}</td>
                            <td class="col px-3">{{$school->district}}</td>
                            @foreach($years as $year)
                                <td class="col-1 px-3 d-flex justify-content-end">
                                    @if(strpos($conei->period, $year)!==false)
                                        <a class="confirm-check editable checked" data-year="{{$year}}" data-row="conei{{$conei->id}}"></a>
                                    @else
                                        <a class="confirm-check editable" data-year="{{$year}}" data-row="conei{{$conei->id}}"></a>
                                    @endif
                                </td>
                            @endforeach
                            <td class="col d-none">
                                <form action="{{route('coneis.update', $conei->id)}}" method="POST" class="d-none">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="period" value="{{$conei->period}}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <div class="table-footer p-3 m-1">
            <div class="dropdown-wrapper" data-input="perPage">
                <a class="btn btn-light dropdown-toggle">10 Registros</a>
                <div class="dropdown-list">
                    <a class="dropdown-list-item" data-value="5">5 Registros</a>
                    <a class="dropdown-list-item selected" data-value="10">10 Registros</a>
                    <a class="dropdown-list-item" data-value="15">15 Registros</a>
                    <a class="dropdown-list-item" data-value="25">25 Registros</a>
                </div>
            </div>
            <div id="pagination">
                <div class="pagination-info mx-2">
                    <span>{{$firstRowIndex}} - {{$lastRowIndex}}</span>
                    <span class="of">de</span> 
                    <span>{{$countResults}}</span>
                </div>
                <div class="pagination-buttons">
                    {!! $schools->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>