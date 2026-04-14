<?php
namespace App\Livewire\PullOut;

use App\Services\LocationServices;
use App\Services\PullOutServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pull Out')]
class PullOutList extends Component
{

    use WithPagination;
    public int $perPage        = 20;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $pullOutServices;
    public function boot(PullOutServices $pullOutServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->pullOutServices  = $pullOutServices;
        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    public function delete(int $ID)
    {
        $this->pullOutServices->Delete($ID);
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
    public function render()
    {
        $dataList = $this->pullOutServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.pull-out.pull-out-list', ['dataList' => $dataList]);
    }
}
