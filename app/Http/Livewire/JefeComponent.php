<?php

namespace App\Http\Livewire;

use App\Models\Field;
use App\Models\JefeHuertoProfile;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class JefeComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $password = '', $password_confirmation, $field_id, $user_id, $admin_id, $administrativo_id = null;
    public $administradores;
    public $edit = false;

    protected $listeners = [
        'confirmed',
        'getAdmins'
    ];

    protected $validationAttributes = [
        'name' => 'Nombre',
        'field_id' => 'Campo',
        'email' => 'Correo Electrónico',
        'password' => 'Contraseña',
        'admin_id' => 'Administrador',
        'administrativo_id' => 'Administrativo'
    ];

    public function render()
    {
        $user = Auth::user();
        $administrativos = [];
        if($user->hasRole('Gerente')){
            $administrativos = DB::table('admin_users')
            ->select('users.id', 'users.name')
            ->join('users', 'users.id', 'admin_users.user_id')
            ->where('admin_id', Auth::user()->id)->get();
            $administrativos_id = $administrativos->pluck('id');
            $jefes = DB::table('jefe_huerto_profiles as jh')
            ->select('u.id', 'u.name', 'u.email', 'a.id as administrador_id', 'a.name as administrador', 'admin.admin_id as administrativo_id', 'f.name as campo')
            ->join('users as u', 'u.id', 'jh.user_id')
            ->join('users as a', 'a.id', 'jh.admin_id')
            ->join('admin_users as admin', 'admin.user_id', 'jh.admin_id')
            ->join('fields as f', 'f.id', 'u.field_id')
            ->whereIn('admin.admin_id', $administrativos_id)
            ->paginate(5);
        }else if($user->hasRole('Admin')){
            $jefes = DB::table('jefe_huerto_profiles as jh')
            ->select('u.id', 'u.name', 'u.email', 'a.id as administrador_id', 'a.name as administrador', 'admin.admin_id as administrativo_id', 'f.name as campo')
            ->join('users as u', 'u.id', 'jh.user_id')
            ->join('users as a', 'a.id', 'jh.admin_id')
            ->join('admin_users as admin', 'admin.user_id', 'jh.admin_id')
            ->join('fields as f', 'f.id', 'u.field_id')
            ->where('jh.admin_id', Auth::user()->id)
            ->paginate(5);
        }else{
            $jefes =  DB::table('jefe_huerto_profiles as jh')
            ->select('u.id', 'u.name', 'u.email', 'a.id as administrador_id', 'a.name as administrador', 'admin.admin_id as administrativo_id', 'f.name as campo')
            ->join('users as u', 'u.id', 'jh.user_id')
            ->join('users as a', 'a.id', 'jh.admin_id')
            ->join('admin_users as admin', 'admin.user_id', 'jh.admin_id')
            ->join('fields as f', 'f.id', 'u.field_id')
            ->where('admin.admin_id', Auth::user()->id)->paginate(5);
            $this->administradores = DB::table('admin_users')
            ->select('a.id', 'a.name')
            ->join('users as a', 'a.id', 'admin_users.user_id')
            ->join('fields', 'fields.id', 'a.field_id')
            ->where('admin_id', Auth::user()->id)->get();
        }
        $fields = Field::all();
        return view('livewire.jefe-component', compact('jefes', 'fields', 'administrativos'));
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->field_id = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_id = '';
        $this->admin_id = '';
        $this->administrativo_id = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'field_id' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'admin_id' => 'sometimes',
            'administrativo_id' => 'sometimes'
        ]);
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->field_id = $this->field_id;
        $user->password = Hash::make($this->password);
        $user->save();
        $user->assignRole('JH');

        JefeHuertoProfile::create([
            'user_id'   => $user->id,
            'admin_id'  => Auth::user()->hasRole('Admin') ? Auth::user()->id : $this->admin_id
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

    public function edit($id, $administrativo_id = null, $administrador_id = null)
    {
        $user = User::find($id);
        $this->edit = true;
        $this->emit('show-form');
        $this->name             = $user->name;
        $this->field_id         = $user->field_id;
        $this->email            = $user->email;
        $this->user_id          = $id;
        $this->admin_id         = $administrador_id;
        $this->administradores  =  collect(DB::table('admin_users')
        ->select('users.id', 'users.name')
        ->join('users', 'users.id', 'admin_users.user_id')
        ->where('admin_id', $administrativo_id)->get()->toArray());
        $this->administrativo_id       = $administrativo_id;
        if(Auth::user()->hasRole('Administrativo|Gerente')){
            $this->admin_id = $administrador_id;
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'field_id' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'password' => 'sometimes|string|confirmed|min:8',
            'admin_id' => 'sometimes',
            'administrativo_id' => 'sometimes'
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

        $jh = JefeHuertoProfile::where('user_id', $user->id)->first();
        if(Auth::user()->hasRole('Administrativo|Gerente')){
            $jh->admin_id = $this->admin_id;
        }
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

    function updatedAdministrativoId($administrativo_id)
    {
        $this->admin_id = '';
        $this->administradores =  collect(DB::table('admin_users')
        ->select('users.id', 'users.name')
        ->join('users', 'users.id', 'admin_users.user_id')
        ->where('admin_id', $administrativo_id)->get()->toArray());
    }
}
