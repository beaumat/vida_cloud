<?php

namespace App\Livewire\RolePermissionPage;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Title('Roles & Permission')]
class RolePermissionList extends Component
{
    public $roles = [];
    public $name = null;


    private function getList()
    {
        $this->roles = Role::all();
    }
    public function addRole()
    {

        $this->validate(
            ['name' => 'required|string|min:3|unique:roles,name'],
            [],
            ['name' => 'name']
        );

        try {
            Role::create(['name' => $this->name]);
            $this->name = null;
            session()->flash('success', 'New roles added.');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $this->getList();
        return view('livewire.role-permission-page.role-permission-list');
    }
}
