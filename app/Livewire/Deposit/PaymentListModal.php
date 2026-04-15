<?php

namespace App\Livewire\Deposit;

use App\Services\DepositServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\SalesReceiptServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentListModal extends Component
{
    public int $UNDEPOSITED_ACCOUNT_ID = 5;
    public int $PAYMENT_METHOD_ID = 0;
    public string $search;
    public int $LOCATION_ID;
    public int $DEPOSIT_ID;
    public $dataList = [];
    public $paymentMethodList = [];
    private $depositServices;
    private $salesReceiptServices;
    private $paymentServices;
    private $paymentMethodServices;
    public function boot(DepositServices $depositServices, SalesReceiptServices $salesReceiptServices, PaymentServices $paymentServices, PaymentMethodServices $paymentMethodServices)
    {
        $this->depositServices = $depositServices;
        $this->salesReceiptServices = $salesReceiptServices;
        $this->paymentServices = $paymentServices;
        $this->paymentMethodServices = $paymentMethodServices;
    }

    public $showModal = false;
    #[On('open-payment')]
    public function openModal($result)
    {

        $this->search = '';
        $this->DEPOSIT_ID = (int) $result['DEPOSIT_ID'];
        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->PAYMENT_METHOD_ID = 0;

        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddFund(int $OBJECT_ID, int $OBJECT_TYPE)
    {

        DB::beginTransaction();
        try {
            //code...
            switch ($OBJECT_TYPE) {
                case 13:
                    # sales receipt...
                    $data =  $this->salesReceiptServices->getViaUndeposit($OBJECT_ID);

                    if ($data) {
                        $this->depositServices->StoreFund(
                            $this->DEPOSIT_ID,
                            $data->CUSTOMER_ID,
                            $data->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                            $data->PAYMENT_METHOD_ID,
                            $data->PAYMENT_REF_NO,
                            $data->AMOUNT,
                            $OBJECT_TYPE,
                            $OBJECT_ID
                        );
                        $this->depositServices->UndepositedUpdate($OBJECT_ID, $OBJECT_TYPE, 1);
                    }
                    break;
                case 11:
                    # payments...
                    $data = $this->paymentServices->getViaUndeposit($OBJECT_ID);
                    if ($data) {
                        $this->depositServices->StoreFund(
                            $this->DEPOSIT_ID,
                            $data->CUSTOMER_ID,
                            $data->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                            $data->PAYMENT_METHOD_ID,
                            $data->PAYMENT_REF_NO,
                            $data->AMOUNT,
                            $OBJECT_TYPE,
                            $OBJECT_ID
                        );
                        $this->depositServices->UndepositedUpdate($OBJECT_ID, $OBJECT_TYPE, 1);
                    }

                    break;
                default:
                    # code...
                    break;
            }

            $this->depositServices->UpdateAmount($this->DEPOSIT_ID);
            DB::commit();
            $this->dispatch('get-amount');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->depositServices->getUndositedCollection($this->LOCATION_ID, $this->PAYMENT_METHOD_ID, $this->search);
        }

        return view('livewire.deposit.payment-list-modal');
    }
}
