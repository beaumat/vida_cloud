<?php

namespace App\Livewire\PatientPayment;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Patient: Cash/GL Payment')]
class PatientPaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public bool $isDesc = true;
    public int $perPage = 50;
    public string $sortby = 'patient_payment.ID';
    public int $locationid;
    public bool $itemized = true;
    public $locationList = [];
    public int $paymentMethodId = 0;
    public $paymentMethodList = [];
    private $patientPaymentServices;
    private $locationServices;
    private $userServices;
    private $uploadServices;
    private $paymentMethodServices;
    private $dateServices;
    public string $DATE_FROM;
    public string $DATE_TO;
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices,
        PaymentMethodServices    $paymentMethodServices,
        DateServices $dateServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->itemized = false;
        $this->paymentMethodId = 0;
        $this->paymentMethodList = $this->paymentMethodServices->getPaymentMethodViaPatientPayment();
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();

        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
    }
    public function delete($id)
    {

        if ($this->patientPaymentServices->ChargesAreAlreadyExists($id)) {
            session()->flash('error', 'This payment cannot be deleted because it has already been applied.');
            return;
        }


        try {
            DB::beginTransaction();
            $data = $this->patientPaymentServices->get($id);
            if ($data) {
                $this->uploadServices->RemoveIfExists($data->FILE_PATH);
                $this->patientPaymentServices->Delete($data->ID);
                session()->flash('message', 'Successfully deleted.');
                DB::commit();
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
        $dataList = $this->patientPaymentServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->sortby,
            $this->isDesc,
            $this->paymentMethodId,
            $this->itemized,
            $this->DATE_FROM,
            $this->DATE_TO
        );



        return view('livewire.patient-payment.patient-payment-list', ['dataList' => $dataList]);
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
}
