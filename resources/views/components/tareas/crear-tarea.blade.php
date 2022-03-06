<div class="modal fade" id="create-homework" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
                    Crear tarea
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="mb-3">
                    <label for="date">Fecha</label>
					<input type="date" class="form-control" id="date" placeholder="Ingresa la fecha">
                </div>

				<div class="mb-3">
                    <label for="title">Titulo</label>
                    <input type="text" class="form-control" placeholder="Ingresa el titulo" id="title">
                </div>

				<div class="mb-3">
					<label for="description">Descripción</label>
					<textarea type="text" class="form-control" rows="3" id="description" placeholder="Ingresa la descripción"></textarea>
				</div>

				<div class="mb-3">
					<label for="user_id">Jefe</label>
					<select class="form-control"  aria-label="user" id="user_id">
						<option value="">Seleccione</option>
						@foreach($users as $user)
							<option value="{{$user->user_id}}">{{$user->jefe->name}}</option>
						@endforeach
					</select>
				</div>

				<div class="mb-3">
					<label for="priority" class="form-label">Prioridad</label>
					<select class="form-control"  aria-label="priority" id="priority_id">
						<option value="">Seleccione</option>
						@foreach($priorities as $priority)
							<option value="{{$priority->id}}">{{$priority->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" onclick="guardar()" class="btn btn-primary">
					Guardar
				</button>
			</div>
    	</div>
  	</div>
</div>