<div>
    @include('livewire.jefes.jefe-form-component')
    <button type="button" class="btn btn-primary mb-3 btn-lg" wire:click="add">
        Agregar
    </button>
    @if(count($jefes) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo Electronico</th>
                @role('Administrativo|Gerente')<th scope="col">Administrador</th>@endrole
                <th scope="col">Campo</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jefes as $value)
                    <tr class="align-middle">
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        @role('Administrativo|Gerente')<td>{{$value->administrador}} @endrole</td>
                        <td>{{ $value->campo }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" wire:click="edit({{ $value->id }}, {{$value->administrativo_id}}, {{$value->administrador_id}})" class="btn btn-outline-primary">Editar</button>
                                <button type="button" wire:click="delete({{ $value->id }})"class="btn btn-outline-primary">Borrar</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $jefes->links() }}
    </div>
    @else
    <div class="alert alert-primary" role="alert">
        <strong>No se ha agregado ningún registro</strong>
    </div>
    @endif
</div>
