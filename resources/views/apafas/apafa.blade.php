<td class="col-4 px-3">
        @php
            $wordsToReplace = [' Del ', ' De La ', ' De Los', ' De ', ' Y ', 'S/n'];
            $replacementWords = [' del ', ' de la ', ' de los', ' de ', ' y ', 'S/N'];
            $value = ucwords(mb_strtolower($school->name));
        @endphp
         <a class="link preventDefault" href="{{route('apafas.edit', $apafa->id)}}">{{str_replace($wordsToReplace, $replacementWords, $value)}}</a>
    </td>
    <td class="col px-3">
            <a class="link preventDefault" href="{{route('instituciones.show', $school->code)}}">{{$school->code}}</a>
    </td>
    <td class="col px-3">{{$apafa->binder}}</td>
    <td class="col px-3">{{$apafa->number}}</td>
    <td class="col px-3">{{$school->schoolType->name}}</td>
    <td class="col px-3">{{$school->district->name}}</td>
    @foreach($years as $year)
        <td class="col-1 px-3 d-flex justify-content-end">
            @if(strpos($apafa->period, $year)!==false)
                <a class="confirm-check editable checked" data-year="{{$year}}" data-row="apafa{{$apafa->id}}"></a>
            @else
                <a class="confirm-check editable" data-year="{{$year}}" data-row="apafa{{$apafa->id}}"></a>
            @endif
        </td>
    @endforeach
    <td class="col d-none">
        <form action="{{route('apafas.update', $apafa->id)}}" method="POST" class="d-none">
            @csrf
            @method('PUT')
            <input type="hidden" name="period" value="{{$apafa->period}}">
        </form>
    </td>