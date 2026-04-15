<?php

namespace App\Livewire\User;

use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserRoles extends Component
{


    public int $id;
    public $assignedRoles = [];
    public string $userName = '';
    public $user;
    public $unassignedRoles = [];
    public function mount($id)
    {
        if ($id) {
            $this->id = $id;
            $this->user = User::find($id);

            if ($this->user) {
                $this->userName = $this->user->name;
                $this->ReloadRole();
                return;
            }
        }
    }
    public function ReloadRole()
    {


        // Get the roles assigned to the user
        $this->assignedRoles = $this->user->roles;
        $allRoles = Role::all();
        $this->unassignedRoles = $allRoles->diff($this->assignedRoles);
    }
    public function addrole($id, $role)
    {

        $this->user->assignRole($role);
        $this->ReloadRole();
    }
    public function deleterole($id, $role)
    {
        $this->user->removeRole($role);
        $this->ReloadRole();
    }
    public function render()
    {
        return view('livewire.user.user-roles');
    }
}
