<?php

namespace App\Livewire\ReceiveMoney;

use App\Services\AccountServices;
use App\Services\ReceiveMoneyServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ReceiveMoneyDetails extends Component
{
    #[Reactive()]
    public int $STATUS;
    #[Reactive()]
    public int $LOCATION_ID;

    #[Reactive()]
    public int $RECEIVE_MONEY_ID;
    public int $ACCOUNT_ID = 0;
    public float $AMOUNT = 0.0;
    public string $NOTES = '';

    public $editID = null;
    public int $editACCOUNT_ID = 0;
    public float $editAMOUNT = 0.0;
    public string $editNOTES = '';


    public $dataList = [];
    public $accountList = [];
    public bool $saveSuccess = false;
    public bool $codeBase = false;
    
    public float $TOTAL_AMOUNT = 0.0;
    public $acctCodeList = [];
    public $acctDescList = [];
    public string $ACCOUNT_CODE;
    public string $ACCOUNT_DESCRIPTION;
    private $receiveMoneyServices;
    private $accountServices;
    public function boot(ReceiveMoneyServices $receiveMoneyServices, AccountServices $accountServices)
    {

        $this->receiveMoneyServices = $receiveMoneyServices;
        $this->accountServices = $accountServices;
    }
    public function mount( $TOTAL = 0 )
    {   
        $this->TOTAL_AMOUNT = $TOTAL;
        $this->updatedcodebase();
    }

    public function save()
    {
        $this->validate(
            [
                'ACCOUNT_ID' => 'required',
                'AMOUNT' => 'required|numeric|min:0',
                'NOTES' => 'nullable|string|max:255',
            ],
            [],
            [
                'ACCOUNT_ID' => 'Account',
                'AMOUNT' => 'Amount',
                'NOTES' => 'Notes',
            ]
        );

        try {
            $this->receiveMoneyServices->StoreDetails(
                $this->RECEIVE_MONEY_ID,
                $this->ACCOUNT_ID,
                $this->AMOUNT,
                $this->NOTES
            );

           $this->TOTAL_AMOUNT = $this->receiveMoneyServices->ReCalculate($this->RECEIVE_MONEY_ID);
           $this->clearData();
        } catch (\Throwable $th) {
            //throw $th;
            session()->flash('error', 'Error: ' . $th->getMessage());
        }



    }
    public function editCancel()
    {
        $this->editID = null;
    }
    private function clearData()
    {
        $this->ACCOUNT_ID = 0;
        $this->AMOUNT = 0.0;
        $this->NOTES = '';
        $this->saveSuccess = $this->saveSuccess ? false : true;
        $this->ACCOUNT_CODE = '';
        $this->ACCOUNT_DESCRIPTION = '';
        $this->updatedcodebase();
    }
    public function edit(int $ID)
    {
        $data = $this->receiveMoneyServices->getDetails($ID);

        if ($data) {
            $this->editID = $data->ID;
            $this->editACCOUNT_ID = $data->ACCOUNT_ID;
            $this->editAMOUNT = $data->AMOUNT;
            $this->editNOTES = $data->NOTES;
        }
    }
    public function update()
    {
        $this->receiveMoneyServices->UpdateDetails( $this->editID, $this->editACCOUNT_ID, $this->editAMOUNT, $this->editNOTES );
        $this->editID = null;
        $this->TOTAL_AMOUNT = $this->receiveMoneyServices->ReCalculate($this->RECEIVE_MONEY_ID);
    }
    public function delete(int $ID)
    {
        $this->receiveMoneyServices->DeleteDetails($ID);
        $this->TOTAL_AMOUNT = $this->receiveMoneyServices->ReCalculate($this->RECEIVE_MONEY_ID);
    }
    public function updatedaccountid()
    {
        $acct = $this->accountServices->get($this->ACCOUNT_ID);

        if ($acct) {
            $this->ACCOUNT_CODE = $acct->TAG ? $acct->TAG : '';
            $this->ACCOUNT_DESCRIPTION = $acct->NAME;
            $this->NOTES = '';
        }
    }
    public function updatedcodebase()
    {
        if ($this->codeBase == true) {
            return $this->acctCodeList = $this->accountServices->getAccount(true);
        }
        return $this->acctDescList = $this->accountServices->getAccount(false);
    }
    public function render()
    {
        $this->dataList = $this->receiveMoneyServices->getDetailsList($this->RECEIVE_MONEY_ID);
        return view('livewire.receive-money.receive-money-details');
    }
}
