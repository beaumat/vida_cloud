<?php
namespace App\Livewire\Patient;

use App\Exports\PatientListExport;
use App\Services\ContactRequirementServices;
use App\Services\ContactServices;
use App\Services\DoctorLocationServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Patients')]
class PatientList extends Component
{
    public $contacts = [];

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search       = '';
    public int $perPage  = 25;
    public $locationList = [];
    public $doctorList   = [];
    public int $locationid;
    public int $doctorid;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $doctorLocationServices;
    private $patientDoctorServices;
    private $contactRequirementServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DoctorLocationServices $doctorLocationServices,
        PatientDoctorServices $patientDoctorServices,
        ContactRequirementServices $contactRequirementServices
    ) {
        $this->contactServices            = $contactServices;
        $this->locationServices           = $locationServices;
        $this->userServices               = $userServices;
        $this->doctorLocationServices     = $doctorLocationServices;
        $this->patientDoctorServices      = $patientDoctorServices;
        $this->contactRequirementServices = $contactRequirementServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
        $this->doctorid     = 0;
    }
    public function delete($id)
    {
        try {

            $this->contactRequirementServices->DeletePatient($id);
            $this->patientDoctorServices->DeletePatient($id);
            $this->contactServices->Delete($id);

            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updatedlocationid()
    {
        $this->doctorid = 0;
        try {
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function export()
    {
        return Excel::download(new PatientListExport(
            $this->contactServices,
            $this->doctorid,
            $this->locationid,
            $this->search,
            $this->sortby,
            $this->isDesc
        ), 'patient-list.xlsx');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public bool $isDesc = true;
    public string $sortby = 'contact.ID';
    public function sorting(string $column)
    {
        if ($this->sortby == $column) {
            $this->isDesc = $this->isDesc ? false : true;
            return;
        }
        $this->isDesc = true;
        $this->sortby = $column;
    }
    public function render()
    {
        $this->doctorList = $this->doctorLocationServices->ViewList($this->locationid);

        $dataList = $this->contactServices->SearchPatient2($this->search, $this->perPage, $this->locationid, $this->sortby, $this->isDesc, $this->doctorid);

        return view('livewire.patient.patient-list', ['dataList' => $dataList]);
    }
}
