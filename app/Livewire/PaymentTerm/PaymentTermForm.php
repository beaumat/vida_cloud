<?php

namespace App\Livewire\PaymentTerm;

use App\Models\PaymentTerms;
use App\Models\PaymentTermType;
use App\Services\DateServices;
use App\Services\PaymentTermServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Terms')]
class PaymentTermForm extends Component
{
    public $paymentTermTypes = [];
    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public int $TYPE;
    public int $NET_DUE;
    public float $DISCOUNT_PCT;
    public int $DISCOUNT_DUE;
    public int $DATE_MONTH_PARAM;
    public int $DATE_DAY_PARAM;
    public int $DATE_MIN_DAYS;
    public bool $INACTIVE;

    public $weeklyList = [];
    public $semiMonthly = [];
    public $dayList = [];
    public $monthList = [];
    public $semiAnnualList = [];
    public function mount($id = null, DateServices $dateServices)
    {
        $this->weeklyList = $dateServices->WeeklyList();
        $this->semiMonthly = $dateServices->SemiMonthly();
        $this->dayList = $dateServices->DayList();
        $this->semiAnnualList = $dateServices->SemiAnnual();
        $this->monthList = $dateServices->MonthList();
        $this->paymentTermTypes = PaymentTermType::all();

        if (is_numeric($id)) {

            $paymentTerms = PaymentTerms::where('ID', $id)->first();

            if ($paymentTerms) {
                $this->ID = $paymentTerms->ID;
                $this->CODE = $paymentTerms->CODE;
                $this->DESCRIPTION = $paymentTerms->DESCRIPTION;
                $this->TYPE = $paymentTerms->TYPE;
                $this->NET_DUE = $paymentTerms->NET_DUE ?  $paymentTerms->NET_DUE : 0;
                $this->DISCOUNT_PCT = $paymentTerms->DISCOUNT_PCT ? $paymentTerms->DISCOUNT_PCT : 0;
                $this->DISCOUNT_DUE = $paymentTerms->DISCOUNT_DUE ? $paymentTerms->DISCOUNT_DUE : 0;
                $this->DATE_MONTH_PARAM = $paymentTerms->DATE_MONTH_PARAM ? $paymentTerms->DATE_MONTH_PARAM : 0;
                $this->DATE_DAY_PARAM  = $paymentTerms->DATE_DAY_PARAM ? $paymentTerms->DATE_DAY_PARAM : 0;
                $this->DATE_MIN_DAYS = $paymentTerms->DATE_MIN_DAYS ? $paymentTerms->DATE_MIN_DAYS : 0;
                $this->INACTIVE = $paymentTerms->INACTIVE;

                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancefinancialpayment_term')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
        $this->TYPE = 0;
        $this->NET_DUE = 0;
        $this->DISCOUNT_PCT = 0;
        $this->DISCOUNT_DUE = 0;
        $this->DATE_MONTH_PARAM = 0;
        $this->DATE_DAY_PARAM  = 0;
        $this->DATE_MIN_DAYS = 0;
        $this->INACTIVE = false;
    }


    public function save(PaymentTermServices $paymentTermServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:payment_terms,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:100|unique:payment_terms,description,' . $this->ID,
                'TYPE' => 'required',
            ],
            [],
            [
                'CODE' => 'Code',
                'DESCRIPTION' => 'Description',
                'TYPE' => 'Type',
            ]
        );


        try {

            if ($this->ID === 0) {
                $this->ID = $paymentTermServices->Store($this->CODE, $this->DESCRIPTION, $this->TYPE, $this->NET_DUE, $this->DISCOUNT_PCT, $this->DISCOUNT_DUE, $this->DATE_MONTH_PARAM, $this->DATE_DAY_PARAM, $this->DATE_MIN_DAYS, $this->INACTIVE);
                session()->flash('message', 'Successfully created.');
            } else {
                $paymentTermServices->Update($this->ID, $this->CODE, $this->DESCRIPTION, $this->TYPE, $this->NET_DUE, $this->DISCOUNT_PCT, $this->DISCOUNT_DUE, $this->DATE_MONTH_PARAM, $this->DATE_DAY_PARAM, $this->DATE_MIN_DAYS, $this->INACTIVE);
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
        return view('livewire.payment-term.payment-term-form');
    }
}
