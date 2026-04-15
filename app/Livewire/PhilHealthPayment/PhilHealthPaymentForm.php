<?php

namespace App\Livewire\PhilHealthPayment;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\PhilHealthServices;
use App\Services\SystemSettingServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Patient: Philhealth Payment Notes')]
class PhilHealthPaymentForm extends Component
{

    use WithFileUploads;
    public string $FILE_NAME;
    public string $FILE_PATH;
    public bool $IS_CONFIRM;
    public string $DATE_CONFIRM;
    public $PDF;
    public int $ID = 0;
    public string $CODE;
    public $DATE;
    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public int $PAYMENT_METHOD_ID;
    public string $CARD_NO;
    public $CARD_EXPIRY_DATE;
    public string $RECEIPT_REF_NO;
    public $RECEIPT_DATE;
    public string $NOTES;
    public int $UNDEPOSITED_FUNDS_ACCOUNT_ID;
    public int $OVERPAYMENT_ACCOUNT_ID;
    public int $STATUS;
    public string $STATUS_DATE;
    public string $STATUS_DESCRIPTION;
    public bool $DEPOSITED;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public int $WTAX_ACCOUNT_ID;
    public float $WTAX_AMOUNT = 0;
    public float $LESS_AMOUNT = 0;
    public int $PHILHEALTH_ID = 0;
    public $locationList = [];
    public $contactList = [];
    public $paymentMethodList = [];
    public $dataPhList = [];

