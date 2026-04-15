<?php

namespace App\Livewire\PhilHealth;


use App\Services\HemoServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Philhealth')]
class PhilHealthList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 20;
    public int $locationid;
    public $locationList = [];
    public $ADMITTED;
    public $DISCHARGED;
    private $philHealthServices;
    private $locationServices;
    private $invoiceServices;
    private $userServices;
    public bool $show = false;
    public $editID = null;
    public $editClaimNo = null;
    private $hemoServices;
    private $serviceChargeServices;
    private $patientPaymentServices;
    private $paymentServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        LocationServices $locationServices,
        UserServices $userServices,
        InvoiceServices $invoiceServices,
        HemoServices $hemoServices,
        ServiceChargeServices $serviceChargeServices,
        PatientPaymentServices $patientPaymentServices,
        PaymentServices $paymentServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->invoiceServices = $invoiceServices;
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->paymentServices = $paymentServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {


        DB::beginTransaction();
        try {
            $this->philHealthServices->Delete($id);
            DB::commit();
            session()->flash('message', 'Successufully deleted.');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error:' . $th->getMessage());
        }

    }
    public function multiselect()
    {
        $this->show = true;
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function locationClick()
    {
        DB::beginTransaction();
        try {
            $dataList = $this->philHealthServices->dataList($this->locationid);
            foreach ($dataList as $list) {
                if ($list->INVOICE_ID > 0) {
                    $total = $this->invoiceServices->getPaid($list->INVOICE_ID);
                    $Stats = $this->philHealthServices->UpdatePayment($list->ID, $total);
                    if ($Stats == 11) {
                        $PAYMENT_ID = $this->invoiceServices->getPaymentIdVIaInvoice($list->INVOICE_ID);
                        if ($PAYMENT_ID > 0) {
                            $dataPayment = (object) $this->paymentServices->get($PAYMENT_ID);
                            if ($dataPayment) {
                                $PATIENT_PAYMENT_ID = $this->patientPaymentServices->PH_exists($list->ID);
                                if ($PATIENT_PAYMENT_ID == 0) {
                                    $PATIENT_PAYMENT_ID = $this->patientPaymentServices->PH_Store(
                                        $list->ID,
                                        $list->AMOUNT,
                                        $dataPayment->RECEIPT_REF_NO,
                                        $dataPayment->DATE,
                                        ""
                                    );
                                } else {
                                    $this->patientPaymentServices->PH_Update(
                                        $PATIENT_PAYMENT_ID,
                                        $list->ID,
                                        $list->AMOUNT,
                                        $dataPayment->RECEIPT_REF_NO,
                                        $dataPayment->DATE,
                                        ""
                                    );
                                }
                                $summaryList = $this->hemoServices->GetSummary($list->CONTACT_ID, $list->LOCATION_ID, $list->DATE_ADMITTED, $list->DATE_DISCHARGED);
                                foreach ($summaryList as $sumList) {
                                    $PP_ITEM_ID = $this->patientPaymentServices->PaymentChargesExist($PATIENT_PAYMENT_ID, $sumList->SCI_ID);
                                    if ($PP_ITEM_ID > 0) {
                                        $this->patientPaymentServices->PaymentChargesUpdate($PP_ITEM_ID, $PATIENT_PAYMENT_ID, $sumList->SCI_ID, 0, $sumList->AMOUNT);
                                    } else {
                                        $this->patientPaymentServices->PaymentChargeStore($PATIENT_PAYMENT_ID, $sumList->SCI_ID, 0, $sumList->AMOUNT, 0, 0);
                                    }
                                    $this->serviceChargeServices->updateServiceChargesItemPaid($sumList->SCI_ID);
                                    $this->serviceChargeServices->updateServiceChargesBalance($sumList->SERVICE_CHARGES_ID);

                                }
                            }


                        }

                    }
                }

            }

            DB::commit();
            session()->flash('message', 'Successfully udpate paid');
        } catch (\Throwable $th) {

            DB::rollBack();
            session()->flash('error', 'Error:' . $th->getMessage());
        }


    }

    public function updateCM()
    {
        $this->validate(
            [
                'editClaimNo' => 'required'
            ],
            [],
            ['editClaimNo' => 'Claim No.']
        );

        if ($this->philHealthServices->ifClaimNoExists($this->locationid, $this->editClaimNo)) {
            session()->flash('error', 'Claim No. already Exists');
            return;
        }

        $this->philHealthServices->updateClaimNo($this->editID, $this->editClaimNo);
        $this->cancelCM();
    }
    public function editCM($ID)
    {
        $this->editID = $ID;
    }
    public function cancelCM()
    {
        $this->editID = null;
        $this->editClaimNo = null;
    }
    public function getARForm(int $ID)
    {
        $data = [
            'PHILHEALTH_ID' => $ID
        ];

        $this->dispatch('ar-form-show', result: $data);
    }
    #[On('ar-form-data')]
    public function arForm($ar)
    {
        $this->dispatch('reload_philhealth_payment');
    }
    public function print(int $ID)
    {
        $data = [
            'PHILHEALTH_ID' => $ID
        ];

        $this->dispatch('philhealth-print-data', result: $data);
    }

    #[On('reload-list')]
    public function render()
    {
        $dataList = $this->philHealthServices->Search($this->search, $this->locationid, $this->perPage, $this->ADMITTED, $this->DISCHARGED);
        return view('livewire.phil-health.phil-health-list', ['dataList' => $dataList]);
    }
}
