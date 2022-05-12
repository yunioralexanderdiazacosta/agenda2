<div class="modal fade" id="create-homework" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  	<div class="modal-content">
			<div class="modal-header" style="background-color:green;color:white">
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
                    <input type="text" class="form-control" placeholder="Ingresa el titulo" id="title" maxlength="30">
                </div>

				<div class="mb-3">
					<label for="description">Descripción</label>
					<textarea type="text" class="form-control" rows="3" id="description" placeholder="Ingresa la descripción"></textarea>
				</div>

				<div class="mb-3">
					<label>Para</label><br>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="mi" value=0 onclick="selectI()">
						<label class="form-check-label" for="i">Mi</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="gerente" value=1 onclick="selectGerente()">
						<label class="form-check-label" for="gerente">Gerente</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="administrativo" value=4 onclick="selectAdministrativo()">
						<label class="form-check-label" for="administrativo">Administrativo</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="admin" value=2 onclick="selectAdmin()">
						<label class="form-check-label" for="administrador">Administrador</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="para" id="jh" value=3 onclick="selectJefe()">
						<label class="form-check-label" for="jh">Jefe de huerto</label>
					</div>
				</div>

				<div class="mb-3" id="form-gerente" style="display: none">
					<label for="gerente">Gerente</label>
					<select class="form-control" aria-label="gerente" id="gerente_id">
					</select>
				</div>

				<div class="mb-3" id="form-administrativo" style="display: none">
					<label for="administrativo">Administrativo</label>
					<select class="form-control" aria-label="administrativo" id="administrativo_id">
					</select>
				</div>

				<div class="mb-3" id="form-administrador" style="display: none">
					<label for="user_id">Administrador</label>
					<select class="form-control" aria-label="administrador" id="admin_id">
					</select>
				</div>

				<div class="mb-3" id="form-jefe" style="display: none">
					<label for="user_id">Jefe</label>
					<select class="form-control" aria-label="user" id="user_id">
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