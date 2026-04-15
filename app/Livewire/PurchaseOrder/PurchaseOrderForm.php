<?php
namespace App\Livewire\PurchaseOrder;

use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentTermServices;
use App\Services\PurchaseOrderServices;
use App\Services\ShipViaServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Purchase Order')]
class PurchaseOrderForm extends Component
{

    public bool $PO_ALREADY_BILL;
    public int $ID;
    public int $VENDOR_ID;
    public $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SHIP_VIA_ID;
    public string $DATE_EXPECTED;
    public int $PAYMENT_TERMS_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;
    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public $vendorList      = [];
    public $locationList    = [];
    public $shipViaList     = [];
    public $paymentTermList = [];
    public $taxList         = [];
    public bool $Modify;
    private $purchaseOrderServices;
    private $locationServices;
    private $contactServices;
    private $shipViaServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    public function boot(
        PurchaseOrderServices $purchaseOrderServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        ShipViaServices $shipViaServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices

    ) {
        $this->purchaseOrderServices  = $purchaseOrderServices;
        $this->locationServices       = $locationServices;
        $this->contactServices        = $contactServices;
        $this->shipViaServices        = $shipViaServices;
        $this->paymentTermServices    = $paymentTermServices;
        $this->taxServices            = $taxServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;
    }
    public function LoadDropdown()
    {
        $this->vendorList      = $this->contactServices->getList(0);
        $this->locationList    = $this->locationServices->getList();
        $this->shipViaList     = $this->shipViaServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList         = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->INPUT_TAX_ID);
        if ($tax) {
            $this->INPUT_TAX_RATE       = (float) $tax->INPUT_TAX_RATE;
            $this->INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($PO)
    {
        $this->ID                   = $PO->ID;
        $this->CODE                 = $PO->CODE;
        $this->DATE                 = $PO->DATE;
        $this->DATE_EXPECTED        = $PO->DATE_EXPECTED ? $PO->DATE_EXPECTED : '';
        $this->LOCATION_ID          = $PO->LOCATION_ID;
        $this->VENDOR_ID            = $PO->VENDOR_ID;
        $this->SHIP_VIA_ID          = $PO->SHIP_VIA_ID ? $PO->SHIP_VIA_ID : 0;
        $this->PAYMENT_TERMS_ID     = $PO->PAYMENT_TERMS_ID ? $PO->PAYMENT_TERMS_ID : 0;
        $this->CLASS_ID             = $PO->CLASS_ID ? $PO->CLASS_ID : 0;
        $this->NOTES                = $PO->NOTES ?? '';
        $this->AMOUNT               = $PO->AMOUNT;
        $this->STATUS               = $PO->STATUS;
        $this->INPUT_TAX_ID         = $PO->INPUT_TAX_ID ? $PO->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE       = $PO->INPUT_TAX_RATE ? $PO->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT     = $PO->INPUT_TAX_AMOUNT ? $PO->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $PO->INPUT_TAX_VAT_METHOD ? $PO->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $PO->INPUT_TAX_ACCOUNT_ID ? $PO->INPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT       = $PO->TAXABLE_AMOUNT ? $PO->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT    = $PO->NONTAXABLE_AMOUNT ? $PO->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION   = $this->documentStatusServices->getDesc($this->STATUS);
        $this->PO_ALREADY_BILL      = $this->purchaseOrderServices->isPOAlreadyBill($this->ID);
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $PO = $this->purchaseOrderServices->get($id);
            if ($PO) {
                $this->LoadDropdown();
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorspurchase_order')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->Modify               = true;
        $this->ID                   = 0;
        $this->CODE                 = '';
        $this->DATE                 = $this->userServices->getTransactionDateDefault();
        $this->DATE_EXPECTED        = '';
        $this->LOCATION_ID          = $this->userServices->getLocationDefault();
        $this->VENDOR_ID            = 0;
        $this->SHIP_VIA_ID          = $this->shipViaServices->getFirst();
        $this->CLASS_ID             = 0;
        $this->PAYMENT_TERMS_ID     = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->NOTES                = '';
        $this->AMOUNT               = 0;
        $this->STATUS               = 0;
        $this->INPUT_TAX_ID         = (int) $this->systemSettingServices->GetValue('InputTaxId');
        $this->INPUT_TAX_RATE       = 0;
        $this->INPUT_TAX_AMOUNT     = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->TAXABLE_AMOUNT       = 0;
        $this->NONTAXABLE_AMOUNT    = 0;
        $this->STATUS_DESCRIPTION   = "";
        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function makeBill()
    {
        $this->dispatch('open-make-bill', purchase: ['PO_ID' => $this->ID]);
    }
    public function save()
    {

        $this->validate(
            [
                'VENDOR_ID'        => 'required|numeric|exists:contact,id',
                'CODE'             => 'nullable|max:20|unique:purchase_order,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'INPUT_TAX_ID'     => 'required|numeric|exists:tax,id',
                'DATE'             => 'required|date',
                'LOCATION_ID'      => 'required|numeric|exists:location,id',
                'PAYMENT_TERMS_ID' => 'required|numeric|exists:payment_terms,id',
            ],
            [],
            [
                'VENDOR_ID'        => 'Vendor',
                'CODE'             => 'Reference No.',
                'INPUT_TAX_ID'     => 'Tax',
                'DATE'             => 'Date',
                'LOCATION_ID'      => 'Location',
                'PAYMENT_TERMS_ID' => 'Payment Terms',
            ]
        );

        try {
            $this->getTax();
            if ($this->ID == 0) {

                if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
                    session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
                    return;
                }

                $this->ID = $this->purchaseOrderServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->DATE_EXPECTED,
                    '',
                    $this->SHIP_VIA_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->NOTES,
                    $this->STATUS,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID
                );
                return Redirect::route('vendorspurchase_order_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->purchaseOrderServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->VENDOR_ID,
                    $this->LOCATION_ID,
                    $this->CLASS_ID,
                    $this->DATE_EXPECTED,
                    '',
                    $this->SHIP_VIA_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->NOTES,
                    $this->STATUS,
                    $this->INPUT_TAX_ID,
                    $this->INPUT_TAX_RATE,
                    $this->INPUT_TAX_VAT_METHOD,
                    $this->INPUT_TAX_ACCOUNT_ID
                );
                $this->purchaseOrderServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $this->purchaseOrderServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');
            }

            $PO = $this->purchaseOrderServices->get($this->ID);
            if ($PO) {
                $this->getInfo($PO);
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT            = $list['AMOUNT'];
            $this->INPUT_TAX_AMOUNT  = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT    = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    public function updateCancel()
    {
        $PO = $this->purchaseOrderServices->get($this->ID);
        if ($PO) {
            $this->getInfo($PO);
        }
        $this->Modify = false;
    }
    public function getPosted()
    {
        try {

            $count = (int) $this->purchaseOrderServices->CountItems($this->ID);
            if ($count == 0) {
                session()->flash('error', 'Item not found.');
                return;
            }

            DB::beginTransaction();
            $this->purchaseOrderServices->StatusUpdate($this->ID, 15);
            DB::commit();
            $data = $this->purchaseOrderServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getVoid()
    {
        try {

            $this->purchaseOrderServices->StatusUpdate($this->ID, 7);
            $PO = $this->purchaseOrderServices->get($this->ID);

            if ($PO) {
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-form');
    }
}
