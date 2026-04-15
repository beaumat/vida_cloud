<?php
namespace App\Livewire\BankReconReport;

use App\Services\AccountServices;
use App\Services\BankReconServices;
use App\Services\LocationServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BankReconHeaderReport extends Component
{

    #[Reactive]
    public $BANK_RECON_ID;

    public string $COMPANY_NAME;
    public string $COMPANY_ADDRESS;
    public string $BANK_NAME;
    public string $ACCOUNT_NO;

    public string $BANK_STATEMENT_DATE;
    public string $CODE;
    public string $LOCATION_NAME;
    public string $PREPARED_BY_NAME;

    private $bankReconServices;

    private $accountServices;
    private $locationServices;
    public function boot(BankReconServices $bankReconServices, AccountServices $accountServices, LocationServices $locationServices)
    {
        $this->bankReconServices = $bankReconServices;
        $this->accountServices   = $accountServices;
        $this->locationServices  = $locationServices;

    }
    public function mount()
    {
        $data = $this->bankReconServices->get($this->BANK_RECON_ID);
        if ($data) {
            $this->CODE                = $data->CODE;
            $this->BANK_STATEMENT_DATE = $data->DATE;
            $dAcct                     = $this->accountServices->get($data->ACCOUNT_ID);
            if ($dAcct) {
                $this->BANK_NAME  = $dAcct->NAME ?? '';
                $this->ACCOUNT_NO = $dAcct->BANK_ACCOUNT_NO ?? '';
            }

            $loc = $this->locationServices->Get($data->LOCATION_ID);
            if ($loc) {
                $this->LOCATION_NAME   = $loc->NAME;
                $this->COMPANY_NAME    = $loc->NAME_OF_BUSINESS;
                $this->COMPANY_ADDRESS = "";

            }
        }
    }

    public function render()
    {
        return view('livewire.bank-recon-report.bank-recon-header-report');
    }
}
