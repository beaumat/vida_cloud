<?php

namespace App\Livewire\Requirement;

use App\Services\RequirementServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Requirements')]
class RequirementList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    private $requirementServices;
    public function boot(RequirementServices $requirementServices)
    {
        $this->requirementServices = $requirementServices;
    }
    public function delete(int $id)
    {
        $this->requirementServices->Delete($id);
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

        $dataList = $this->requirementServices->Search($this->search);
        return view('livewire.requirement.requirement-list', ['dataList' => $dataList]);
    }
}
