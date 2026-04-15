<?php

namespace App\Livewire\User;

use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User List')]
class UserList extends Component
{
    public $users = [];
    public $search = '';
    private $userServices;
    public function boot(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    public function delete($id)
    {
        try {
            $this->userServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->users = $this->userServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
        }
    }
    public function render()
    {   

        $this->users = $this->userServices->Search($this->search);
        return view('livewire.user.user-list');
    }
}
