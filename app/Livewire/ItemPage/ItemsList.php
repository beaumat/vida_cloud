<?php

namespace App\Livewire\ItemPage;


use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Item List')]
class ItemsList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];
    public $search = '';
    public  $locationId;
    public $locationList = [];
    public int $perPage = 40;
    private $itemServices;
    private $locationServices;
    private $userServices;
    public function boot(ItemServices $itemServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->itemServices = $itemServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationId = $this->userServices->getLocationDefault();

        $this->locationList = $this->locationServices->getList();
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function delete($id)
    {
        try {
            $this->itemServices->Delete($id);
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
    public function render()
    {

        $items = $this->itemServices->search($this->search, $this->perPage, $this->locationId);

        return view('livewire.item-page.items-list', ['items' => $items]);
    }
}
