<?php
namespace App\Livewire\Hemodialysis;

use App\Exports\TreatmentListExport;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\ScheduleServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Hemodialysis Treatment')]
class HemoList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString     = ['search' => ['except' => '']];
    public $search             = '';
    public $statusList         = [];
    public int $perPage        = 100;
    public int $locationid;

    public $locationList = [];
    private $hemoServices;
    private $locationServices;
    private $userServices;
    public $dataList = [];

    public string $DATE_FROM;
    private $dateServices;
    private $scheduleServices;
    public function boot(
        HemoServices $hemoServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        ScheduleServices $scheduleServices
    ) {
        $this->hemoServices     = $hemoServices;
        $this->locationServices = $locationServices;
        $this->userServices     = $userServices;
        $this->dateServices     = $dateServices;
        $this->scheduleServices = $scheduleServices;
    }
    #[On('upload-alert')]
    public function AlertMsg($data)
    {
        session()->flash('message', $data);
    }
    public function delete(int $ID)
    {
        DB::beginTransaction();
        try {
            $data = $this->hemoServices->Get($ID);
            if ($data) {
                $this->hemoServices->StatusUpdate($data->ID, 3);
                $this->scheduleServices->StatusUpdate($data->CUSTOMER_ID, $data->DATE, $data->LOCATION_ID, 3);
                DB::commit();
                session()->flash('message', 'Successfully void.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function showNotes(int $ID, string $NAME)
    {

        $data = ['HEMO_ID' => $ID, 'PATIENT_NAME' => $NAME];
        $this->dispatch('open-nurse-notes', result: $data);
    }
    public function mount()
    {
        $this->DATE_FROM    = $this->userServices->getTransactionDateDefault();
        $this->DATE_TO      = $this->dateServices->NowDate();
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
        $this->statusList   = $this->hemoServices->HemoStatus();
        $this->refreshList();
    }
    public function refreshList()
    {
        $this->dispatch('refresh-list');
    }
    public function clickOn()
    {
        $this->hemoServices->FixTreatmentNumberFromStart($this->locationid);
    }
    public function exportData()
    {
        $dataList = $this->hemoServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->DATE_FROM == '' ? $this->dateServices->NowDate() : $this->DATE_FROM

        );
        $this->dispatch('refresh-list');
        return Excel::download(new TreatmentListExport($dataList), 'hemo-treatment-.xlsx');

    }
    public function updatedDateFrom()
    {
        $this->refreshList();
    }
    public function updatedlocationid()
    {
        try {

            $this->userServices->SwapLocation($this->locationid);
            $this->refreshList();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function unposted(int $ID)
    {
        DB::beginTransaction();
        try {

            $data = $this->hemoServices->Get($ID);
            if ($data) {
                $this->hemoServices->StatusUpdate($data->ID, 4);
                $this->scheduleServices->StatusUpdate(
                    $data->CUSTOMER_ID,
                    $data->DATE,
                    $data->LOCATION_ID,
                    0
                );
                DB::commit();
                session()->flash('message', 'Unpost successfully');
                $this->refreshList();
            }

        } catch (\Throwable $ex) {
            DB::rollBack();
            session()->flash('error', "Error :" . $ex->getMessage());
        }

    }
    public int $count = 0;
    #[On('refresh-list')]
    public function handleRefresh()
    {
        $this->count = 0;

        $this->dataList = $this->hemoServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->DATE_FROM == '' ? $this->dateServices->NowDate() : $this->DATE_FROM

        );

    }
    public function updatedsearch()
    {
        $this->refreshList();
    }

    public function render()
    {
        return view('livewire.hemodialysis.hemo-list');
    }
}
