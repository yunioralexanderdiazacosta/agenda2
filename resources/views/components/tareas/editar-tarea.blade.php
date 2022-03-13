<div class="modal fade" id="edit-homework" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title_modal">
                    @hasanyrole('Gerente|Admin')
                        Editar tarea
                    @else
                        Ver tarea
                    @endhasanyrole
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

				@role('Gerente')
					<div class="mb-3">
						<label>Para</label><br>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="edit_para" id="edit_admin" value=true onclick="editSelectAdmin()">
							<label class="form-check-label" for="edit_admin">Administrador</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="radio" name="edit_para" id="edit_jh" value=false onclick="editSelectJefe()">
							<label class="form-check-label" for="edit_jh">Jefe de huerto</label>
						</div>
					</div>
				@endrole

				@role('Gerente')
					<div class="mb-3" id="edit-form-administrador">
						<label for="user_id">Administrador</label>
						<select class="form-control" aria-label="administrador" id="edit_admin_id" onclick="getJefes(this.value, '#edit_user_id')">
							<option value="">Seleccione</option>
							@foreach($administradores as $admin)
								<option value="{{$admin->id}}">{{$admin->name}}</option>
							@endforeach
						</select>
					</div>
				@endrole

				@hasanyrole('Gerente|Admin')
					<div class="mb-3" id="edit-form-jefe" @role('Gerente') style="display: none" @endrole>
						<label for="user_id">Jefe</label>
						<select class="form-control" aria-label="user" id="edit_user_id">
							<option value="">Seleccione</option>
							@role('Admin')
								@foreach($users as $user)
									<option value="{{$user->user_id}}">{{$user->jefe->name}}</option>
								@endforeach
							@endrole
						</select>
					</div>
				@endhasanyrole

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
                @hasanyrole('Gerente|Admin')
                	<button type="button" onclick="eliminar()" id="eliminar" class="btn btn-danger">Eliminar</button>
				@endhasanyrole
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				@hasanyrole('Gerente|Admin')
					<button type="button" onclick="actualizar()" id="actualizar" class="btn btn-primary">
						Guardar
					</button>
                @endhasanyrole
			</div>
    	</div>
  	</div>
</div>