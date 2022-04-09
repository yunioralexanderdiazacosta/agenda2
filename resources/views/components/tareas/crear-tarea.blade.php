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

				@role('Administrativo|Gerente')
				<div class="mb-3">
					<label>Para</label><br>
					@role('Gerente')
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="administrativo" value=1 onclick="selectAdministrativo()">
						<label class="form-check-label" for="inlineRadio1">Administrativo</label>
					</div>
					@endrole
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="admin" value=2 onclick="selectAdmin()">
						<label class="form-check-label" for="inlineRadio1">Administrador</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="jh" value=3 onclick="selectJefe()">
						<label class="form-check-label" for="inlineRadio2">Jefe de huerto</label>
					</div>
				</div>
				@endrole

				@role('Gerente')
				<div class="mb-3" id="form-administrativo" style="display: none">
					<label for="administrativo">Administrativo</label>
					<select class="form-control" aria-label="administrativo" id="administrativo_id" onclick="getAdmins(this.value)">
						<option value="">Seleccione</option>
						@foreach($administrativos as $administrativo)
							<option value="{{$administrativo->id}}">{{$administrativo->name}}</option>
						@endforeach
					</select>
				</div>
				@endrole

				@role('Administrativo|Gerente')
				<div class="mb-3" id="form-administrador" style="display: none">
					<label for="user_id">Administrador</label>
					<select class="form-control" aria-label="administrador" id="admin_id" onclick="getJefes(this.value)">
						<option value="">Seleccione</option>
						@foreach($administradores as $admin)
							<option value="{{$admin->id}}">{{$admin->name}}</option>
						@endforeach
					</select>
				</div>
				@endrole

				<div class="mb-3" id="form-jefe" @role('Administrativo|Gerente') style="display: none" @endrole>
					<label for="user_id">Jefe</label>
					<select class="form-control" aria-label="user" id="user_id">
						<option value="">Seleccione</option>
						@role('Admin')
							@foreach($users as $user)
								<option value="{{$user->user_id}}">{{$user->jefe->name}}</option>
							@endforeach
						@endrole
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