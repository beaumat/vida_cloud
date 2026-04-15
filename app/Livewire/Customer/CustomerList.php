<?php

namespace App\Livewire\Customer;

use App\Services\ContactServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Customer')]
class CustomerList extends Component
{


    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 30;
    public $locationList = [];
    public int $locationid = 0;

    private $contactServices;
    private $locationServices;
    public function boot( ContactServices $contactServices, LocationServices $locationServices ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = 0;
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

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {

        $dataList = $this->contactServices->Search($this->search, 1, $this->perPage);
        return view('livewire.customer.customer-list', ['dataList' => $dataList]);
    }
}
