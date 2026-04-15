<?php
namespace App\Livewire\ServiceCharge;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PaymentTermServices;
use App\Services\PriceLevelLineServices;
use App\Services\ScheduleServices;
use App\Services\ServiceChargeServices;
use App\Services\ShipViaServices;
use App\Services\SystemSettingServices;
use App\Services\TaxServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Service Charge')]
class ServiceChargeForm extends Component
{

    public int $ID = 0;
    public int $PATIENT_ID;
    public int $SALES_REP_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SHIP_VIA_ID;
    public $DUE_DATE;
    public int $PAYMENT_TERMS_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $OUTPUT_TAX_ID;
    public float $OUTPUT_TAX_RATE;
    public int $OUTPUT_TAX_VAT_METHOD;
    public int $OUTPUT_TAX_ACCOUNT_ID;
    public float $OUTPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $BALANCE_DUE;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public bool $WALK_IN = false;

    public int $HEMO_ID;

    public int $ITEM_COUNT  = 0;
    public int $HEMO_STATUS = 0;
    public $patientList     = [];
    public $locationList    = [];
    public $shipViaList     = [];
    public $paymentTermList = [];
    public $taxList         = [];
    public bool $Modify;
    private $locationServices;
    private $contactServices;
    private $shipViaServices;
    private $paymentTermServices;
    private $taxServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $scheduleServices;
    private $serviceChargeServices;
    private $priceLevelLineServices;
    private $hemoServices;
    public string $tab = "item";
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        LocationServices $locationServices,
        ContactServices $contactServices,
        ShipViaServices $shipViaServices,
        PaymentTermServices $paymentTermServices,
        TaxServices $taxServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        ScheduleServices $scheduleServices,
        HemoServices $hemoServices,
        PriceLevelLineServices $priceLevelLineServices
    ) {
        $this->serviceChargeServices  = $serviceChargeServices;
        $this->locationServices       = $locationServices;
        $this->contactServices        = $contactServices;
        $this->shipViaServices        = $shipViaServices;
        $this->paymentTermServices    = $paymentTermServices;
        $this->taxServices            = $taxServices;
        $this->userServices           = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->accountServices        = $accountServices;
        $this->scheduleServices       = $scheduleServices;
        $this->hemoServices           = $hemoServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
    }
    public function updatedwalkin()
    {
        $this->contactLoad();
    }
    public function updatedLocationid()
    {
        $this->contactLoad();
    }

    private function contactLoad()
    {
        if ($this->WALK_IN) {
            $this->patientList = $this->contactServices->getPatientList2($this->LOCATION_ID);
        } else {
            $isCreated         = (bool) $this->ID == 0 ? true : false;
            $this->patientList = $this->scheduleServices->ContactListFromSchedules($this->DATE, $this->LOCATION_ID, $isCreated);
        }
    }
    public function LoadDropdown()
    {
        $this->contactLoad();
        $this->locationList    = $this->locationServices->getList();
        $this->shipViaList     = $this->shipViaServices->getList();
        $this->paymentTermList = $this->paymentTermServices->getList();
        $this->taxList         = $this->taxServices->getList();
    }
    public function getTax()
    {
        $tax = $this->taxServices->get($this->OUTPUT_TAX_ID);
        if ($tax) {
            $this->OUTPUT_TAX_RATE       = (float) $tax->OUTPUT_TAX_RATE;
            $this->OUTPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->OUTPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($Data)
    {
        $this->ID                     = $Data->ID;
        $this->CODE                   = $Data->CODE;
        $this->DATE                   = $Data->DATE;
        $this->DUE_DATE               = $Data->DUE_DATE ?? null;
        $this->LOCATION_ID            = $Data->LOCATION_ID;
        $this->PATIENT_ID             = $Data->PATIENT_ID;
        $this->SALES_REP_ID           = $Data->SALES_REP_ID ?? 0;
        $this->SHIP_VIA_ID            = $Data->SHIP_VIA_ID ?? 0;
        $this->PAYMENT_TERMS_ID       = $Data->PAYMENT_TERMS_ID ? $Data->PAYMENT_TERMS_ID : 0;
        $this->CLASS_ID               = $Data->CLASS_ID ? $Data->CLASS_ID : 0;
        $this->NOTES                  = $Data->NOTES ?? '';
        $this->AMOUNT                 = $Data->AMOUNT;
        $this->BALANCE_DUE            = $Data->BALANCE_DUE;
        $this->ACCOUNTS_RECEIVABLE_ID = $Data->ACCOUNTS_RECEIVABLE_ID;
        $this->STATUS                 = $Data->STATUS;
        $this->OUTPUT_TAX_ID          = $Data->OUTPUT_TAX_ID ? $Data->OUTPUT_TAX_ID : 0;
        $this->OUTPUT_TAX_RATE        = $Data->OUTPUT_TAX_RATE ? $Data->OUTPUT_TAX_RATE : 0;
        $this->OUTPUT_TAX_AMOUNT      = $Data->OUTPUT_TAX_AMOUNT ? $Data->OUTPUT_TAX_AMOUNT : 0;
        $this->OUTPUT_TAX_VAT_METHOD  = $Data->OUTPUT_TAX_VAT_METHOD ? $Data->OUTPUT_TAX_VAT_METHOD : 0;
        $this->OUTPUT_TAX_ACCOUNT_ID  = $Data->OUTPUT_TAX_ACCOUNT_ID ? $Data->OUTPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT         = $Data->TAXABLE_AMOUNT ? $Data->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT      = $Data->NONTAXABLE_AMOUNT ? $Data->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION     = $this->documentStatusServices->getDesc($this->STATUS);
        $this->WALK_IN                = $Data->WALK_IN ?? false;

    }
    public function updatedPAYMENTTERMSID()
    {
        $this->DUE_DATE = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID);
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->serviceChargeServices->get($id);
            if ($data) {
                $this->LOCATION_ID = $data->LOCATION_ID ?? 0;
                $this->getInfo($data);
                $this->LoadDropdown();
                $this->Modify  = false;
                $this->HEMO_ID = $this->hemoServices->GetHemoID($data->DATE, $data->PATIENT_ID, $data->LOCATION_ID);
                if ($this->HEMO_ID > 0) {

                }
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientsservice_charges')->with('error', $errorMessage);
        }
        $this->DATE        = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->LoadDropdown();
        $this->Modify                 = true;
        $this->ID                     = 0;
        $this->CODE                   = '';
        $this->PATIENT_ID             = 0;
        $this->SALES_REP_ID           = 0;
        $this->SHIP_VIA_ID            = $this->shipViaServices->getFirst();
        $this->CLASS_ID               = 0;
        $this->PAYMENT_TERMS_ID       = (int) $this->systemSettingServices->GetValue('DefaultPaymentTermsId');
        $this->DUE_DATE               = $this->paymentTermServices->getDueDate($this->PAYMENT_TERMS_ID);
        $this->NOTES                  = '';
        $this->AMOUNT                 = 0;
        $this->BALANCE_DUE            = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivables');
        $this->STATUS                 = 0;
        $this->OUTPUT_TAX_ID          = (int) $this->systemSettingServices->GetValue('OutputTaxId');
        $this->OUTPUT_TAX_RATE        = 0;
        $this->OUTPUT_TAX_AMOUNT      = 0;
        $this->OUTPUT_TAX_VAT_METHOD  = 0;
        $this->OUTPUT_TAX_ACCOUNT_ID  = 0;
        $this->TAXABLE_AMOUNT         = 0;
        $this->NONTAXABLE_AMOUNT      = 0;
        $this->STATUS_DESCRIPTION     = "";
        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
        $this->contactLoad();
    }
    public function save()
    {

        $this->validate(
            [
                'PATIENT_ID'       => 'required|numeric|not_in:0|exists:contact,id',
                'CODE'             => $this->ID > 0 ? 'required|max:20|unique:invoice,code,' . $this->ID : 'nullable',
                'OUTPUT_TAX_ID'    => 'required|numeric|not_in:0',
                'DATE'             => 'required|date',
                'LOCATION_ID'      => 'required|numeric|exists:location,id',
                'PAYMENT_TERMS_ID' => 'required|numeric',
            ],
            [],
            [
                'PATIENT_ID'       => 'Petient',
                'CODE'             => 'Reference No.',
                'OUTPUT_TAX_ID'    => 'Tax',
                'DATE'             => 'Date',
                'LOCATION_ID'      => 'Location',
                'PAYMENT_TERMS_ID' => 'Payment Terms',
            ]
        );

        if ($this->systemSettingServices->IsCloseDate($this->DATE)) {
            session()->flash('error', 'You cannot create a transaction before or on the closing date on :' . $this->systemSettingServices->CloseDate());
            return;
        }

        try {
            if ($this->ID == 0) {
                if ($this->serviceChargeServices->ServicesChargesExists($this->DATE, $this->PATIENT_ID, $this->LOCATION_ID)) {
                    session()->flash('error', 'A service charge for this patient already exists for the date ' . date('M/d/Y', strtotime($this->DATE)) . '.');
                    return;
                }

                DB::beginTransaction();
                $this->getTax();
                $this->ID = (int) $this->serviceChargeServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->PATIENT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID,
                    $this->WALK_IN
                );

                $PRICE_LEVEL_ID = 0;
                $dataItem       = $this->hemoServices->ItemListWithIsCashier($this->PATIENT_ID, $this->LOCATION_ID, $this->DATE);
                $dataLoc        = $this->locationServices->get($this->LOCATION_ID);
                if ($dataLoc) {
                    if ($dataLoc->PRICE_LEVEL_ID > 0) {
                        $PRICE_LEVEL_ID = (int) $dataLoc->PRICE_LEVEL_ID ?? 0;
                    }
                }

                foreach ($dataItem as $list) {
                    $RATE = 0;
                    if ($PRICE_LEVEL_ID > 0) {
                        $RATE = (float) $this->priceLevelLineServices->GetPriceByLocation($this->LOCATION_ID, $list->ITEM_ID);
                    } else {
                        $RATE = (float) $list->RATE ?? 0;
                    }

                    $AMOUNT     = $list->QUANTITY * $RATE;
                    $SC_ITEM_ID = $this->serviceChargeServices->ItemStore(
                        $this->ID,
                        $list->ITEM_ID,
                        $list->QUANTITY,
                        $list->UNIT_ID ?? 0,
                        $list->UNIT_BASE_QUANTITY ?? 1,
                        $RATE ?? 0,
                        0,
                        $AMOUNT,
                        $list->TAXABLE,
                        0,
                        0,
                        $list->COGS_ACCOUNT_ID ?? 0,
                        $list->ASSET_ACCOUNT_ID ?? 0,
                        0,
                        0,
                        false,
                        $PRICE_LEVEL_ID
                    );
                    $this->hemoServices->ItemUpdateSC_ITEM_ID($list->ID, $list->HEMO_ID, $list->ITEM_ID, $SC_ITEM_ID);
                }
                $this->serviceChargeServices->ReComputed($this->ID);
                DB::commit();
                return Redirect::route('patientsservice_charges_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                DB::beginTransaction();
                $this->getTax();
                $this->serviceChargeServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->PATIENT_ID,
                    $this->LOCATION_ID,
                    $this->NOTES,
                    $this->ACCOUNTS_RECEIVABLE_ID,
                    $this->STATUS,
                    $this->OUTPUT_TAX_ID,
                    $this->OUTPUT_TAX_RATE,
                    $this->OUTPUT_TAX_VAT_METHOD,
                    $this->OUTPUT_TAX_ACCOUNT_ID
                );
                $this->serviceChargeServices->getUpdateTaxItem($this->ID, $this->OUTPUT_TAX_ID);
                $getResult = $this->serviceChargeServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                DB::commit();
                session()->flash('message', 'Successfully updated');
            }
            $data = $this->serviceChargeServices->get($this->ID);
            if ($data) {
                $this->getInfo($data);
            }
            $this->Modify = false;

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT            = $list['AMOUNT'];
            $this->BALANCE_DUE       = $list['BALANCE_DUE'];
            $this->OUTPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT    = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    #[On('update-status')]
    public function updateStatus()
    {
        $data = $this->serviceChargeServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    #[On('prompt-item-message')]
    public function receivedMessage($result)
    {
        if ($result['type'] == 0) {
            session()->flash('message', $result['message']);
        } else {
            session()->flash('error', $result['message']);
        }
    }
    public function updateCancel()
    {
        $data = $this->serviceChargeServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;
        $this->contactLoad();
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function updatedDate()
    {
        $this->patientList = $this->scheduleServices->ContactListFromSchedules($this->DATE, $this->LOCATION_ID);
    }
    public function updatedLocation()
    {
        $this->patientList = $this->scheduleServices->ContactListFromSchedules($this->DATE, $this->LOCATION_ID);
    }
    public function openForm()
    {
        $data = [
            'HEMO_ID'     => $this->HEMO_ID,
            'DATE'        => $this->DATE,
            'PATIENT_ID'  => $this->PATIENT_ID,
            'LOCATION_ID' => $this->LOCATION_ID,
        ];

        $this->dispatch('open-agreement-form', data: $data);
    }
    public function delete()
    {

        try {

            $ITEM_COUNT = $this->serviceChargeServices->getItemCount($this->ID);
            if ($ITEM_COUNT > 0) {
                $message = 'Invalid item must be empty';
                $result  = ['message' => $message, 'type' => 1];
                $this->dispatch('prompt-item-message', result: $result);
                return;
            }
            DB::beginTransaction();
            $this->serviceChargeServices->Delete($this->ID);
            DB::commit();
            return Redirect::route('patientsservice_charges')->with('message', 'Successfully deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {

        return view('livewire.service-charge.service-charge-form');
    }
}
