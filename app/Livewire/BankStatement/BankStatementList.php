<?php
namespace App\Livewire\BankStatement;

use App\Services\BankStatementServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Bank Statement")]
class BankStatementList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search             = '';
    public int $perPage        = 30;
    private $bankStatementServices;
    public function boot(BankStatementServices $bankStatementServices)
    {
        $this->bankStatementServices = $bankStatementServices;
    }
    public function mount()
    {

    }
    public function delete(int $id)
    {
        try {
            //code...
            DB::beginTransaction();
            $this->bankStatementServices->delete($id);

            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            session()->flash("error", $th->getMessage());
        }
    }
    public function render()
    {

        $dataList = $this->bankStatementServices->Search($this->search);

        return view('livewire.bank-statement.bank-statement-list', ['dataList' => $dataList]);
    }
}
