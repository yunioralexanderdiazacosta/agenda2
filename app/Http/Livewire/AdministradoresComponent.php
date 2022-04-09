<?php

namespace App\Http\Livewire;

use App\Models\AdminUser;
use App\Models\Field;
use App\Models\Homework;
use App\Models\JefeHuertoProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AdministradoresComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password = '', $password_confirmation, $field_id, $administrativo_id;
    public $edit = false;

    protected $listeners = [
        'confirmed'
    ];

    protected $validationAttributes = [
        'name' => 'Nombre',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'field_id' => 'Campo',
        'Administrativo_id' => 'Administrativo'
    ];

    public function render()
    {
        if(Auth::user()->hasRole('Gerente')){

            $administrativos = DB::table('admin_users')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)->get();

            $administrativos_id = DB::table('admin_users')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)->get()->pluck('id');

            $administradores = DB::table('admin_users')
            ->select('a.id', 'a.name', 'a.email', 'fields.name as field_name', 'g.id as administrativo_id', 'g.name as administrativo')
            ->join('users as a', 'a.id', 'admin_users.user_id')
            ->join('users as g', 'g.id', 'admin_users.admin_id')
            ->join('fields', 'fields.id', 'a.field_id')
            ->whereIn('admin_id', $administrativos_id)->paginate(5);
        }else{
            $administrativos = [];
            $administradores = DB::table('admin_users')
            ->select('a.id', 'a.name', 'a.email', 'fields.name as field_name', 'g.id as administrativo_id', 'g.name as administrativo')
            ->join('users as a', 'a.id', 'admin_users.user_id')
            ->join('users as g', 'g.id', 'admin_users.admin_id')
            ->join('fields', 'fields.id', 'a.field_id')
            ->where('admin_id', Auth::user()->id)->paginate(5);
        }
        $fields = Field::all();
        return view('livewire.administradores-component', compact('administradores', 'administrativos', 'fields'));
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->field_id = '';
        $this->administrativo_id = '';
    }

    public function store()
    {
        $admin = Auth::user();
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'field_id' => 'required',
            'administrativo_id' => 'present'
        ]);
        $user = new  User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = Hash::make($this->password);
        $user->field_id = $this->field_id;
        $user->save();
        $user->assignRole('Admin');

        if($admin->hasRole('Gerente')){
            $admin_id = $this->administrativo_id;
        }else{
            $admin_id = $admin->id;
        }
        $gerente = User::find($admin_id);
        $gerente->users()->attach($user->id);

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

    public function edit($id, $administrativo_id = null)
    {
        $user_logged = Auth::user();
        $this->edit = true;
        $admin = User::find($id);
        $this->emit('show-form');
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->field_id = $admin->field_id;
        $this->user_id = $admin->id;
        if($user_logged->hasRole('Gerente')){
            $this->administrativo_id = $administrativo_id;
        }
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
        //Eliminar tareas administrador
        Homework::where('user_id', $this->user_id)->delete();
        //Eliminar relacion con administrativo
        AdminUser::where('user_id', $this->user_id)->delete();
        $admin_users = JefeHuertoProfile::where('admin_id', $this->user_id)->get();
        foreach($admin_users as $value){
            //Eliminar tareas JH
            Homework::where('user_id', $value->user_id)->delete();
            //Eliminar profile JH
            JefeHuertoProfile::where('user_id', $value->user_id)->delete();
            //Eliminar usuario JH
            User::where('id', $value->user_id)->delete();
        }
        //Eliminar usuario administrador
        $user = User::find($this->user_id);
        $user->delete();
        $this->alert('success', 'Eliminado correctamente');
    }
}
