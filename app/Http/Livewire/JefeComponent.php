<?php

namespace App\Http\Livewire;

use App\Models\Field;
use App\Models\JefeHuertoProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class JefeComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password = '', $password_confirmation, $field_id, $user_id;
    public $edit = false;

    protected $listeners = [
        'confirmed'
    ];

    protected $validationAttributes = [
        'name' => 'Nombre',
        'field_id' => 'Campo',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña'
    ];

    public function render()
    {
        $jefes = JefeHuertoProfile::with('jefe')->where('admin_id', Auth::user()->id)->paginate(5);
        $fields = Field::all();
        return view('livewire.jefe-component', compact('jefes', 'fields'));
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->field_id = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'field_id' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8'
        ]);
        $user = new  User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->save();
        $user->assignRole('JH');

        JefeHuertoProfile::create([
            'user_id'   => $user->id,
            'field_id'  => $this->field_id,
            'admin_id'  => Auth::user()->id
        ]);
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
        $this->name = $object['jefe']['name'];
        $this->field_id = $object['field_id'];
        $this->email = $object['jefe']['email'];
        $this->user_id = $object['jefe']['id'];
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'field_id' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'password' => 'sometimes|string|confirmed|min:8'
        ]);

        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
        if($this->password != '' || $this->password != null)
        {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        $jh = JefeHuertoProfile::where('user_id', $user->id)->first();
        $jh->field_id = $this->field_id;
        $jh->save();
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
        $jh   =  JefeHuertoProfile::where('user_id', $this->user_id)->first();
        $jh->delete();
        $user = User::find($this->user_id);
        $user->delete();
        $this->alert('success', 'Eliminado correctamente');
    }
}
