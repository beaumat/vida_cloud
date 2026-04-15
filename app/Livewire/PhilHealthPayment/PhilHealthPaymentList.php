<?php

namespace App\Livewire\PhilHealthPayment;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\PhilHealthServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Patient: Philhealth Payment Notes')]
class PhilHealthPaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public bool $isDesc = true;
    public int $perPage = 50;
    public string $sortby = 'patient_payment.ID';
    public int $locationid;
    public $locationList = [];
    private $patientPaymentServices;
    private $locationServices;
    private $userServices;
    private $uploadServices;
    private $paymentMethodServices;
    private $philHealthServices;
    private $dateServices;
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices,
        PaymentMethodServices    $paymentMethodServices,
        DateServices $dateServices,
        PhilHealthServices $philHealthServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->dateServices = $dateServices;
        $this->philHealthServices = $philHealthServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        if ($this->patientPaymentServices->ChargesAreAlreadyExists($id)) {
            session()->flash('error', 'This payment cannot be deleted because it has already been applied.');
            return;
        }

        try {
            $data = $this->patientPaymentServices->get($id);
            if ($data) {
                DB::beginTransaction();
                $this->patientPaymentServices->Delete($data->ID);
                $TotalPaid = (float) $this->patientPaymentServices->getSumOnPhilHealth(
                    $data->PATIENT_ID,
                    $data->LOCATION_ID,
                    $data->PHILHEALTH_ID
                );
                $this->philHealthServices->UpdatePayment($data->PHILHEALTH_ID, $TotalPaid);
                DB::commit();
                session()->flash('message', 'Successfully deleted.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function getConfirm($id)
    {
        $this->patientPaymentServices->ConfirmProccess($id);
    }
    public function reloadList()
    {
        $this->dispatch('reload-list');
    }
    public function openPayment(int $CONTACT_ID)
    {
        $data = [
            'CONTACT_ID' => $CONTACT_ID
        ];
        $this->dispatch('open-assistance', result: $data);
    }
    public function sorting(string $column)
    {
        if ($this->sortby  == $column) {
            $this->isDesc = $this->isDesc ? false : true;
            return;
        }
        $this->isDesc = true;
        $this->sortby = $column;
    }
    #[On('reload-list')]
    public function render()
    {
        $dataList = $this->patientPaymentServices->SearchPhilheatlh(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->sortby,
            $this->isDesc,
        );


        return view('livewire.phil-health-payment.phil-health-payment-list', ['dataList' => $dataList]);
    }
}
