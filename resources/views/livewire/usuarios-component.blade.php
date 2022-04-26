<div>
    @include('livewire.usuarios.usuario-form-component')
    @include('livewire.usuarios.permisos-crear-component')
    @include('livewire.usuarios.permisos-ver-component')
    <button type="button" class="btn btn-primary mb-3 btn-lg" wire:click="add">
        Agregar
    </button>
    @if(count($usuarios) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo Electronico</th>
                <th scope="col">Campo</th>
                <th scope="col">Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $value)
                    <tr class="align-middle">
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td>@if($value->field) {{$value->field->name}}  @endif</td>
                        <td>@if($value->roles[0]) {{$value->roles[0]->name}} @endif</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" wire:click="pView({{ $value->id }})" class="btn btn-outline-primary">P. Ver</button>
                                <button type="button" wire:click="pCreate({{ $value->id }})" class="btn btn-outline-primary">P. Crear</button>
                                <button type="button" wire:click="edit({{ $value->id }})" class="btn btn-outline-primary">Editar</button>
                                <button type="button" wire:click="delete({{ $value->id }})"class="btn btn-outline-primary">Borrar</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $usuarios->links() }}
    </div>
    @else
    <div class="alert alert-primary" role="alert">
        <strong>No se ha agregado ningún registro</strong>
    </div>
    @endif
</div>
