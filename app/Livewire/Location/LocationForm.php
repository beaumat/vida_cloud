<?php

namespace App\Livewire\Location;

use App\Models\LocationGroup;
use App\Models\PriceLevels;
use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\TaxServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location - Form')]
class LocationForm extends Component
{
    public int $ID;
    public string $NAME;
    public bool $INACTIVE;
    public int $PRICE_LEVEL_ID;
    public int $GROUP_ID;
    public $priceLevels = [];
    public $locationGroups = [];

    public int $HCI_MANAGER_ID;
    public int $PHIC_INCHARGE_ID;
    public int $PHIC_INCHARGE2_ID;
    public string $NAME_OF_BUSINESS;
    public string $ACCREDITATION_NO;
    public string $BLDG_NAME_LOT_BLOCK;
    public string $STREET_SUB_VALL;
    public string $BRGY_CITY_MUNI;
    public string $PROVINCE;
    public string $ZIP_CODE;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public string $PHIC_SOA_FORMAT;
    public bool $PHIC_FORM_MODIFY;
    public bool $IS_DAILY = false;
    public bool $USED_DRY_WEIGHT = false;
    public string $LOGO_FILE = '';
    public string $DOCTOR_ORDER_DEFAULT;
    public bool $OTHER_SIGN;
    public int $PREPARED_BY_ID;
    public int $HD_FACILITY_REP_ID;
    public int $HCI_MANAGER_TREATMENT_ID;
    public bool $ITEMIZED_BASE;
    public bool $LEAVE_BLANK_AG_ADMIN_OFFICE_FEE;
    public int $PF_TAX_ID;

