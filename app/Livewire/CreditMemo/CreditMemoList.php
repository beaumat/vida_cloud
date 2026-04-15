<?php

namespace App\Livewire\CreditMemo;

use App\Services\CreditMemoServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Credit Memo')]
class CreditMemoList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public int $locationid;
    public $locationList = [];
    private $creditMemoServices;
    private $locationServices;
    private $userServices;

    public function boot(
        CreditMemoServices $creditMemoServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->creditMemoServices = $creditMemoServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
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
    public function delete($id)
    {
        try {
            $this->creditMemoServices->Delete($id);
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
    public function render()
    {
        $dataList = $this->creditMemoServices->Search($this->search, $this->locationid, $this->perPage);
        
        return view('livewire.credit-memo.credit-memo-list', ['dataList' => $dataList]);
    }
}
