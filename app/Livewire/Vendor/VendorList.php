<?php

namespace App\Livewire\Vendor;

use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Vendor List')]
class VendorList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    private $contactServices;
    public function boot(ContactServices $contactServices)
    {
        $this->contactServices = $contactServices;
    }
    public function delete(int $id)
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
        $contacts = $this->contactServices->Search($this->search, 0, 20, 0);
        return view('livewire.vendor.vendor-list', ['dataList' => $contacts]);
    }
}
