<div class="modal fade" id="edit-homework" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="title_modal">
                   Editar tarea
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="text-right" id="created_by">
					Tarea dada por: <span id="admin_name"></span>
				</div>
                <input type="hidden" id="id">
				<input type="hidden" id="view">
                <div class="mb-3">
                    <label for="date">Fecha</label>
					<input type="date" class="form-control" @role('JH') readonly @endrole id="edit_date" placeholder="Ingresa la fecha">
                </div>

                <div class="mb-3">
                    <label for="title">Titulo</label>
                    <input type="text" class="form-control" placeholder="Ingresa el titulo" @role('JH') readonly @endrole id="edit_title" maxlength="30">
                </div>

				<div class="mb-3">
					<label for="description">Descripción</label>
					<textarea class="form-control" @role('JH') readonly @endrole rows="3" id="edit_description" placeholder="Ingresa la descripción"></textarea>
				</div>

				<div class="mb-3" id="selected_option">
					<label>Para</label><br>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="edit_para" id="edit_mi" value=0 onclick="selectI()">
						<label class="form-check-label" for="i">Mi</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="edit_para" id="edit_gerente" value=1 onclick="editSelectGerente()">
						<label class="form-check-label" for="gerente">Gerente</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="edit_para" id="edit_administrativo" value=4 onclick="editSelectAdministrativo()">
						<label class="form-check-label" for="edit_administrativo">Administrativo</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="edit_para" id="edit_admin" value=2 onclick="editSelectAdmin()">
						<label class="form-check-label" for="edit_admin">Administrador</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="edit_para" id="edit_jh" value=3 onclick="editSelectJefe()">
						<label class="form-check-label" for="edit_jh">Jefe de huerto</label>
					</div>
				</div>

				<div class="mb-3" id="edit-form-gerente" style="display: none">
					<label for="gerente">Gerente</label>
					<select class="form-control" aria-label="gerente" id="edit_gerente_id">
					</select>
				</div>

				<div class="mb-3" id="edit-form-administrativo" style="display: none">
					<label for="administrativo">Administrativo</label>
					<select class="form-control" aria-label="administrativo" id="edit_administrativo_id">
					</select>
				</div>


				<div class="mb-3" id="edit-form-administrador" style="display: none">
					<label for="administrador">Administrador</label>
					<select class="form-control" aria-label="administrador" id="edit_admin_id">
						<option value="">Seleccione</option>
					</select>
				</div>

				<div class="mb-3" id="edit-form-jefe" style="display: none">
					<label for="jh">Jefe</label>
					<select class="form-control" aria-label="jh" id="edit_user_id">
						<option value="">Seleccione</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="priority" class="form-label">Prioridad</label>
					<select class="form-control" aria-label="priority" id="edit_priority_id" @role('JH') style="pointer-events: none;" @endrole>
						<option value="">Seleccione</option>
						@foreach($priorities as $priority)
							<option value="{{$priority->id}}">{{$priority->name}}</option>
						@endforeach
					</select>
				</div>

				<div class="mb-3">
					<label for="description">Comentario</label>
					<textarea class="form-control"  rows="3" id="edit_comment" placeholder="Ingresa el comentario"></textarea>
				</div>

				<div class="float-right">
					<input id="status" type="checkbox" data-width="125" data-toggle="toggle" data-on="Realizada" data-off="Pendiente" data-onstyle="success" data-offstyle="dark">
				</div>
			</div>
			<div class="modal-footer">
                <button type="button" onclick="eliminar()" id="eliminar" class="btn btn-danger">Eliminar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="button" onclick="actualizar()" id="actualizar" class="btn btn-primary">
					Guardar
				</button>
			</div>
    	</div>
  	</div>
</div>