<div>
    @include('livewire.gerentes.gerente-form-component')
    <button type="button" class="btn btn-primary mb-3 btn-lg" wire:click="add">
        Agregar
    </button>
    @if(count($gerentes) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo Electronico</th>
                <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gerentes as $value)
                    <tr class="align-middle">
                        <td>{{ $loop->index+1 }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->email }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" wire:click="edit({{ $value->id }})" class="btn btn-outline-primary">Editar</button>
                                <button type="button" wire:click="delete({{ $value->id }})"class="btn btn-outline-primary">Borrar</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $gerentes->links() }}
    </div>
    @else
    <div class="alert alert-primary" role="alert">
        <strong>No se ha agregado ningún registro</strong>
    </div>
    @endif
</div>
