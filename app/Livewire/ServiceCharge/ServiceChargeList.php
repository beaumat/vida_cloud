<?php

namespace App\Livewire\ServiceCharge;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Service Charges')]
class ServiceChargeList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 100;
    public string $DATE_FROM;
    public bool $nurseMark = false;
    public string $DATE_NOW;
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $serviceChargeServices;
    private $dateServices;
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices
    ) {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            $this->serviceChargeServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
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
        $this->DATE_NOW  = $this->dateServices->NowDate();
        
        $dataList = $this->serviceChargeServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->DATE_FROM == '' ?  $this->dateServices->NowDate() : $this->DATE_FROM,
            $this->nurseMark
        );

        return view('livewire.service-charge.service-charge-list', ['dataList' => $dataList]);
    }
   
}
