<?php
namespace App\Livewire\BankStatement;

use App\Services\AccountServices;
use App\Services\BankStatementServices;
use App\Services\DateServices;
use App\Services\FileTypeServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Bank Statement")]
class BankStatementForm extends Component
{

    private $bankStatementSerivce;
    private $accountServices;
    private $fileTypeServices;
    private $dateServices;

    public bool $Modify     = false;
    public $accountList     = [];
    public $fileTypeList    = [];
    public int $STATUS      = 0;
    public int $ID          = 0;
    public $BANK_ACCOUNT_ID = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public string $DESCRIPTION = '';
    public int $FILE_TYPE      = 0;
    public string $NOTES       = '';

    public $SELECT_YEAR;
    public $SELECT_MONTH;
    public $yearList  = [];
    public $monthList = [];

    public float $BEGINNING_BALANCE = 0;
    public float $ENDING_BALANCE    = 0;
    public function boot(BankStatementServices $bankStatementServices, AccountServices $accountServices, FileTypeServices $fileTypeServices, DateServices $dateServices)
    {
        $this->bankStatementSerivce = $bankStatementServices;
        $this->accountServices      = $accountServices;
        $this->fileTypeServices     = $fileTypeServices;
        $this->dateServices         = $dateServices;
    }
    private function LoadDropdown()
    {
        $this->fileTypeList = $this->fileTypeServices->getFileTypes();
        $this->accountList  = $this->accountServices->getBankAccount();

        $this->yearList  = $this->dateServices->YearList();
        $this->monthList = $this->dateServices->MonthList();
    }

    public function mount($id = null)
    {

        if (is_numeric($id)) {

            $this->getInfo($id);

        } else {

            $this->SELECT_YEAR  = $this->dateServices->NowYear();
            $this->SELECT_MONTH = $this->dateServices->NowMonth();

            $this->LoadDropdown();
            $this->ID     = 0;
            $this->Modify = true;
            $this->DATE   = $this->dateServices->NowDate();
        }
    }

    private function getInfo(int $ID)
    {
        $data = $this->bankStatementSerivce->get($ID);
        if ($data) {

            $this->LoadDropdown();
            $this->ID              = $data->ID;
            $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
            $this->DATE_FROM       = $data->DATE_FROM;
            $this->DATE_TO         = $data->DATE_TO;
            $this->DESCRIPTION     = $data->DESCRIPTION ?? '';
            $this->FILE_TYPE       = $data->FILE_TYPE;
            $this->NOTES           = $data->NOTES ?? '';

            $this->BEGINNING_BALANCE = $data->BEGINNING_BALANCE ?? 0;
            $this->ENDING_BALANCE    = $data->ENDING_BALANCE ?? 0;
            $this->STATUS            = $data->RECON_STATUS ?? 0;
        } else {

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingbank_statement')->with('error', $errorMessage);

        }
    }
    public function Save()
    {

        if ($this->ID == 0) {

            $this->validate(
                [
                    'BANK_ACCOUNT_ID' => 'required|not_in:0|exists:account,id',
                    'FILE_TYPE'       => 'required|not_in:0|exists:file_type_map,id',
                    'SELECT_YEAR'     => 'required|not_in:0',
                    'SELECT_MONTH'    => 'required|not_in:0',
                    'DESCRIPTION'     => 'required',

                ],
                [],
                [
                    'BANK_ACCOUNT_ID' => 'Bank Account',
                    'FILE_TYPE'       => 'File Type',
                    'SELECT_YEAR'     => 'Year',
                    'SELECT_MONTH'    => 'Month',
                    'DESCRIPTION'     => 'Description',

                ]
            );
            $this->DATE_FROM = $this->dateServices->GetFirstDay_ByMonthYear($this->SELECT_YEAR, $this->SELECT_MONTH);
            $this->DATE_TO   = $this->dateServices->GetLastDay_ByMonthYear($this->SELECT_YEAR, $this->SELECT_MONTH);
            try {
                DB::beginTransaction();
                $this->ID = $this->bankStatementSerivce->store($this->DATE_FROM, $this->DATE_TO,
                    $this->DESCRIPTION,
                    $this->BANK_ACCOUNT_ID,
                    $this->FILE_TYPE,
                    $this->NOTES);
                DB::commit();
                return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } catch (\Throwable $e) {

                DB::rollBack();
                session()->flash("error", $e->getMessage());
            }

        } else {

            $this->validate(
                [
                    'BANK_ACCOUNT_ID' => 'required|not_in:0|exists:account,id',
                    'FILE_TYPE'       => 'required|not_in:0|exists:file_type_map,id',
                    'DATE_FROM'       => 'required|date',
                    'DATE_TO'         => 'required|date',
                    'DESCRIPTION'     => 'required',

                ],
                [],
                [
                    'BANK_ACCOUNT_ID' => 'Bank Account',
                    'FILE_TYPE'       => 'File Type',
                    'DATE_FROM'       => 'Date From',
                    'DATE_TO'         => 'Date To',
                    'DESCRIPTION'     => 'Description',

                ]
            );

            try {
                DB::beginTransaction();
                $this->bankStatementSerivce->update($this->ID, $this->DATE_FROM, $this->DATE_TO, $this->DESCRIPTION, $this->BANK_ACCOUNT_ID, $this->FILE_TYPE, $this->NOTES);
                DB::commit();
                return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID])->with('message', 'Successfully updated');
            } catch (\Throwable $e) {
                DB::rollBack();
                session()->flash("error", $e->getMessage());
            }

        }

    }

    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        return Redirect::route('bankingbank_statement_edit', ['id' => $this->ID]);
    }
    #[On('promp')]
    public function prompMessage($result)
    {
        session()->flash($result['key'], $result['message']);
    }
    public function render()
    {
        return view('livewire.bank-statement.bank-statement-form');
    }
}
