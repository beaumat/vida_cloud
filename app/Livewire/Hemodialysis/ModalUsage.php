<?php
namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalUsage extends Component
{

    private $hemoServices;
    public $dataList = [];
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public bool $showModal = false;
    public function closeModal()
    {
        $this->showModal = false;
    }

    #[On('usage-modal-open')]
    public function openModal($result)
    {

        $CONTACT_ID  = $result['CONTACT_ID'];
        $DATE        = $result['DATE'];
        $LOCATION_ID = $result['LOCATION_ID'];
        $ITEM_ID     = $result['ITEM_ID'];

        $this->dataList  = $this->hemoServices->UsageHistory($ITEM_ID, $CONTACT_ID, $DATE, $LOCATION_ID);
        $this->showModal = true;
    }

    public function render()
    {

        return view('livewire.hemodialysis.modal-usage');
    }
}
