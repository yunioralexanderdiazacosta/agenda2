<?php

namespace App\Http\Livewire;

use App\Models\Field;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdministradoresComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password = '', $password_confirmation, $field_id;
    public $edit = false;

    protected $listeners = [
        'confirmed'
    ];

    protected $validationAttributes = [
        'name' => 'Nombre',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'field_id' => 'Campo'
    ];

    public function render()
    {
        $administradores = User::role('Admin')->paginate(5);
        $fields = Field::all();
        return view('livewire.administradores-component', compact('administradores', 'fields'));
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'field_id' => 'required'
        ]);
        $user = new  User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->field_id = $this->field_id;
        $user->save();
        $user->assignRole('Admin');

        $this->alert('success', 'Registrado correctamente');
        $this->resetInputFields();
        $this->emit('hide-form');
    }

    public function add()
    {
        $this->edit = false;
        $this->resetInputFields();
        $this->emit('show-form');
    }

    public function edit($object)
    {
        $this->edit = true;
        $this->emit('show-form');
        $this->name = $object['name'];
        $this->email = $object['email'];
        $this->field_id = $object['field_id'];
        $this->user_id = $object['id'];
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'field_id' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'password' => 'sometimes|string|confirmed|min:8',
            'field_id' => 'required'
        ]);

        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
        $user->field_id = $this->field_id;
        if($this->password != '' || $this->password != null)
        {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        $this->alert('success', 'Actualizado correctamente');
        $this->resetInputFields();
        $this->emit('hide-form');
    }

    public function delete($id)
    {
        $this->user_id = $id;
        $this->alert('question', '¿Esta seguro que desea remover el registro?', [
            'position' => 'center',
            'toast' => false,
            'timer' => null,
            'showCancelButton' => true,
            'showConfirmButton' => true,
            'cancelButtonText' => 'Cancelar',
            'confirmButtonText' => 'Confirmar',
            'onConfirmed' => 'confirmed',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33'
        ]);
    }

    public function confirmed()
    {
        $user = User::find($this->user_id);
        $user->delete();
        $this->alert('success', 'Eliminado correctamente');
    }
}
