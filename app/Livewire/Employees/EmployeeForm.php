<?php

namespace App\Livewire\Employees;

use App\Models\Contacts;
use App\Models\Gender;
use App\Services\ContactServices;
use App\Services\LocationServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Employees')]
class EmployeeForm extends Component
{
    public int $LOCATION_ID;
    public int $ID;
    public int $TYPE = 2;
    public string $NAME;
    public string $COMPANY_NAME;
    public string $SALUTATION;
    public string $FIRST_NAME;
    public string $MIDDLE_NAME;
    public string $LAST_NAME;
    public string $PRINT_NAME_AS;
    public string $POSTAL_ADDRESS;
    public string $CONTACT_PERSON;
    public string $TELEPHONE_NO;
    public string $FAX_NO;
    public string $MOBILE_NO;
    public string $ALT_TELEPHONE_NO;
    public string $ALT_CONTACT_PERSON;
    public string $EMAIL;
    public string $ACCOUNT_NO;
    public bool $INACTIVE;
    public int $GROUP_ID;
    public int $PAYMENT_TERMS_ID;
    public float $CREDIT_LIMIT;
    public int $PREF_PAYMENT_METHOD_ID;
    public string $CREDIT_CARD_NO;
    public string $CREDIT_CARD_EXPIRY_DATE;
    public int $SALES_REP_ID;
    public int $PRICE_LEVEL_ID;
    public string $TAXPAYER_ID;
    public int $TAX_ID;
    public int $EW_TAX_ID;
    public string $SSS_NO;
    public int $GENDER;
    public string $DATE_OF_BIRTH;
    public string $NICKNAME;
    public string $HIRE_DATE;
    public string $PIN;
    public $taxList = [];
    public $genders = [];
    public $locationList = [];
    public string $selectTab = 'gen';
    private $contactServices;
    private $locationServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices
    ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function SelectTab($tab)
    {
        $this->selectTab = $tab;
    }

