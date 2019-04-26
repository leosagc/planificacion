<div class="overlay-header px-4 row">
	<div class="title col">Editar institución</div>
	<div class="col-auto close-button">
		<a class="material-icons close-button">close</a>
	</div>
</div>
<div class="overlay-body px-4">
	<form action="{{route('instituciones.update', $school->id)}}" method="POST">
		@csrf
		@method('PUT')
		<section class="mt-3">
			<div class="form-group pt-2 mt-1">
				<label class="font-weight-bold" for="code">Código Modular</label>
				<input type="text" name="code" class="form-control w-100" id="code" value="{{$school->code}}">
			</div>
			<div class="form-group pt-2 mt-1">
				<label class="font-weight-bold" for="name">Nombre de la institución</label>
				<input type="text" name="name" class="form-control w-100" id="name" value="{{$school->name}}">
            </div>
            <div class="form-group pt-2 mt-1">
				<label class="font-weight-bold" for="phone">Telefono</label>
				<input type="text" name="phone" class="form-control w-100" id="phone" value="{{$school->phone}}">
            </div>
            <div class="form-group pt-2 mt-1">
				<label class="font-weight-bold">Nivel / Modalidad</label>
                <input type="hidden" name="type_id" value="{{$school->schoolType->id}}" id="type_id">
                <div class="dropdown-wrapper" data-input="type_id">
                    <a class="btn btn-light dropdown-toggle">{{$school->schoolType->name}}</a>
                    <div class="dropdown-list">
                        @foreach ($schoolTypes as $schoolType)
			                @if($school->school_type_id == $schoolType->id)
			             		<a class="dropdown-list-item selected" data-value="{{$schoolType->id}}">{{$schoolType->name}}</a>
			                @else
			                   	<a class="dropdown-list-item" data-value="{{$schoolType->id}}">{{$schoolType->name}}</a>
			                @endif
		               @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group pt-2 mt-1">
                <label class="font-weight-bold">Distrito</label>
                <input type="hidden" name="district_id" value="{{$school->district->id}}" id="district_id">
                <div class="dropdown-wrapper" data-input="district_id">
                    <a class="btn btn-light dropdown-toggle">{{$school->district->name}}</a>
                    <div class="dropdown-list">
                        @foreach ($districts as $district)
                            @if($school->district_id == $district->id)
                                <a class="dropdown-list-item selected" data-value="{{$district->id}}">{{$district->name}}</a>
                            @else
                                <a class="dropdown-list-item" data-value="{{$district->id}}">{{$district->name}}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
		</section>
	</form>
</div>
<div class="overlay-footer row mx-0 px-4 py-3 mb-2">
	<a class="btn btn-light close-button">Cancelar</a>
	<input type="submit" class="btn btn-primary submit" value="Actualizar">
</div>