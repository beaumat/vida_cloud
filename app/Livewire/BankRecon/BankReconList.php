<?php

namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bank Reconciliation')]
class BankReconList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 30;
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $bankReconServices;
    public function boot(
        BankReconServices $bankReconServices,
        LocationServices $locationServices,
        UserServices $userServices,
    ) {
        $this->bankReconServices = $bankReconServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($ID)
    {
        try {
            DB::beginTransaction();
            $this->bankReconServices->Delete($ID);
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
        $dataList = $this->bankReconServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.bank-recon.bank-recon-list', ['dataList' => $dataList]);
    }
}
