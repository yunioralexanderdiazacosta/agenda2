<div class="modal fade" wire:ignore.self id="p-view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<form autocomplete="off">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					Permiso para ver tareas
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="mb-3">
                    <div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" wire:model="role2" wire:click="changeRole2('Gerente')" id="gerente" value="Gerente">
						<label class="form-check-label" for="administrativo">Gerente</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" wire:model="role2" wire:click="changeRole2('Administrativo')" id="administrativo" value="Administrativo">
						<label class="form-check-label" for="administrativo">Administrativo</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" wire:model="role2" wire:click="changeRole2('Admin')" id="admin" value="Admin">
						<label class="form-check-label" for="administrador">Administrador</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" wire:model="role2" wire:click="changeRole2('JH')" id="jh" value="JH">
						<label class="form-check-label" for="jefehuerto">Jefe de huerto</label>
					</div>
				</div>
                @if(count($users2) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col"></th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo Electronico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users2 as $user)
                        <tr class="align-middle">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" @if($user['checked'] == true) checked @endif wire:click="changePV({{$user['id']}})" value=true id="user">
                                </div>
                            </td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-primary" role="alert">
                    <strong>No se ha agregado ningún registro</strong>
                </div>
                @endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
    	</div>
		</form>
  	</div>
</div>