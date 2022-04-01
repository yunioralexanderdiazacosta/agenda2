<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class GerentesComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password = '', $password_confirmation;
    public $edit = false;

    protected $listeners = [
        'confirmed'
    ];

    protected $validationAttributes = [
        'name' => 'Nombre',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
    ];


    public function render()
    {
        $gerentes = DB::table('admin_users')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)->paginate(5);
        return view('livewire.gerentes-component', compact('gerentes'));
    }

    public function store()
    {
        $admin = Auth::user();
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);
        $user = new  User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->save();
        $user->assignRole('Gerente');
        $admin->users()->attach($user->id);

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

    public function edit($id)
    {
        $this->edit = true;
        $this->emit('show-form');
        $user = User::find($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->user_id = $user->id;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'password' => 'sometimes|string|confirmed|min:8',
        ]);

        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
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
        $admin = Auth::user();
        $admin->users()->detach($this->user_id);
        $user = User::find($this->user_id);
        $user->delete();
        $this->alert('success', 'Eliminado correctamente');
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
}
