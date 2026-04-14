<?php

namespace App\Livewire\SpendMoney;

use App\Services\LocationServices;
use App\Services\SpendMoneyServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Spend Money')]
class SpendMoneyList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $spendMoneyServices;
    public function boot(SpendMoneyServices $spendMoneyServices, LocationServices $location, UserServices $userServices)
    {
        $this->spendMoneyServices = $spendMoneyServices;
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
        $data = $this->spendMoneyServices->Get($ID);

        if ($data) {
            $this->spendMoneyServices->Delete($ID);
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

        $dataList = $this->spendMoneyServices->Search($this->search, $this->locationid);

        return view('livewire.spend-money.spend-money-list', ['dataList' => $dataList]);

    }
}
