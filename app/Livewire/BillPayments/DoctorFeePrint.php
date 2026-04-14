<?php

namespace App\Livewire\BillPayments;

use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use App\Services\PaymentPeriodServices;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Print Doctors Fee')]
class DoctorFeePrint extends Component
{

    public int $n = 0;
    public $ADMINISTRATOR_NAME;
    public $REPORT_HEADER_1;
    public $REPORT_HEADER_2;
    public $REPORT_HEADER_3;
    public $DOCTOR_NAME;
    public $LOCATION_NAME;
    public float $TOTAL_TREATMENT = 0;
    public float $TOTAL_AMOUNT = 0;
    public string $USER_NAME;
    public string $PERIOD;
    public $LOGO_FILE;
    public $billList = [];
    private $billPaymentServices;
    private $contactServices;
    private $locationServices;
    private $paymentPeriodServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        PaymentPeriodServices $paymentPeriodServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->paymentPeriodServices = $paymentPeriodServices;
    }
    public function mount($id = null)
    {
        try {
            $data = $this->billPaymentServices->Get($id);
            if ($data) {
                $con = $this->contactServices->get($data->PAY_TO_ID, 4);
                if ($con) {
                    // get doctor name
                    $this->DOCTOR_NAME = $con->NAME ?? '';
                }

                $period = $this->paymentPeriodServices->get($data->PF_PERIOD_ID);
                if ($period) {
                    $this->PERIOD = date('m/d/Y', strtotime($period->DATE_FROM)) . ' - ' . date('m/d/Y', strtotime($period->DATE_TO));
                }

                $locData = $this->locationServices->get($data->LOCATION_ID);
                if ($locData) {
                    $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                    $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                    $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                    $this->LOCATION_NAME = $locData->NAME;
                    $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                }

                $this->billList = $this->billPaymentServices->billPaymentBIllsPatientList($id);

                $conUser = $this->contactServices->get(Auth::user()->contact_id ?? 0, 2); // Employee
                if ($conUser) {
                    $this->USER_NAME = $conUser->PRINT_NAME_AS ?? '';
                }
                $conAdmin = $this->contactServices->get($locData->HCI_MANAGER_ID ?? 0, 2); // Employee
                if ($conAdmin) {
                    $this->ADMINISTRATOR_NAME = $conAdmin->PRINT_NAME_AS ?? '';
                }
                $this->dispatch('preview_print');
            }
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.bill-payments.doctor-fee-print');
    }
}
