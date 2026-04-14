<?php

namespace App\Livewire\GeneralJournal;

use App\Services\ContactServices;
use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("General Journal Print")]
class GeneralJournalPrint extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public string $CONTACT_NAME;
    public int $LOCATION_ID;
    public string $LOCATION_NAME;
    public string $NOTES;
    private $generalJournalServices;
    private $contactServices;
    private $locationServices;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public  $LOGO_FILE = null;

    public $listDetails = [];

    public function boot(
        GeneralJournalServices $generalJournalServices,
        ContactServices $contactServices,
        LocationServices $locationServices
    ) {
        $this->generalJournalServices = $generalJournalServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $data = $this->generalJournalServices->Get($id);
            if ($data) {

                $this->CODE = $data->CODE;
                $this->DATE = $data->DATE;
                $this->listDetails =  $this->generalJournalServices->ListDetails($id);
                $con = $this->contactServices->getSingleData($data->CONTACT_ID ?? 0);
                if ($con) {
                    $this->CONTACT_NAME = $con->PRINT_NAME_AS;
                }
                $locData = $this->locationServices->get($data->LOCATION_ID);
                if ($locData) {
    
                    $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                    $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                    $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                    $this->LOCATION_NAME  = $locData->NAME;
                    $this->LOGO_FILE = $locData->LOGO_FILE ?? null;
                }

                $this->dispatch('preview_print');
                return;
            }
        }
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.general-journal.general-journal-print');
    }
}
