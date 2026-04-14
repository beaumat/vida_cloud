<?php
namespace App\Livewire\Depreciation;

use App\Services\DepreciationServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Depreciation List')]
class DepreciationList extends Component
{

    use WithPagination;
    public int $perPage        = 30;
    protected $paginationTheme = 'bootstrap';
    protected $queryString     = ['search' => ['except' => '']];
    public $search             = '';
    public int $locationid;
    public $locationList = [];
    private $userServices;
    private $depreciationServices;
    private $locationServices;
    public function boot(DepreciationServices $depreciationServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->depreciationServices = $depreciationServices;
        $this->locationServices     = $locationServices;
        $this->userServices         = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();
    }
    public function delete(int $ID)
    {
        DB::beginTransaction();
        try {

            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->depreciationServices->object_type_depreciation, $ID);
            if ($JOURNAL_NO > 0) {
                $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
            }
            $this->depreciationServices->Delete($ID);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function autoDepreciation()
    {
        try {
            $errorMessage = $this->depreciationServices->monthlyExecute();
            if ($errorMessage !== "success") {
                session()->flash('error', $errorMessage);
            } else {
                session()->flash('message', 'Depreciation executed successfully.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
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

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {

        $data = $this->depreciationServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.depreciation.depreciation-list', ['dataList' => $data]);
    }
}
