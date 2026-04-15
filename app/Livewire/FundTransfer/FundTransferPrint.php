<?php

namespace App\Livewire\FundTransfer;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\FundTransferServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Fund Transfer Print')]
class FundTransferPrint extends Component
{

    public int $ID;
    private $fundTransferServices;
    private $contactServices;
    private $locationServices;
    private $accountServices;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public string $LOCATION_NAME;

    public $LOGO_FILE = null;

    public $listDetails = [];


    public float $AMOUNT;
    public string $FROM_ACCOUNT;
    public string $FROM_LOCATION;
    public string $FROM_NAME;

    public string $TO_ACCOUNT;
    public string $TO_LOCATION;
    public string $TO_NAME;
    public string $CODE;
    public string $DATE;
    public string $INTER_ACCOUNT;
    public string $NOTES;

    public function boot(FundTransferServices $fundTransferServices, ContactServices $contactServices, LocationServices $locationServices, AccountServices $accountServices)
    {
        $this->fundTransferServices = $fundTransferServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->accountServices = $accountServices;
    }

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $data = $this->fundTransferServices->Get($id);
            if ($data) {
                $this->AMOUNT = $data->AMOUNT ?? 0;
                $this->NOTES = $data->NOTES;
                $this->CODE = $data->CODE;
                $this->DATE = $data->DATE;

                $toLocData = $this->locationServices->get($data->TO_LOCATION_ID);
                if ($toLocData) {
                    $this->TO_LOCATION = $toLocData->NAME;
                }
                $locData = $this->locationServices->get($data->FROM_LOCATION_ID);
                if ($locData) {

                    $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                    $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                    $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                    $this->LOCATION_NAME = $locData->NAME;
                    $this->FROM_LOCATION = $this->LOCATION_NAME;
                    $this->LOGO_FILE = $locData->LOGO_FILE ?? null;
                }

                $dataFromAcct = $this->accountServices->Get($data->FROM_ACCOUNT_ID);
                if ($dataFromAcct) {
                    $this->FROM_ACCOUNT = $dataFromAcct->NAME;
                }
                $dataToAcct = $this->accountServices->Get($data->TO_ACCOUNT_ID);
                if ($dataToAcct) {
                    $this->TO_ACCOUNT = $dataToAcct->NAME;
                }
                // INTER_LOCATION_ACCOUNT_ID
                $interAccount = $this->accountServices->Get($data->INTER_LOCATION_ACCOUNT_ID);
                if ($interAccount) {
                    $this->INTER_ACCOUNT = $interAccount->NAME;
                }


                $dataFromContact = $this->contactServices->get2($data->FROM_NAME_ID);
                if ($dataFromContact) {
                    $this->FROM_NAME = $dataFromContact->NAME;
                }
                $dataToContact = $this->contactServices->get2($data->TO_NAME_ID);
                if ($dataToContact) {
                    $this->TO_NAME = $dataToContact->NAME;
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
        return view('livewire.fund-transfer.fund-transfer-print');
    }
}
