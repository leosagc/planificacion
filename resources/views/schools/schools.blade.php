<table class="w-100">
        <thead class="table-header">
            <tr class="row mx-0 px-2">
                <th class="col-4 px-3">Nombre de la Institución</th>
                <th class="col px-3">Código</th>
                <th class="col px-3">Teléfono</th>
                <th class="col px-3">Nivel</th>
                <th class="col px-3">Distrito</th>
                <th class="col px-3">Gestión</th>
                <th class="col-auto px-3 invisible">
                    <a class="btn btn-light">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody class="table-body">
            @foreach ($schools as $school)
                <tr class="row mx-0 px-2">
                    <td class="col-4 px-3">
                        @php
                            $wordsToReplace = [' Del ', ' De La ', ' De Los', ' De ', ' Y ', 'S/n'];
                            $replacementWords = [' del ', ' de la ', ' de los', ' de ', ' y ', 'S/N'];
                            $value = ucwords(mb_strtolower($school->name));
                        @endphp
                        <a class="link preventDefault" href="{{route('instituciones.edit', $school->id)}}">{{str_replace($wordsToReplace, $replacementWords, $value)}}</a>
                    </td>
                    <td class="col p-3">
                        <a class="link preventDefault" href="{{route('instituciones.show', $school->code)}}">{{$school->code}}</a>
                    </td>
                    <td class="col px-3">{{$school->phone}}</td>
                    <td class="col px-3">{{$school->schoolType->name}}</td>
                    <td class="col px-3">{{$school->district}}</td>
                    <td class="col px-3">Pública</td>
                    <td class="col-auto px-3">
                        <form action="{{action('SchoolController@destroy', $school->id)}}" method="post">
                            {{csrf_field()}}
                            <input name="_method" type="hidden" value="DELETE">
                            <button class="btn btn-light" type="submit">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </form>
                    </td>
                </tr>
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