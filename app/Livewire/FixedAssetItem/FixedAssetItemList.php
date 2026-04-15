<?php
namespace App\Livewire\FixedAssetItem;

use App\Services\DepreciationServices;
use App\Services\FixedAssetItemServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Fixed Asset Items')]
class FixedAssetItemList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString     = ['search' => ['except' => '']];
    public $search             = '';
    public $LOCATION_ID;
    public $locationList = [];
    public int $perPage  = 40;
    private $locationServices;
    private $userServices;
    private $fixedAssetItemServices;
    private $depreciationServices;
    public function boot(LocationServices $locationServices,
        UserServices $userServices,
        FixedAssetItemServices $fixedAssetItemServices,
        DepreciationServices $depreciationServices) {

        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->fixedAssetItemServices = $fixedAssetItemServices;
        $this->depreciationServices   = $depreciationServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID  = $this->userServices->getLocationDefault();
    }
    public function edit(int $id)
    {
        $this->dispatch('open-asset-item', result: ['ID' => $id]);
    }
    public function dep(int $id)
    {
        $this->dispatch('open-depreciation', result: ['ID' => $id]);
    }
    public function delete($id)
    {
        try {

            if ($this->depreciationServices->IsFixedAssetAlreadyDepreciation($id)) {
                session()->flash('error', 'This fixed asset cannot be deleted because it has already been depreciated.');
                return;
            }

            $this->fixedAssetItemServices->Delete($id);
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
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    #[On('refresh-list')]
    public function render()
    {
        $items = $this->fixedAssetItemServices->Search($this->search, $this->LOCATION_ID, 50);
        return view('livewire.fixed-asset-item.fixed-asset-item-list', ['items' => $items]);
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
}
