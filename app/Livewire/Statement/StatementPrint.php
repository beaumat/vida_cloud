<?php
namespace App\Livewire\Statement;

use App\Services\ContactServices;
use App\Services\StatementServices;
use DateTime;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Customer Soa Print')]
class StatementPrint extends Component
{

    public string $NAME         = '';
    public string $CONTACT_TYPE = '';
    public float $PREV_BALANCE  = 0;
    public float $TOTAL_DEBIT   = 0;
    public float $TOTAL_CREDIT  = 0;
    public float $BALANCE_DUE   = 0;
    public $dataList            = [];
    private $statementServices;
    private $contactServices;

    public string $dateFrom;
    public string $dateTo;
    public function boot(StatementServices $statementServices, ContactServices $contactServices)
    {
        $this->statementServices = $statementServices;
        $this->contactServices   = $contactServices;
    }
    public function mount(int $id, string $datefrom, string $dateto = "")
    {
        $this->dateFrom = $datefrom;
        $this->dateTo   = $dateto;

        $this->dataList = $this->statementServices->CustomerSoaEntryList($id, $datefrom, $dateto);
        $data           = $this->contactServices->get2($id);
        if ($dateto != '') {
            $dt = new DateTime($datefrom);
            $dt->modify('-1 day');

            $this->PREV_BALANCE = $this->statementServices->CustomerSoaBalance($id, $dt->format('Y-m-d'), '', null);
        }

        $this->TOTAL_DEBIT  = $this->statementServices->CustomerSoaBalance($id, $datefrom, $dateto, 0);
        $this->TOTAL_CREDIT = $this->statementServices->CustomerSoaBalance($id, $datefrom, $dateto, 1);
        $this->BALANCE_DUE  = ($this->TOTAL_DEBIT + $this->TOTAL_CREDIT) + $this->PREV_BALANCE;
        if ($data) {
            $this->NAME = $data->NAME ?? '';
            $type       = $this->contactServices->ContactType($data->TYPE);
            if ($type) {
                $this->CONTACT_TYPE = $type->DESCRIPTION ?? '';
            }

        }
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.statement.statement-print');
    }
}
