<?php

namespace App\Livewire\CostAdjustment;

use App\Services\CostAdjustmentServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Cost Adjustment')]
class CostAdjustmentList extends Component
{
    use WithPagination;
    private $costAdjustmentServices;
    private $locationServices;
    private $userServices;

    public $locationList = [];
    public $locationid;
    public $search;

    public function boot(CostAdjustmentServices $costAdjustmentServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->costAdjustmentServices = $costAdjustmentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->locationid = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function delete(int $ID)
    {
        try {
            DB::beginTransaction();
            $this->costAdjustmentServices->Delete($ID);
            DB::commit();
            session()->flash('message', 'Successfully deleted');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollBack();
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

        $dataList = $this->costAdjustmentServices->Search($this->search, $this->locationid);
        return view('livewire.cost-adjustment.cost-adjustment-list', ['dataList' => $dataList]);
    }
}
