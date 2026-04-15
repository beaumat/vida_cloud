<?php

namespace App\Livewire\BuildAssembly;

use App\Services\BuildAssemblyServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Build Assembly')]
class BuildAssemblyList extends Component
{
    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $buildAssemblyServices;
    private $locationServices;
    private $userServices;
    public function boot(BuildAssemblyServices $buildAssemblyServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->buildAssemblyServices = $buildAssemblyServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function updatedlocationid()
    {

        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            $this->buildAssemblyServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $dataList = $this->buildAssemblyServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.build-assembly.build-assembly-list', ['dataList' => $dataList]);
    }
}
