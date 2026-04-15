<?php

namespace App\Livewire\Employees;

use App\Exports\EmployeeListExport;
use App\Services\ContactServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Employees')]
class EmployeeList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 50;
    public int $locationid = 0;
    public  $locationList = [];
    private $contactServices;
    private $locationServices;
    public function boot(ContactServices $contactServices, LocationServices $locationServices)
    {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
    }
    public function delete($id)
    {
        try {
            $this->contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function export()
    {

        $dataList = $this->contactServices->Search($this->search, 2, 9999999, $this->locationid);

        return Excel::download(new EmployeeListExport($dataList), 'employee-list.xlsx');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $dataList = $this->contactServices->Search($this->search, 2, $this->perPage, $this->locationid);
        return view('livewire.employees.employee-list', ['dataList' => $dataList]);
    }
}