    public $managerList = [];
    public $inchargeList = [];
    public $physicianList = [];
    public $preparedByList = [];
    public $hdFacilityRepList = [];
    public $taxList =[];
    private $locationServices;
    private $contactServices;
    private $taxServices;
    public function boot(LocationServices $locationServices, ContactServices $contactServices, TaxServices  $taxServices)
    {
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->taxServices = $taxServices;
    
    }
    public function loadDropDown()
    {
        $this->priceLevels = PriceLevels::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->where('TYPE', '1')->get();
        $this->locationGroups = LocationGroup::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $contactList = $this->contactServices->getList(2);
        $this->managerList = $contactList;
        $this->inchargeList = $contactList;
        $this->physicianList = $contactList;
        $this->preparedByList = $contactList;
        $this->hdFacilityRepList = $contactList;
        $this->taxList = $this->taxServices->getWTax();
    }
    public function mount($id = null)
    {

        $this->loadDropDown();
        if (is_numeric($id)) {
            $data = $this->locationServices->get($id);
            if ($data) {
                $this->ID = $data->ID;
                $this->NAME = $data->NAME;
                $this->INACTIVE = $data->INACTIVE;
                $this->PRICE_LEVEL_ID = $data->PRICE_LEVEL_ID ? $data->PRICE_LEVEL_ID : 0;
                $this->GROUP_ID = $data->GROUP_ID ? $data->GROUP_ID : 0;
                $this->HCI_MANAGER_ID = $data->HCI_MANAGER_ID ?? 0;
                $this->PHIC_INCHARGE_ID = $data->PHIC_INCHARGE_ID ?? 0;
                $this->PHIC_INCHARGE2_ID = $data->PHIC_INCHARGE2_ID ?? 0;
                $this->NAME_OF_BUSINESS = $data->NAME_OF_BUSINESS ?? '';
                $this->ACCREDITATION_NO = $data->ACCREDITATION_NO ?? '';
                $this->BLDG_NAME_LOT_BLOCK = $data->BLDG_NAME_LOT_BLOCK ?? '';
                $this->STREET_SUB_VALL = $data->STREET_SUB_VALL ?? '';
                $this->BRGY_CITY_MUNI = $data->BRGY_CITY_MUNI ?? '';
                $this->PROVINCE = $data->PROVINCE ?? '';
                $this->ZIP_CODE = $data->ZIP_CODE ?? '';
                $this->REPORT_HEADER_1 = $data->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $data->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $data->REPORT_HEADER_3 ?? '';
                $this->PHIC_SOA_FORMAT = $data->PHIC_SOA_FORMAT ?? '';
                $this->PHIC_FORM_MODIFY = $data->PHIC_FORM_MODIFY ?? false;
                $this->IS_DAILY = $data->IS_DAILY ?? false;
                $this->LOGO_FILE = $data->LOGO_FILE ?? '';
                $this->USED_DRY_WEIGHT = $data->USED_DRY_WEIGHT ?? false;
                $this->DOCTOR_ORDER_DEFAULT = $data->DOCTOR_ORDER_DEFAULT ?? '';
                $this->OTHER_SIGN = $data->OTHER_SIGN ?? false;
                $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
                $this->HD_FACILITY_REP_ID = $data->HD_FACILITY_REP_ID ?? 0;
                $this->ITEMIZED_BASE = $data->ITEMIZED_BASE ?? false;
                $this->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE = $data->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE ?? false;
                $this->PF_TAX_ID = $data->PF_TAX_ID ?? 0;
                $this->HCI_MANAGER_TREATMENT_ID = $data->HCI_MANAGER_TREATMENT_ID ?? 0;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingslocation')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->NAME = '';
        $this->PRICE_LEVEL_ID = 0;
        $this->GROUP_ID = 0;
        $this->INACTIVE = false;
        $this->HCI_MANAGER_ID = 0;
        $this->PHIC_INCHARGE_ID = 0;
        $this->PHIC_INCHARGE2_ID = 0;
        $this->NAME_OF_BUSINESS = '';
        $this->ACCREDITATION_NO = '';
        $this->BLDG_NAME_LOT_BLOCK = '';
        $this->STREET_SUB_VALL = '';
        $this->BRGY_CITY_MUNI = '';
        $this->PROVINCE = '';
        $this->ZIP_CODE = '';
        $this->REPORT_HEADER_1 = '';
        $this->REPORT_HEADER_2 = '';
        $this->REPORT_HEADER_3 = '';
        $this->PHIC_SOA_FORMAT = '';
        $this->PHIC_FORM_MODIFY = false;
        $this->IS_DAILY = false;
        $this->LOGO_FILE = '';
        $this->USED_DRY_WEIGHT = false;
        $this->DOCTOR_ORDER_DEFAULT = '';
        $this->OTHER_SIGN = false;
        $this->PREPARED_BY_ID = 0;
        $this->HD_FACILITY_REP_ID = 0;
        $this->ITEMIZED_BASE = false;
        $this->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE = false;
        $this->PF_TAX_ID = 0;
        $this->HCI_MANAGER_TREATMENT_ID = 0;
    }

    public function save()
    {
        $this->validate([
            'NAME' => 'required|max:50|unique:location,name,' . $this->ID
        ], [], [
            'NAME' => 'Name'
        ]);

        try {
            if ($this->ID === 0) {
                $this->ID = $this->locationServices->Store(
                    $this->NAME,
                    $this->INACTIVE,
                    $this->PRICE_LEVEL_ID,
                    $this->GROUP_ID,
                    $this->HCI_MANAGER_ID,
                    $this->PHIC_INCHARGE_ID,
                    $this->NAME_OF_BUSINESS,
                    $this->ACCREDITATION_NO,
                    $this->BLDG_NAME_LOT_BLOCK,
                    $this->STREET_SUB_VALL,
                    $this->BRGY_CITY_MUNI,
                    $this->PROVINCE,
                    $this->ZIP_CODE,
                    $this->REPORT_HEADER_1,
                    $this->REPORT_HEADER_2,
                    $this->REPORT_HEADER_3,
                    $this->PHIC_SOA_FORMAT,
                    $this->PHIC_FORM_MODIFY,
                    $this->IS_DAILY,
                    $this->LOGO_FILE,
                    $this->USED_DRY_WEIGHT,
                    $this->DOCTOR_ORDER_DEFAULT,
                    $this->OTHER_SIGN,
                    $this->PREPARED_BY_ID,
                    $this->HD_FACILITY_REP_ID,
                    $this->ITEMIZED_BASE,
                    $this->PHIC_INCHARGE2_ID,
                    $this->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
                    $this->PF_TAX_ID,
                    $this->HCI_MANAGER_TREATMENT_ID
                );

                Redirect::route('maintenancesettingslocation_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->locationServices->Update(
                    $this->ID,
                    $this->NAME,
                    $this->INACTIVE,
                    $this->PRICE_LEVEL_ID,
                    $this->GROUP_ID,
                    $this->HCI_MANAGER_ID,
                    $this->PHIC_INCHARGE_ID,
                    $this->NAME_OF_BUSINESS,
                    $this->ACCREDITATION_NO,
                    $this->BLDG_NAME_LOT_BLOCK,
                    $this->STREET_SUB_VALL,
                    $this->BRGY_CITY_MUNI,
                    $this->PROVINCE,
                    $this->ZIP_CODE,
                    $this->REPORT_HEADER_1,
                    $this->REPORT_HEADER_2,
                    $this->REPORT_HEADER_3,
                    $this->PHIC_SOA_FORMAT,
                    $this->PHIC_FORM_MODIFY,
                    $this->IS_DAILY,
                    $this->LOGO_FILE,
                    $this->USED_DRY_WEIGHT,
                    $this->DOCTOR_ORDER_DEFAULT,
                    $this->OTHER_SIGN,
                    $this->PREPARED_BY_ID,
                    $this->HD_FACILITY_REP_ID,
                    $this->ITEMIZED_BASE,
                    $this->PHIC_INCHARGE2_ID,
                    $this->LEAVE_BLANK_AG_ADMIN_OFFICE_FEE,
                    $this->PF_TAX_ID,
                    $this->HCI_MANAGER_TREATMENT_ID
                );
                session()->flash('message', 'Successfully updated');
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
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.location.location-form');
    }
}
