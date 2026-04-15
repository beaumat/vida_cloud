<?php

namespace App\Livewire\PaymentMethod;

use App\Models\Accounts;
use App\Models\PaymentMethods;
use App\Models\PaymentTypes;
use App\Services\PaymentMethodServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Method')]
class PaymentMethodForm extends Component
{
    public $paymentTypes = [];
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $PAYMENT_TYPE;
    public int $GL_ACCOUNT_ID;
    public $accountList = [];
    private $paymentMethodServices;
    public function boot(PaymentMethodServices $paymentMethodServices)
    {
        $this->paymentMethodServices = $paymentMethodServices;
    }
    public function mount($id = null)
    {
        $this->paymentTypes = PaymentTypes::all();
        $this->accountList = Accounts::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();

        if (is_numeric($id)) {

            $paymenthMethod = PaymentMethods::where('ID', $id)->first();

            if ($paymenthMethod) {
                $this->ID = $paymenthMethod->ID;
                $this->CODE = $paymenthMethod->CODE;
                $this->DESCRIPTION = $paymenthMethod->DESCRIPTION;
                $this->PAYMENT_TYPE = $paymenthMethod->PAYMENT_TYPE;
                $this->GL_ACCOUNT_ID = $paymenthMethod->GL_ACCOUNT_ID ? $paymenthMethod->GL_ACCOUNT_ID : 0;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancefinancialpayment_method')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->PAYMENT_TYPE = 0;
        $this->GL_ACCOUNT_ID = 0;
    }


    public function save()
    {
        $this->validate(
            [    
                'DESCRIPTION' => 'required|max:100|unique:payment_method,description,' . $this->ID,
                'PAYMENT_TYPE' => 'required',
            ],
            [],
            [     
                'DESCRIPTION' => 'Description',
                'PAYMENT_TYPE' => 'Payment Type',
            ]
        );


        try {

            if ($this->ID == 0) {
                $this->ID = $this->paymentMethodServices->Store($this->CODE, $this->DESCRIPTION, $this->PAYMENT_TYPE, $this->GL_ACCOUNT_ID);
                return Redirect::route('maintenancefinancialpayment_method_edit',['id'=> $this->ID])->with('message', 'Successfully created');
            
            } else {
                $this->paymentMethodServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->PAYMENT_TYPE, $this->GL_ACCOUNT_ID);
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
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
        return view('livewire.payment-method.payment-method-form');
    }
}
