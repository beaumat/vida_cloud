<?php
namespace App\Livewire;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\PullOutServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Component;

class TestPage extends Component
{
    private $hemoServices;
    private $dateServices;
    public string $data;
    private $pullOutServices;
    public function boot(HemoServices $hemoServices, DateServices $dateServices, PullOutServices $pullOutServices)
    {
        $this->hemoServices    = $hemoServices;
        $this->dateServices    = $dateServices;
        $this->pullOutServices = $pullOutServices;
    }
    public int $COUNT = 0;
    public function mount($id = null)
    {

        $this->COUNT = $this->pullOutServices->getCountPrevousAccount();
        if ($this->COUNT > 0) {
            $data = $this->pullOutServices->getPullOutPreviousAccount();
            if ($data) {
                $this->pullOutServices->setUpdateParameter($data->ID);
                $this->dispatch('refresh-test');
            }

        }
        else{
                dd("END OF PULLOUT");
        }

        // comment temporary
        // if ($id) {
        //     $this->hemoServices->getMakeJournal($id);
        // } else {
        //     $transDate = $this->dateServices->BackDate();

        //     if ($id == null) {
        //         $itemData = $this->hemoServices->CallOutItemUnPosted($transDate);

        //         foreach ($itemData as $list) {
        //             $this->updateUnpostedItemOnly($list->HEMO_ID, $itemData->count());
        //             return;
        //         }

        //     }
        // }
    }

    private function updateUnpostedItemOnly(int $HEMO_ID, int $COUNT = 0)
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->getMakeJournal($HEMO_ID);
            $this->hemoServices->makeItemInventory($HEMO_ID);
            DB::commit();

            $data = "Successs " . 'HEMO_ID:' . $HEMO_ID . ' - COUNT:' . $COUNT;
            session()->flash('message', $data);
            $this->dispatch('refresh-test');

        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage() . 'HEMO_ID:' . $HEMO_ID . ' - COUNT:' . $COUNT);
        }

    }
    #[On('refresh-test')]
    public function refreshData()
    {

        return Redirect::route('testpage');
    }
    public function render()
    {
        return view('livewire.test-page');
    }
}
