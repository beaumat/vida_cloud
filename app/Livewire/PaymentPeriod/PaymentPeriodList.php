<?php

namespace App\Livewire\PaymentPeriod;

use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


#[Title('Payment Period (ACPN)')]
class PaymentPeriodList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $paymentMethods = [];
  
    public $search = '';
    public int $perPage = 30;
    public int $locationid;
    public $locationList = [];

    private $paymentPeriodServices;
    private $locationServices;
    private $userServices;
    public function boot(PaymentPeriodServices $paymentPeriodServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->paymentPeriodServices = $paymentPeriodServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->locationid = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
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

        $dataList = $this->paymentPeriodServices->search($this->search, $this->locationid, 30);
        return view('livewire.payment-period.payment-period-list', ['dataList' => $dataList]);
    }
  
}
