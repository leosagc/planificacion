<div class="overlay-header px-4 row">
        <div class="title col">Editar apafa</div>
        <div class="col-auto close-button">
            <a class="material-icons close-button">close</a>
        </div>
    </div>
    <div class="overlay-body px-4">
        <form action="{{route('apafas.update', $apafa->id)}}" method="POST">
            @csrf
            @method('PUT')
            <section class="mt-3">
                <div class="form-group pt-2 mt-1">
                    <label class="font-weight-bold">Nombre de la institución</label>
                    <input type="text" class="form-control w-100" value="{{$school->name}}" disabled>
                </div>
                <div class="form-group pt-2 mt-1">
                    <label class="font-weight-bold">Número</label>
                    <input type="text" class="form-control w-100" value="{{$apafa->number}}">
                </div>
                <div class="form-group pt-2 mt-1">
                    <label class="font-weight-bold" for="folder">Anillado</label>
                    <input type="text" name="folder" class="form-control w-100" id="folder" value="{{$apafa->folder}}">
                </div>
                <div class="form-group pt-2 mt-1">
                    <label class="font-weight-bold" for="phone">Archivo</label>
                    <input type="text" name="binder" class="form-control w-100" id="binder" value="{{$apafa->binder}}">
                </div>
                <div class="form-group pt-2 mt-1 periods">
                    <label class="font-weight-bold">Periodos</label>
                    <input type="hidden" name="period" class="form-control w-100" value="{{$apafa->period}}" id="period">
                    @php
                        $periods = explode('-', $apafa->period);
                        $years = [];
                        for($i = 2011; $i <= date('Y', strtotime('+1 years')); $i++){
                            array_push($years, "$i");
                        }
                    @endphp
                    <div class="row mx-0 timeline mt-1">
                        @foreach ($years as $year)
                            <div class="col-2 px-0 mb-3 period">
                                <span class="d-block text-center thead py-1">{{$year}}</span>
                                <span class="d-flex justify-content-center w-100 py-2 tbody">
                                    @if (in_array($year, $periods))
                                        <a class="confirm-check checked" data-year="{{$year}}"></a>
                                    @else
                                        <a class="confirm-check" data-year="{{$year}}""></a>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </form>
    </div>
    <div class="overlay-footer row mx-0 px-4 py-3 mb-2">
        <a class="btn btn-light close-button">Cancelar</a>
        <input type="submit" class="btn btn-primary submit" value="Actualizar">
    </div>