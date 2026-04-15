<?php

namespace App\Livewire\WriteCheck;

use App\Services\LocationServices;
use App\Services\UserServices;
use App\Services\WriteCheckServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Pay by Check')]
class WriteCheckList extends Component
{

    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $writeCheckServices;
    public function boot(
        WriteCheckServices $writeCheckServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->writeCheckServices = $writeCheckServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete(int $id)
    {
        $data  = $this->writeCheckServices->Get($id);

        if ($data) {

            if ($data->STATUS == 0) {
                try {
                    DB::beginTransaction();
                    $this->writeCheckServices->Delete($id);
                    session()->flash('message', 'Successfully deleted.');
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $errorMessage = 'Error occurred: ' . $e->getMessage();
                    session()->flash('error', $errorMessage);
                }

                return;
            }
            session()->flash('error', 'Invalid. this file cannot be deleted.');
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
        $dataList = $this->writeCheckServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.write-check.write-check-list', ['dataList' => $dataList]);
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
