<?php

namespace App\Livewire\ReceiveMoney;

use App\Services\LocationServices;
use App\Services\ReceiveMoneyServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Receive Money')]
class ReceiveMoneyList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $receiveMoneyServices;
    public function boot(ReceiveMoneyServices $receiveMoneyServices, LocationServices $location, UserServices $userServices)
    {
        $this->receiveMoneyServices = $receiveMoneyServices;
        $this->locationServices = $location;
        $this->userServices = $userServices;

    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete(int $ID)
    {
        $data = $this->receiveMoneyServices->Get($ID);

        if ($data) {
            $this->receiveMoneyServices->Delete($ID);
            session()->flash('success', 'Data Deleted Successfully');
        } else {
            session()->flash('error', 'Data Not Found');
        }
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

        $dataList = $this->receiveMoneyServices->Search($this->search, $this->locationid);

        return view('livewire.receive-money.receive-money-list', ['dataList' => $dataList]);
    }
}
