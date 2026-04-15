<?php

namespace App\Livewire\DoctorBatchPayment;

use App\Services\DoctorBatchServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Doctor Batch Payment')]
class DoctorBatchList extends Component
{
    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $doctorBatchServices;
    private $locationServices;
    private $userServices;
    private $accountJournalServices;
    public function boot(
        DoctorBatchServices $doctorBatchServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->doctorBatchServices = $doctorBatchServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;

    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
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
    public function delete($id)
    {
        try {
            //code...
            DB::beginTransaction();
            $this->doctorBatchServices->Delete($id);
            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function render()
    {
        $dataList = $this->doctorBatchServices->Search($this->search, $this->locationid);
        return view('livewire.doctor-batch-payment.doctor-batch-list', ['dataList' => $dataList]);
    }
}
