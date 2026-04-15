<?php
namespace App\Livewire\Invoice;

use App\Services\LocationServices;
use App\Services\PaymentServices;
use App\Services\PhilHealthServices;
use App\Services\TaxCreditServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('PhilHealth Paid (2026)')]
class QuickPhilhealthPaid extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $locationid;
    public $locationList = [];
    public $search;
    public bool $showPaid  = false;
    public bool $showModal = false;
    private $philHealthServices;
    private $paymentServices;
    private $taxCreditServices;
    private $locationServices;
    private $userServices;
    public function boot(PhilHealthServices $philHealthServices, PaymentServices $paymentServices, TaxCreditServices $taxCreditServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->paymentServices    = $paymentServices;
        $this->taxCreditServices  = $taxCreditServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
    }
    public function mount()
    {

        $this->locationList = $this->locationServices->getList();
        $this->locationid   = $this->userServices->getLocationDefault();

    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openARform(int $PHILHEALTH_ID)
    {
        $this->dispatch('ar-form-show', result: ['PHILHEALTH_ID' => $PHILHEALTH_ID]);
    }
    public function makePaidNew(int $PHIC_ID)
    {
        $data = [
            'PHIC_ID' => $PHIC_ID,
        ];
        $this->dispatch('quick-paid-new', result: $data);
    }
    #[On('quick-paid-reload', 'ar-form-data')]
    public function render()
    {

        $data = $this->philHealthServices->getLHIO_List($this->search, $this->showPaid, $this->locationid);

        return view('livewire.invoice.quick-philhealth-paid', ['dataList' => $data]);

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
