<div class="modal fade" id="edit-homework" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
                    @role('Admin')
                        Editar tarea
                    @else
                        Ver tarea
                    @endrole
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <input type="hidden" id="id">
                <div class="mb-3">
                    <label for="date">Fecha</label>
					<input type="date" class="form-control" @role('JH') readonly @endrole id="edit_date" placeholder="Ingresa la fecha">
                </div>

                <div class="mb-3">
                    <label for="title">Titulo</label>
                    <input type="text" class="form-control" placeholder="Ingresa el titulo" @role('JH') readonly @endrole id="edit_title">
                </div>

				<div class="mb-3">
					<label for="description">Descripción</label>
					<textarea class="form-control" @role('JH') readonly @endrole rows="3" id="edit_description" placeholder="Ingresa la descripción"></textarea>
				</div>

                @role('Admin')
				<div class="mb-3">
					<label for="name">Jefe</label>
					<select class="form-control"  aria-label="jefe" id="edit_user_id">
						<option value="">Seleccione</option>
						@foreach($users as $user)
							<option value="{{$user->user_id}}">{{$user->jefe->name}}</option>
						@endforeach
					</select>
				</div>
                @endrole

				<div class="mb-3">
					<label for="priority" class="form-label">Prioridad</label>
					<select class="form-control" aria-label="priority" id="edit_priority_id" @role('JH') style="pointer-events: none;" @endrole>
						<option value="">Seleccione</option>
						@foreach($priorities as $priority)
							<option value="{{$priority->id}}">{{$priority->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="modal-footer">
                @role('Admin')
                <button type="button" onclick="eliminar()" class="btn btn-danger">Eliminar</button>
				@endrole
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				@role('Admin')
                <button type="button" onclick="actualizar()" class="btn btn-primary">
					Guardar
				</button>
                @endrole
			</div>
    	</div>
  	</div>
</div>