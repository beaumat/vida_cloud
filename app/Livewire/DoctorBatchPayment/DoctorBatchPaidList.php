<?php
namespace App\Livewire\DoctorBatchPayment;

use App\Services\AccountJournalServices;
use App\Services\BillPaymentServices;
use App\Services\DoctorBatchServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorBatchPaidList extends Component
{
    #[Reactive]
    public int $DOCTOR_BATCH_ID;
    public $dataList = [];
    private $doctorBatchServices;
    private $billPaymentServices;
    private $accountJournalServices;

    public function boot(DoctorBatchServices $doctorBatchServices, BillPaymentServices $billPaymentServices, AccountJournalServices $accountJournalServices)
    {
        $this->doctorBatchServices    = $doctorBatchServices;
        $this->billPaymentServices    = $billPaymentServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function deleteItem(int $id)
    {
        $this->doctorBatchServices->DeletePaid($id, $this->DOCTOR_BATCH_ID);
    }

    public function postedPayBill(int $CHECK_ID)
    {
        $data = $this->billPaymentServices->Get($CHECK_ID);
        if ($data) {

            try {
                DB::beginTransaction();
                $check      = $this->billPaymentServices->object_type_check;
                $checkbills = $this->billPaymentServices->object_type_check_bills;
                $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($check, $CHECK_ID);
                if ($JOURNAL_NO == 0) {
                    $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($check, $CHECK_ID) + 1;
                }

                $checkDataBills = $this->billPaymentServices->billPaymentBillsJournal($CHECK_ID);
                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $checkDataBills,
                    $data->LOCATION_ID,
                    $checkbills,
                    $data->DATE,
                    "AP"
                );

                $checkData = $this->billPaymentServices->billPaymentJournalRemaining($CHECK_ID);
                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $checkData,
                    $data->LOCATION_ID,
                    $check,
                    $data->DATE,
                    "BILL"
                );

                $checkData = $this->billPaymentServices->billPaymentJournal($CHECK_ID);
                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $checkData,
                    $data->LOCATION_ID,
                    $check,
                    $data->DATE,
                    "BILL"
                );

                $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
                $debit_sum  = (float) $data['DEBIT'];
                $credit_sum = (float) $data['CREDIT'];
                if ($debit_sum == $credit_sum) {
                    $this->billPaymentServices->StatusUpdate($CHECK_ID, 15);
                    DB::commit();
                    session()->flash('message', 'Successfully posted');
                }
                session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
                DB::rollBack();
            } catch (\Exception $e) {
                DB::rollBack();
                $errorMessage = 'Error occurred: ' . $e->getMessage();
                session()->flash('error', $errorMessage);
            }
        }
    }
    #[On('refresh-list-doctor-batch')]
    public function render()
    {
        $this->dataList = $this->doctorBatchServices->PaidList($this->DOCTOR_BATCH_ID);

        return view('livewire.doctor-batch-payment.doctor-batch-paid-list');
    }
}
