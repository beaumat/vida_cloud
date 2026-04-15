<?php

namespace App\Livewire\ChartOfAccount;

use App\Models\Accounts;
use App\Models\AccountType;
use App\Services\AccountServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Chart Of Account')]
class ChartOfAccountForm extends Component
{
    public int $ID;
    public string $NAME;
    public int $GROUP_ACCOUNT_ID;
    public int $TYPE;
    public string $BANK_ACCOUNT_NO;
    public bool $INACTIVE;
    public string $TAG;
    public int $LINE_NO;
    public $accountTypes = [];
    public $accountGroups = [];

    private $accountServices;
    public function boot(AccountServices $accountServices)
    {
        $this->accountServices = $accountServices;
    }
    public function mount($id = null)
    {
        $this->accountTypes = $this->accountServices->GetTypeList();

        $this->accountGroups = Accounts::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();

        if (is_numeric($id)) {

            $account = Accounts::where('ID', $id)->first();

            if ($account) {
                $this->ID = $account->ID;
                $this->NAME = $account->NAME;
                $this->GROUP_ACCOUNT_ID = $account->GROUP_ACCOUNT_ID > 0 ? $account->GROUP_ACCOUNT_ID : 0;
                $this->TYPE = $account->TYPE;
                $this->BANK_ACCOUNT_NO = $account->BANK_ACCOUNT_NO ? $account->BANK_ACCOUNT_NO : '';
                $this->INACTIVE = $account->INACTIVE;
                $this->TAG = $account->TAG ? $account->TAG : '';
                $this->LINE_NO = $account->LINE_NO ?? 0;
                return;
            }


            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingslocation_group')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->GROUP_ACCOUNT_ID = 0;
        $this->TYPE = -1;
        $this->BANK_ACCOUNT_NO = '';
        $this->INACTIVE = false;
        $this->TAG = '';
        $this->LINE_NO = 0;
    }
    public function save()
    {
        $this->validate(
            [
                'TAG' => 'required|max:10|unique:account,tag,' . $this->ID,
                'NAME' => 'required|max:50|unique:account,name,' . $this->ID,
                'TYPE' => 'required|integer|exists:account_type_map,id'
            ],
            [],
            [
                'TAG'   => 'Code',
                'NAME'  => 'Name',
                'TYPE'  => 'Account Type'
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $this->accountServices->Store(
                    $this->NAME,
                    $this->GROUP_ACCOUNT_ID,
                    $this->TYPE,
                    $this->BANK_ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->TAG,
                    $this->LINE_NO
                );
                session()->flash('message', 'Successfully created');
            } else {
                $this->accountServices->Update(
                    $this->ID,
                    $this->NAME,
                    $this->GROUP_ACCOUNT_ID,
                    $this->TYPE,
                    $this->BANK_ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->TAG,
                    $this->LINE_NO
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
        return view('livewire.chart-of-account.chart-of-account-form');
    }
}
