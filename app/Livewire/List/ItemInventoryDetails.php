<?php
namespace App\Livewire\List;

use App\Exports\InventoryReportExport;
use App\Services\DateServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemRecountServers;
use App\Services\ItemServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Item Inventory Details')]
class ItemInventoryDetails extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public string $ITEM_NAME;
    public string $DATE;
    public string $DATE_ON;
    public int $ITEM_ID;
    public int $LOCATION_ID;
    private $itemInventoryServices;
    private $itemServices;
    private $locationServices;
    private $dateServices;

    public bool $show = false;
    private $itemRecountServers;
    public function boot(ItemInventoryServices $itemInventoryServices, ItemServices $itemServices, LocationServices $locationServices, DateServices $dateServices, ItemRecountServers $itemRecountServers)
    {
        $this->itemInventoryServices = $itemInventoryServices;
        $this->itemServices          = $itemServices;
        $this->locationServices      = $locationServices;
        $this->dateServices          = $dateServices;
        $this->itemRecountServers    = $itemRecountServers;
    }
    public function mount($id = null, $locationid = null)
    {

        if (is_numeric($id) && is_numeric($locationid)) {

            if ($this->itemServices->isInventoryItem($id)) {

                if ($this->locationServices->IsExist($locationid)) {

                    $this->ITEM_ID     = $id;
                    $data              = $this->itemServices->get($this->ITEM_ID);
                    $this->ITEM_NAME   = $data->DESCRIPTION;
                    $this->DATE        = $this->dateServices->NowDate();
                    $this->DATE_ON     = $this->dateServices->NowDate();
                    $this->LOCATION_ID = $locationid;
                    $this->show        = true;
                    // $this->dispatch('active-scroll');
                    return;
                }

            }
        }
    }
    #[On('active-scroll')]
    public function scrollDown()
    {
        $this->dispatch('scrollToBottom');
    }
    public function reCountItem()
    {
        $this->itemRecountServers->Insert($this->ITEM_ID, $this->LOCATION_ID, $this->DATE_ON);
        session()->flash('message', 'Successfully recounted please refresh the page');
    }
    public function refreshOnHand(int $SOURCE_ID, int $SOURCE_TYPE, int $LOCATION_ID)
    {

        $this->itemInventoryServices->RecomputedEndingOnhand($SOURCE_ID, $SOURCE_TYPE, $LOCATION_ID);
        session()->flash('message', 'Successfully fixed');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function exportData()
    {
        $dataName = str_replace(' ', '', $this->ITEM_NAME);
        $dataName = str_replace('/', '', $dataName);
        $newData  = $this->itemInventoryServices->getDetails($this->ITEM_ID, $this->LOCATION_ID, $this->DATE);

        return Excel::download(new InventoryReportExport(
            $newData
        ), "Inventory-Ending-$dataName.xlsx");
    }
    public function render()
    {
        $dataList = $this->itemInventoryServices->getDetails2($this->ITEM_ID, $this->LOCATION_ID, $this->DATE);

        return view('livewire.list.item-inventory-details', ['dataList' => $dataList]);

    }
}
