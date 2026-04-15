<?php
namespace App\Livewire\Statement;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\StatementServices;
use DateTime;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Statement of Account: Details")]
class StatementView extends Component
{
    public int $CUSTOMER_ID;
    public string $NAME         = '';
    public string $CONTACT_TYPE = '';
    public float $PREV_BALANCE  = 0;
    public float $TOTAL_DEBIT   = 0;
    public float $TOTAL_CREDIT  = 0;
    public float $BALANCE_DUE   = 0;

    public string $dateFrom = '';
    public string $dateTo   = '';

    public bool $AS_OF_DATE = false;
    public $dataList        = [];
    private $statementServices;
    private $contactServices;
    private $dateServices;
    public function boot(StatementServices $statementServices, ContactServices $contactServices, DateServices $dateServices)
    {
        $this->statementServices = $statementServices;
        $this->contactServices   = $contactServices;
        $this->dateServices      = $dateServices;
    }
    public function mount(int $id)
    {
        $this->CUSTOMER_ID = $id;

        $this->updatedAsOfDate();
    }
    public function updatedAsOfDate()
    {
        if ($this->AS_OF_DATE) {

            $this->dateFrom = $this->dateServices->NowDate();
            $this->dateTo   = '';

        } else {

            $dt             = $this->dateServices->NowDate();
            $this->dateFrom = $this->dateServices->GetFirstDay_Month($dt);
            $this->dateTo   = $dt;
        }

    }
    private function Reload()
    {
        $this->PREV_BALANCE = 0;
        $this->dataList     = $this->statementServices->CustomerSoaEntryList($this->CUSTOMER_ID, $this->dateFrom, $this->dateTo);
        $data               = $this->contactServices->get2($this->CUSTOMER_ID);
        if ($this->dateTo != '') {
            $dt = new DateTime($this->dateFrom);
            $dt->modify('-1 day');

            $this->PREV_BALANCE = $this->statementServices->CustomerSoaBalance($this->CUSTOMER_ID, $dt->format('Y-m-d'), '', null);
        }

        $this->TOTAL_DEBIT  = $this->statementServices->CustomerSoaBalance($this->CUSTOMER_ID, $this->dateFrom, $this->dateTo, 0);
        $this->TOTAL_CREDIT = $this->statementServices->CustomerSoaBalance($this->CUSTOMER_ID, $this->dateFrom, $this->dateTo, 1);
        $this->BALANCE_DUE  = ($this->TOTAL_DEBIT + $this->TOTAL_CREDIT) + $this->PREV_BALANCE;

        if ($data) {
            $this->NAME = $data->NAME ?? '';
            $type       = $this->contactServices->ContactType($data->TYPE);
            if ($type) {
                $this->CONTACT_TYPE = $type->DESCRIPTION ?? '';
            }

        }
    }
    public function render()
    {
        $this->Reload();

        return view('livewire.statement.statement-view');
    }
}
