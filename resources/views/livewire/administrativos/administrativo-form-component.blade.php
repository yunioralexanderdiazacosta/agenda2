<div class="modal fade" wire:ignore.self id="show-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form autocomplete="off" wire:submit.prevent="{{$edit ? 'update' : 'store'}}">
	  	<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">
					@if($edit)
						Editar administrativo
					@else
						Agregar administrativo
					@endif
				</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="mb-3">
					<label for="name">Nombre</label>
					<input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name" placeholder="Ingresa el nombre">
					@error('name') <div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label for="email" class="form-label">Correo</label>
					<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email" placeholder="Ingresa el correo electronico">
					@error('email') <div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Contraseña</label>
					<input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model="password" placeholder="Ingresa la contraseña">
					@error('password') <div class="invalid-feedback">{{ $message }}</div>@enderror
				</div>

				<div class="mb-3">
					<label for="password_confirmation" class="form-label">Confirmar contraseña</label>
					<input type="password" class="form-control" id="password_confirmation" wire:model="password_confirmation" placeholder="Ingresa la contraseña">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">
					@if($edit)
						Actualizar
					@else
						Guardar
					@endif
				</button>
			</div>
    	</div>
		</form>
  	</div>
</div>