<?php

namespace App\Livewire;

use App\Services\AccountJournalServices;
use App\Services\HemoServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Component;

class TestHemoPage extends Component
{

    public $HEMO_ID;

    public $CODE;
    public $DATE;
    private $hemoServices;
    private $accountJournalServices;
    public function boot(HemoServices $hemoServices, AccountJournalServices $accountJournalServices)
    {
        $this->hemoServices = $hemoServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function mount()
    {
        $data = $this->hemoServices->getExpensesAccountHemo();

        if ($data) {
            $hemoData = $this->hemoServices->get($data->HEMO_ID);
            if ($hemoData) {
                $this->CODE = $hemoData->CODE;
                $this->DATE = $hemoData->DATE;

                $this->hemoServices->getMakeJournal($data->HEMO_ID);
                // check again

                $dataCHECK = $this->hemoServices->getExpensesAccountHemo();
                if ($dataCHECK) {
                    if ($dataCHECK->HEMO_ID == $data->HEMO_ID) {
                        $this->hemoServices->getDelJournal(
                            $dataCHECK->LOCATION_ID,
                            $dataCHECK->JOURNAL_NO,
                            $dataCHECK->SUBSIDIARY_ID,
                            $dataCHECK->OBJECT_ID,
                            $dataCHECK->OBJECT_TYPE,
                            $dataCHECK->OBJECT_DATE,
                            $dataCHECK->ENTRY_TYPE
                        );
                    }
                }


                $this->dispatch('reload_next');
            }
        }
    }
    #[On('reload_next')]
    public function NextPage()
    {
        return Redirect::route('test_hemo_page');
    }
    public function render()
    {
        return view('livewire.test-hemo-page');
    }
}
