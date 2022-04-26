<?php

namespace App\Http\Livewire;

use App\Models\AdminUser;
use App\Models\Field;
use App\Models\Homework;
use App\Models\HomeworkManage;
use App\Models\HomeworkView;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class UsuariosComponent extends Component
{
    use WithPagination;
    use LivewireAlert;
    protected $paginationTheme = 'bootstrap';
    public $name, $email, $field_id, $role_id, $password = '', $password_confirmation;
    public $edit = false;
    public $role = 'Gerente';
    public $role2 = 'Gerente';
    public $admin_id = '';
    public $admin_id2 = '';

    protected $listeners = [
        'confirmed'
    ];

    protected $validationAttributes = [
        'name'      => 'Nombre',
        'email'     => 'Correo Electrónico',
        'password'  => 'Contraseña',
        'field_id'  => 'Campo',
        'role_id'   => 'Rol'
    ];

    public function render()
    {
        $usuarios   = User::with(['roles', 'field'])->paginate(5);
        $fields     = Field::all();
        $users      = User::role($this->role)->whereNotIn('id', [$this->admin_id])->paginate(10)->through(function($user){
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'checked'   => $this->checkedCreate($user->id)
            ];
        });
        $users2     = User::role($this->role2)->whereNotIn('id', [$this->admin_id2])->paginate(10)->through(function($user){
            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'checked'   => $this->checkedView($user->id)
            ];
        });
        return view('livewire.usuarios-component', compact('usuarios', 'users', 'users2', 'fields'));
    }

    public function store()
    {
        $this->validate([
            'name'      => 'required',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|string|confirmed|min:8',
            'field_id'  => 'required',
            'role_id'   => 'required'
        ]);
        $user           = new User();
        $user->name     = $this->name;
        $user->email    = $this->email;
        $user->password = Hash::make($this->password);
        $user->field_id = $this->field_id;
        $user->save();
        $user->assignRole($this->role_id);

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

    public function pCreate($id)
    {
        $this->role = 'Gerente';
        $this->admin_id = $id;
        $this->emit('show-p-create');
    }

    public function pView($id)
    {
        $this->role2 = 'Gerente';
        $this->admin_id2 = $id;
        $this->emit('show-p-view');
    }

    private function checkedCreate($user_id)
    {
        $check = HomeworkManage::select('id')->where('admin_id', $this->admin_id)->where('user_id', $user_id)->first();
        if($check == null){
            return true;
        }
        return false;
    }

    public function checkedView($user_id)
    {
        $check = HomeworkView::select('id')->where('admin_id', $this->admin_id2)->where('user_id', $user_id)->first();
        if($check == null){
            return false;
        }
        return true;
    }

    public function changeRole($role)
    {
        $this->role = $role;
    }

    public function changeRole2($role)
    {
        $this->role2 = $role;
    }

    public function changePC($user_id)
    {
        $manage = HomeworkManage::where('admin_id', $this->admin_id)->where('user_id', $user_id)->first();
        if($manage == null){
            $create             = new HomeworkManage();
            $create->admin_id   = $this->admin_id;
            $create->user_id    = $user_id;
            $create->save();
        }else{
            $manage->delete();
        }
    }

    public function changePV($user_id)
    {
        $view = HomeworkView::where('admin_id', $this->admin_id2)->where('user_id', $user_id)->first();
        if($view == null){
            $create             = new HomeworkView();
            $create->admin_id   = $this->admin_id2;
            $create->user_id    = $user_id;
            $create->save();
        }else{
            $view->delete();
        }
    }

    public function edit($id)
    {
        $this->edit = true;
        $this->emit('show-form');
        $user           = User::with('roles')->find($id);
        $this->name     = $user->name;
        $this->email    = $user->email;
        $this->user_id  = $user->id;
        $this->field_id = $user->field_id;
        $this->role_id  = $user->roles[0]->name;
    }

    public function update()
    {
        $this->validate([
            'name'      => 'required',
            'email'     => 'required|email|max:255|unique:users,email,' . $this->user_id,
            'password'  => 'sometimes|string|confirmed|min:8',
            'field_id'  => 'required',
            'role_id'   => 'required'
        ]);

        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->email = $this->email;
        if($this->password != '' || $this->password != null)
        {
            $user->password = Hash::make($this->password);
        }
        $user->field_id = $this->field_id;
        $user->save();

        $user->roles()->detach();
        $user->assignRole($this->role_id);
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
        /*
        //eliminar tareas gerente
        Homework::where('user_id', $this->user_id)->delete();
        $admin = Auth::user();
        //eliminar relacion con administrativo
        $admin->users()->detach($this->user_id);
        $gerente_users = AdminUser::where('admin_id', $this->user_id)->get();
        foreach($gerente_users as $value){
            //eliminar tareas administrador
            Homework::where('user_id', $value->user_id)->delete();
            $admin_users = JefeHuertoProfile::where('admin_id', $value->user_id)->get();
            foreach($admin_users as $value2){
                //eliminar tareas JH
                Homework::where('user_id', $value2->user_id)->delete();
                //eliminar perfil JH
                JefeHuertoProfile::where('user_id', $value2->user_id)->delete();
                //eliminar usuario JH
                User::where('id', $value2->user_id)->delete();
            }
            //eliminar usuario administrador
            AdminUser::where('user_id', $value->user_id)->delete();
            User::where('id', $value->user_id)->delete();
        }
        //eliminar relacion gerente-administrador
        $gerente_users = AdminUser::where('admin_id', $this->user_id)->delete();
        //eliminar gerente
        $user = User::find($this->user_id);
        $user->delete();
        $this->alert('success', 'Eliminado correctamente');
        */
    }

    private function resetInputFields()
    {
        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->field_id = '';
        $this->role_id  = '';
    }
}
