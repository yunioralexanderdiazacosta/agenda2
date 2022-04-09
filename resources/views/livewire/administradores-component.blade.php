<div>
    @include('livewire.administradores.administrador-form-component')
    <button type="button" class="btn btn-primary mb-3 btn-lg" wire:click="add">
        Agregar
    </button>
    @if(count($administradores) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo Electronico</th>
                <th scope="col">Campo</th>
                @role('Gerente')<th scope="col">Administrativo</th>@endrole
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($administradores as $value)
                    <tr class="align-middle">
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->field_name }}</td>
                        @role('Gerente')
                        <td>{{ $value->administrativo }}</td>
                        @endrole
                        <td>
                            <div class="btn-group">
                                <button type="button" wire:click="edit({{ $value->id}}, {{$value->administrativo_id}})" class="btn btn-outline-primary">Editar</button>
                                <button type="button" wire:click="delete({{ $value->id }})"class="btn btn-outline-primary">Borrar</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $administradores->links() }}
    </div>
    @else
    <div class="alert alert-primary" role="alert">
        <strong>No se ha agregado ningún registro</strong>
    </div>
    @endif
</div>