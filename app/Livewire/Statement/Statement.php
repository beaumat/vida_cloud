<?php
namespace App\Livewire\Statement;

use App\Services\DateServices;
use App\Services\StatementServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title("Statement of Account")]
class Statement extends Component
{

    use WithPagination;
    public bool $ShowBalanceOnly = false;
    protected $paginationTheme   = 'bootstrap';
    public $AS_OF_DATE;

    public string $search;
    private $statementServices;
    private $dateServices;
    public function boot(StatementServices $statementServices, DateServices $dateServices)
    {
        $this->statementServices = $statementServices;
        $this->dateServices      = $dateServices;
    }
    public function mount()
    {
        $this->AS_OF_DATE = $this->dateServices->NowDate();

        $this->search = "";
    }

    public function render()
    {
        $data = $this->statementServices->CustomerSoaList($this->AS_OF_DATE, $this->search, $this->ShowBalanceOnly);

        return view('livewire.statement.statement', ['dataList' => $data]);
    }
}