    private $patientPaymentServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $paymentMethodServices;
    private $contactServices;
    public bool $Modify = true;
    public bool $showCardNo = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo = false;
    public bool $showReceiptDate = false;
    public bool $showFileName = false;
    public bool $showTax = false;
    private $uploadServices;
    private  $philHealthServices;
    public string $TITLE_REF = "";
    public string $TITLE_DATE = "";
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        ContactServices $contactServices,
        UploadServices $uploadServices,
        PhilHealthServices $philHealthServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->contactServices = $contactServices;
        $this->uploadServices = $uploadServices;
        $this->philHealthServices = $philHealthServices;
    }
    public function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->DATE = $data->DATE;
        $this->CODE = $data->CODE;
        $this->PATIENT_ID = $data->PATIENT_ID;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->AMOUNT = $data->AMOUNT;
        $this->AMOUNT_APPLIED = $data->AMOUNT_APPLIED;
        $this->PAYMENT_METHOD_ID = $data->PAYMENT_METHOD_ID ?? 0;
        $this->CARD_NO = $data->CARD_NO ?? null;
        $this->CARD_EXPIRY_DATE = $data->CARD_EXPIRY_DATE ?? null;
        $this->RECEIPT_REF_NO = $data->RECEIPT_REF_NO ?? null;
        $this->RECEIPT_DATE = $data->RECEIPT_DATE ?? null;
        $this->NOTES = $data->NOTES ?? null;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0;
        $this->OVERPAYMENT_ACCOUNT_ID = $data->OVERPAYMENT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->WTAX_ACCOUNT_ID =  $data->WTAX_ACCOUNT_ID ?? 0;
        $this->WTAX_AMOUNT =  $data->WTAX_AMOUNT  ?? 0;
        $this->LESS_AMOUNT = $data->LESS_AMOUNT ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DATE = $data->STATUS_DATE ?? null;
        $this->DEPOSITED = $data->DEPOSITED ?? null;
        $this->FILE_NAME = $data->FILE_NAME ?? '';
        $this->FILE_PATH = $data->FILE_PATH ?? '';
        $this->IS_CONFIRM = $data->IS_CONFIRM ?? false;
        $this->DATE_CONFIRM = $data->DATE_CONFIRM ?? '';

        $this->PHILHEALTH_ID = $data->PHILHEALTH_ID ?? 0;
        $this->updatedPATIENTID();
        $this->updatedpaymentmethodid();
        $this->Modify = false;
        $this->PDF = null;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->patientPaymentServices->UpdatePaymentChargesApplied($this->ID);
    }
    private function LoadDropDown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->contactList = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->paymentMethodList = $this->paymentMethodServices->getPaymentMethodViaPhilHealth();
    }
    public bool $reloadphcomboBoxList = false;
    public function updatedPATIENTID()
    {

        $this->dataPhList = $this->philHealthServices->DropDownPhilHealth(
            $this->PATIENT_ID,
            $this->LOCATION_ID,
            $this->ID == 0 ? 0 : $this->PHILHEALTH_ID
        );

        $this->reloadphcomboBoxList = $this->reloadphcomboBoxList ? false : true;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->patientPaymentServices->getPhilhealthPayment($id);
            if ($data) {
                $this->getInfo($data);
                $this->LoadDropDown();
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientsphic_pay')->with('error', $errorMessage);
        }
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->LoadDropDown();
        $this->ID = 0;
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->CODE = '';
        $this->PATIENT_ID = 0;
        $this->AMOUNT = 0;
        $this->AMOUNT_APPLIED = 0;
        $this->PAYMENT_METHOD_ID = 91; // Philhealth Default;
        $this->CARD_NO = '';
        $this->CARD_EXPIRY_DATE = null;
        $this->RECEIPT_REF_NO = '';
        $this->RECEIPT_DATE = null;
        $this->NOTES = '';
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $this->OVERPAYMENT_ACCOUNT_ID = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS = 0;
        $this->DEPOSITED = 0;
        $this->Modify = true;
        $this->PDF = null;
        $this->FILE_NAME = '';
        $this->FILE_PATH = '';
        $this->IS_CONFIRM = false;
        $this->DATE_CONFIRM = '';
        $this->WTAX_AMOUNT = 0;
        $this->WTAX_ACCOUNT_ID = 0;
        $this->LESS_AMOUNT = 0;
        $this->PHILHEALTH_ID = 0;
        $this->updatedpaymentmethodid();
    }
    public function updatedPdf()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }
    public function getConfirm()
    {
        $this->patientPaymentServices->ConfirmProccess($this->ID);
        return Redirect::route('patientspayment_edit', ['id' => $this->ID])->with('message', 'Successfully confirm');
    }
    public function updatedAmount()
    {
        $this->WTAX_AMOUNT =  $this->AMOUNT * $this->philHealthServices->TAX;
        $this->LESS_AMOUNT  =  $this->AMOUNT -  $this->WTAX_AMOUNT;
    }
    public function save()
    {


        $this->validate(
            [
                'PATIENT_ID'        => 'required|not_in:0',
                'DATE'              => 'required',
                'LOCATION_ID'       => 'required',
                'AMOUNT'            => 'required|not_in:0',
                'RECEIPT_REF_NO'    => 'required',
                'RECEIPT_DATE'      => 'required',
                'PHILHEALTH_ID'     => 'required|not_in:0'
            ],
            [],
            [
                'PATIENT_ID'        => 'Patient',
                'DATE'              => 'Date',
                'LOCATION_ID'       => 'Location',
                'AMOUNT'            => 'Amount',
                'RECEIPT_REF_NO'    => 'OR No.',
                'RECEIPT_DATE'      => 'OR Date',
                'PHILHEALTH_ID'     => 'Philhealth'
            ]
        );

        DB::beginTransaction();
        try {

            if ($this->ID == 0) {

                $this->ID = $this->patientPaymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->PATIENT_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    0,
                    $this->PAYMENT_METHOD_ID,
                    $this->CARD_NO,
                    $this->CARD_EXPIRY_DATE,
                    $this->RECEIPT_REF_NO,
                    $this->RECEIPT_DATE,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OVERPAYMENT_ACCOUNT_ID,
                    false,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->PHILHEALTH_ID,
                    $this->WTAX_AMOUNT,
                    $this->WTAX_ACCOUNT_ID,
                    $this->LESS_AMOUNT
                );

                $this->PaidUpdate();
                DB::commit();
                return Redirect::route('patientsphic_pay_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->patientPaymentServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->PATIENT_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->PAYMENT_METHOD_ID,
                    $this->CARD_NO,
                    $this->CARD_EXPIRY_DATE,
                    $this->RECEIPT_REF_NO,
                    $this->RECEIPT_DATE,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OVERPAYMENT_ACCOUNT_ID,
                    false,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->WTAX_AMOUNT,
                    $this->WTAX_ACCOUNT_ID,
                    $this->LESS_AMOUNT
                );
                $this->PaidUpdate();
                DB::commit();
                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function PaidUpdate()
    {
        $TotalPaid = (float) $this->patientPaymentServices->getSumOnPhilHealth($this->PATIENT_ID, $this->LOCATION_ID, $this->PHILHEALTH_ID);
        $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID, $TotalPaid);
    }
    public function getDocumentProccess()
    {
        $returnData = $this->uploadServices->Payment($this->PDF);
        $this->patientPaymentServices->UpdateFile($this->ID, $returnData['filename'] . '.' . $returnData['extension'], $returnData['new_path']);
    }
    public function getModify()
    {
        $this->PDF = null;
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->patientPaymentServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
    }
    public bool $reloadType = true;
    public function updatedpaymentmethodid()
    {
        $this->reloadType = $this->reloadType ? false : true;
        $paymentMethod = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);

        if ($paymentMethod) {
            $data =  $this->paymentMethodServices->PaymentMethodSwitch($paymentMethod->PAYMENT_TYPE);
            $this->showCardNo = (bool) $data['showCardNo'];
            $this->showCardDateExpire = (bool) $data['showCardDateExpire'];
            $this->showReceiptNo = (bool) $data['showReceiptNo'];
            $this->showReceiptDate = (bool) $data['showReceiptDate'];
            $this->showFileName = (bool) $data['showFileName'];
            $this->TITLE_REF = (string) $data['titleRef'];
            $this->TITLE_DATE = (string) $data['titleDate'];
            $this->showTax = (bool) $data['showTax'];
        }
    }
    public function openPayment()
    {
        $data = [
            'CONTACT_ID' => $this->PATIENT_ID
        ];
        $this->dispatch('open-assistance', result: $data);
    }
    public function render()
    {
        return view('livewire.phil-health-payment.phil-health-payment-form');
    }
}
