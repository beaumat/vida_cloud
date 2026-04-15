<?php

namespace App\Livewire\PurchaseOrder;

use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\Services\PurchaseOrderServices;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

#[Title('Purchase Order')]
class PurchaseOrderList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $purchaseOrderServices;
    private $locationServices;
    private $userServices;
    public function boot(
        PurchaseOrderServices $purchaseOrderServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->purchaseOrderServices = $purchaseOrderServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->purchaseOrderServices->Delete($id);
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
        $dataList = $this->purchaseOrderServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.purchase-order.purchase-order-list', ['dataList' => $dataList]);
    }
}
