<?php
namespace App\Livewire\PatientPayment;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\SystemSettingServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Patient: Cash/GL Payment')]
class PatientPaymentForm extends Component
{
    use WithFileUploads;
    public bool $IS_INVOICE = false;
    public int $REF_ID      = 0;
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
    public float $WTAX_AMOUNT;
    public $showTax           = false;
    public $locationList      = [];
    public $contactList       = [];
    public $paymentMethodList = [];
    private $patientPaymentServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $paymentMethodServices;
    private $contactServices;
    public bool $Modify             = true;
    public bool $showCardNo         = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo      = false;
    public bool $showReceiptDate    = false;
    public bool $showFileName       = false;
    private $uploadServices;
    public string $TITLE_REF  = "";
    public string $TITLE_DATE = "";
    public $accountList       = [];
    private $invoiceServices;
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
        InvoiceServices $invoiceServices

    ) {

        $this->patientPaymentServices = $patientPaymentServices;
        $this->locationServices       = $locationServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountServices        = $accountServices;
        $this->paymentMethodServices  = $paymentMethodServices;
        $this->contactServices        = $contactServices;
        $this->uploadServices         = $uploadServices;
        $this->invoiceServices        = $invoiceServices;
    }
    public function getInfo($data)
    {
        $this->ID                = $data->ID;
        $this->DATE              = $data->DATE;
        $this->CODE              = $data->CODE;
        $this->PATIENT_ID        = $data->PATIENT_ID;
        $this->LOCATION_ID       = $data->LOCATION_ID;
        $this->AMOUNT            = $data->AMOUNT;
        $this->AMOUNT_APPLIED    = $data->AMOUNT_APPLIED;
        $this->PAYMENT_METHOD_ID = $data->PAYMENT_METHOD_ID ?? 0;
        $this->CARD_NO           = $data->CARD_NO ?? null;
        $this->CARD_EXPIRY_DATE  = $data->CARD_EXPIRY_DATE ?? null;
        $this->RECEIPT_REF_NO    = $data->RECEIPT_REF_NO ?? null;
        $this->RECEIPT_DATE      = $data->RECEIPT_DATE ?? null;
        $this->NOTES             = $data->NOTES ?? null;
        $this->updatedpaymentmethodid();
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0;
        $this->OVERPAYMENT_ACCOUNT_ID       = $data->OVERPAYMENT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID       = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->WTAX_ACCOUNT_ID              = $data->WTAX_ACCOUNT_ID ?? 0;
        $this->WTAX_AMOUNT                  = $data->WTAX_AMOUNT ?? 0;
        $this->STATUS                       = $data->STATUS ?? 0;
        $this->STATUS_DATE                  = $data->STATUS_DATE ?? null;
        $this->DEPOSITED                    = $data->DEPOSITED ?? null;
        $this->FILE_NAME                    = $data->FILE_NAME ?? '';
        $this->FILE_PATH                    = $data->FILE_PATH ?? '';
        $this->IS_CONFIRM                   = $data->IS_CONFIRM ?? false;
        $this->DATE_CONFIRM                 = $data->DATE_CONFIRM ?? '';
        $this->IS_INVOICE                   = $data->IS_INVOICE;
        $this->REF_ID                       = $data->REF_ID ?? 0;

        $this->Modify = false;
        $this->PDF    = null;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->patientPaymentServices->UpdatePaymentChargesApplied($this->ID);
    }
    private function LoadDropDown()
    {
        $this->accountList       = $this->accountServices->getIncome();
        $this->locationList      = $this->locationServices->getList();
        $this->contactList       = $this->contactServices->getPatientList2($this->LOCATION_ID);
        $this->paymentMethodList = $this->paymentMethodServices->getPaymentMethodViaPatientPayment();
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->patientPaymentServices->getPatientPayment($id);

            if ($data) {
                $this->getInfo($data);
                $this->LoadDropDown();
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientspayment')->with('error', $errorMessage);
        }
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->LoadDropDown();
        $this->ID                           = 0;
        $this->DATE                         = $this->userServices->getTransactionDateDefault();
        $this->CODE                         = '';
        $this->PATIENT_ID                   = 0;
        $this->AMOUNT                       = 0;
        $this->AMOUNT_APPLIED               = 0;
        $this->PAYMENT_METHOD_ID            = (int) $this->systemSettingServices->GetValue('DefaultPaymentMethodId');
        $this->CARD_NO                      = '';
        $this->CARD_EXPIRY_DATE             = null;
        $this->RECEIPT_REF_NO               = '';
        $this->RECEIPT_DATE                 = null;
        $this->NOTES                        = '';
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $this->OVERPAYMENT_ACCOUNT_ID       = 0;
        $this->ACCOUNTS_RECEIVABLE_ID       = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS                       = 0;
        $this->DEPOSITED                    = 0;
        $this->Modify                       = true;
        $this->PDF                          = null;
        $this->FILE_NAME                    = '';
        $this->FILE_PATH                    = '';
        $this->IS_CONFIRM                   = false;
        $this->DATE_CONFIRM                 = '';
        $this->WTAX_AMOUNT                  = 0;
        $this->WTAX_ACCOUNT_ID              = 0;
        $this->updatedpaymentmethodid();
    }
    public function updatedPdf()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }
    public function makeInvoice()
    {
        $data = [
            'PAYMENT_METHOD_ID'  => $this->PAYMENT_METHOD_ID,
            'PATIENT_PAYMENT_ID' => $this->ID,
        ];

        $this->dispatch('make-invoice-show', result: $data);
    }
    public function getConfirm()
    {
        $this->patientPaymentServices->ConfirmProccess($this->ID);
        return Redirect::route('patientspayment_edit', ['id' => $this->ID])->with('message', 'Successfully confirm');
    }
    public function save()
    {

        $getType      = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);
        $PAYMENT_TYPE = (int) $getType->PAYMENT_TYPE;
        if ($PAYMENT_TYPE == 10 && $this->ID == 0) {

            if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                return;
            }

            $this->validate(
                [
                    'PATIENT_ID'                   => 'required|not_in:0',
                    'DATE'                         => 'required',
                    'LOCATION_ID'                  => 'required',
                    'AMOUNT'                       => 'required|not_in:0',
                    'RECEIPT_REF_NO'               => 'required',
                    'RECEIPT_DATE'                 => 'required',
                    'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $this->PAYMENT_METHOD_ID == 1 ? '' : 'required|exists:account,id',
                ],
                [],
                [
                    'PATIENT_ID'                   => 'Patient',
                    'DATE'                         => 'Date',
                    'LOCATION_ID'                  => 'Location',
                    'AMOUNT'                       => 'Amount',
                    'RECEIPT_REF_NO'               => 'GL Reference No.',
                    'RECEIPT_DATE'                 => 'GL Date',
                    'UNDEPOSITED_FUNDS_ACCOUNT_ID' => 'GL Account',
                ]
            );
        } else {

            $this->validate(
                [
                    'PATIENT_ID'                   => 'required|not_in:0',
                    'DATE'                         => 'required',
                    'LOCATION_ID'                  => 'required',
                    'AMOUNT'                       => 'required|not_in:0',
                    'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $this->PAYMENT_METHOD_ID == 1 ? '' : 'required|exists:account,id',
                ],
                [],
                [
                    'PATIENT_ID'  => 'Patient',
                    'DATE'        => 'Date',
                    'LOCATION_ID' => 'Location',
                    'AMOUNT'      => 'Amount',
                ]
            );
        }

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
                    0,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    0,
                    $this->WTAX_AMOUNT,
                    $this->WTAX_ACCOUNT_ID
                );

                if ($PAYMENT_TYPE == 10) {
                    if ($this->PDF) {
                        $this->getDocumentProccess();
                    }
                }

                return Redirect::route('patientspayment_edit', ['id' => $this->ID])->with('message', 'Successfully created');
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
                    0,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->WTAX_AMOUNT,
                    $this->WTAX_ACCOUNT_ID
                );

                if ($this->PDF) {
                    if ($PAYMENT_TYPE == 10) {
                        $this->uploadServices->RemoveIfExists($this->FILE_PATH);
                        $this->getDocumentProccess();
                        $data = $this->patientPaymentServices->get($this->ID);
                        if ($data) {
                            $this->getInfo($data);
                        }
                    }
                }
                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getDocumentProccess()
    {
        $returnData = $this->uploadServices->Payment($this->PDF);
        $this->patientPaymentServices->UpdateFile($this->ID, $returnData['filename'] . '.' . $returnData['extension'], $returnData['new_path']);
    }
    public function getModify()
    {
        $this->PDF    = null;
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
        $paymentMethod    = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);

        if ($paymentMethod) {
            $data                     = $this->paymentMethodServices->PaymentMethodSwitch($paymentMethod->PAYMENT_TYPE);
            $this->showCardNo         = (bool) $data['showCardNo'];
            $this->showCardDateExpire = (bool) $data['showCardDateExpire'];
            $this->showReceiptNo      = (bool) $data['showReceiptNo'];
            $this->showReceiptDate    = (bool) $data['showReceiptDate'];
            $this->showFileName       = (bool) $data['showFileName'];
            $this->TITLE_REF          = (string) $data['titleRef'];
            $this->TITLE_DATE         = (string) $data['titleDate'];
            $this->showTax            = (bool) $data['showTax'];

            $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $paymentMethod->GL_ACCOUNT_ID ?? 0;
        }
        // $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
    }
    public function openPayment()
    {
        $data = [
            'CONTACT_ID' => $this->PATIENT_ID,
        ];
        $this->dispatch('open-assistance', result: $data);
    }

    public function makeSalesReceipt()
    {
        $dataItemCheck = $this->patientPaymentServices->PaymentChargesList($this->ID, 0);
        if ($dataItemCheck) {

            $data = [
                'PAYMENT_METHOD_ID'  => $this->PAYMENT_METHOD_ID,
                'PATIENT_PAYMENT_ID' => $this->ID,
            ];

            $this->dispatch('make-sales-receipt-show', result: $data);
        }
    }
    public function render()
    {
        return view('livewire.patient-payment.patient-payment-form');
    }
}
