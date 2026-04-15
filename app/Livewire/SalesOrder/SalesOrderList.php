<?php
namespace App\Livewire\SalesOrder;

use App\Services\LocationServices;
use App\Services\SalesOrderServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Sales Order')]
class SalesOrderList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $perPage        = 15;
    public int $locationid;
    public $locationList = [];
    private $salesOrderServices;
    private $locationServices;
    private $userServices;

    public function boot(
        SalesOrderServices $salesOrderServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->salesOrderServices = $salesOrderServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->salesOrderServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        $dataList = $this->salesOrderServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.sales-order.sales-order-list', ['dataList' => $dataList]);
    }
}