    public function mount($id = null)
    {
        $this->genders = Gender::all();
        $this->locationList = $this->locationServices->getList();

        if (is_numeric($id)) {

            $contact = $this->contactServices->get($id, $this->TYPE);

            if ($contact) {
                $this->ID = $contact->ID;
                $this->NAME = $contact->NAME;
                $this->COMPANY_NAME = $contact->COMPANY_NAME ? $contact->COMPANY_NAME : '';
                $this->SALUTATION = $contact->SALUTATION ? $contact->SALUTATION : '';
                $this->FIRST_NAME = $contact->FIRST_NAME ? $contact->FIRST_NAME : '';
                $this->MIDDLE_NAME = $contact->MIDDLE_NAME ? $contact->MIDDLE_NAME : '';
                $this->LAST_NAME = $contact->LAST_NAME ? $contact->LAST_NAME : '';
                $this->PRINT_NAME_AS = $contact->PRINT_NAME_AS ? $contact->PRINT_NAME_AS : '';
                $this->POSTAL_ADDRESS = $contact->POSTAL_ADDRESS ? $contact->POSTAL_ADDRESS : '';
                $this->CONTACT_PERSON = $contact->CONTACT_PERSON ? $contact->CONTACT_PERSON : '';
                $this->TELEPHONE_NO = $contact->TELEPHONE_NO ? $contact->TELEPHONE_NO : '';
                $this->FAX_NO = $contact->FAX_NO ? $contact->FAX_NO : '';
                $this->MOBILE_NO = $contact->MOBILE_NO ? $contact->MOBILE_NO : '';
                $this->ALT_TELEPHONE_NO = $contact->ALT_TELEPHONE_NO ? $contact->ALT_TELEPHONE_NO : '';
                $this->ALT_CONTACT_PERSON = $contact->ALT_CONTACT_PERSON ? $contact->ALT_CONTACT_PERSON : '';
                $this->EMAIL = $contact->EMAIL ? $contact->EMAIL : '';
                $this->ACCOUNT_NO = $contact->ACCOUNT_NO ? $contact->ACCOUNT_NO : '';
                $this->INACTIVE = $contact->INACTIVE;
                $this->GROUP_ID = $contact->GROUP_ID ? $contact->GROUP_ID : 0;
                $this->PAYMENT_TERMS_ID = $contact->PAYMENT_TERMS_ID ? $contact->PAYMENT_TERMS_ID : 0;
                $this->CREDIT_LIMIT = $contact->CREDIT_LIMIT ? $contact->CREDIT_LIMIT : 0;
                $this->PREF_PAYMENT_METHOD_ID = $contact->PREF_PAYMENT_METHOD_ID ? $contact->PREF_PAYMENT_METHOD_ID : 0;
                $this->CREDIT_CARD_NO = $contact->CREDIT_CARD_NO ? $contact->CREDIT_CARD_NO : '';
                $this->CREDIT_CARD_EXPIRY_DATE = $contact->CREDIT_CARD_EXPIRY_DATE ? $contact->CREDIT_CARD_EXPIRY_DATE : '';
                $this->SALES_REP_ID = $contact->SALES_REP_ID ? $contact->SALES_REP_ID : 0;
                $this->PRICE_LEVEL_ID = $contact->PRICE_LEVEL_ID ? $contact->PRICE_LEVEL_ID : 0;
                $this->TAXPAYER_ID = $contact->TAXPAYER_ID ? $contact->TAXPAYER_ID : '';
                $this->TAX_ID = $contact->TAX_ID ? $contact->TAX_ID : 0;
                $this->EW_TAX_ID = $contact->EW_TAX_ID ? $contact->EW_TAX_ID : 0;
                $this->SSS_NO = $contact->SSS_NO ? $contact->SSS_NO : 0;
                $this->GENDER = $contact->GENDER ? $contact->GENDER : -1;
                $this->DATE_OF_BIRTH = $contact->DATE_OF_BIRTH ? $contact->DATE_OF_BIRTH : '';
                $this->NICKNAME = $contact->NICKNAME ? $contact->NICKNAME : '';
                $this->HIRE_DATE = $contact->HIRE_DATE ? $contact->HIRE_DATE : '';
                $this->PIN = $contact->PIN ?  $contact->PIN : '';
                $this->LOCATION_ID = $contact->LOCATION_ID ?? 0;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancecontactemployees')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->COMPANY_NAME = '';
        $this->SALUTATION = '';
        $this->FIRST_NAME = '';
        $this->MIDDLE_NAME = '';
        $this->LAST_NAME = '';
        $this->PRINT_NAME_AS = '';
        $this->POSTAL_ADDRESS = '';
        $this->CONTACT_PERSON = '';
        $this->TELEPHONE_NO = '';
        $this->FAX_NO = '';
        $this->MOBILE_NO = '';
        $this->ALT_TELEPHONE_NO = '';
        $this->ALT_CONTACT_PERSON = '';
        $this->EMAIL = '';
        $this->ACCOUNT_NO = '';
        $this->INACTIVE = false;
        $this->GROUP_ID = 0;
        $this->PAYMENT_TERMS_ID = 0;
        $this->CREDIT_LIMIT = 0;
        $this->PREF_PAYMENT_METHOD_ID = 0;
        $this->CREDIT_CARD_NO = '';
        $this->CREDIT_CARD_EXPIRY_DATE = '';
        $this->SALES_REP_ID = 0;
        $this->PRICE_LEVEL_ID = 0;
        $this->TAXPAYER_ID = '';
        $this->TAX_ID = 0;
        $this->EW_TAX_ID = 0;
        $this->SSS_NO = 0;
        $this->GENDER = -1;
        $this->DATE_OF_BIRTH = '';
        $this->NICKNAME = '';
        $this->HIRE_DATE = '';
        $this->PIN = '';
        $this->LOCATION_ID = 0;
    }
    public function updatedName()
    {
        if ($this->ID > 0) {

            return;
        }

        $this->PRINT_NAME_AS = $this->NAME;
    }
    public function save()
    {
        $this->validate(
            [
                'NAME' => 'required|max:100|unique:contact,name,' . $this->ID,
                'LOCATION_ID' => 'required|exists:location,id',
                'PRINT_NAME_AS' => 'required|max:100',

            ],
            [],
            [
                'NAME' => 'Name',
                'LOCATION_ID' => 'Location',
                'PRINT_NAME_AS' => 'Print As',
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $this->contactServices->Store(
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME,
                    $this->SALUTATION,
                    $this->FIRST_NAME,
                    $this->MIDDLE_NAME,
                    $this->LAST_NAME,
                    $this->PRINT_NAME_AS,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE
                );

                $this->UpdateLocation($this->ID, $this->LOCATION_ID);
                $this->contactServices->UpdatePin($this->ID, $this->PIN);
                Redirect::route('maintenancecontactemployees_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->contactServices->Update(
                    $this->ID,
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME,
                    $this->SALUTATION,
                    $this->FIRST_NAME,
                    $this->MIDDLE_NAME,
                    $this->LAST_NAME,
                    $this->PRINT_NAME_AS,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE
                );
                $this->UpdateLocation($this->ID, $this->LOCATION_ID);
                $this->contactServices->UpdatePin($this->ID, $this->PIN);
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function UpdateLocation(int $ID, int $LOCATION_ID)
    {
        Contacts::where('ID', '=', $ID)->update(['LOCATION_ID' => $LOCATION_ID]);
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
        return view('livewire.employees.employee-form');
    }
}
