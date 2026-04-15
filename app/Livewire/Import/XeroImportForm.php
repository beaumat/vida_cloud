<?php

namespace App\Livewire\Import;

use App\Services\LocationServices;
use App\Services\XeroDataServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;


#[Title('Import Xero - Transaction Account')]
class XeroImportForm extends Component
{

    public $isForwarded = false;
    public $file = null;

    public $dataList = [];
    public $locationList = [];

    public $locationid = 0;
    private $locationServices;
    private $xeroDataServices;

    public function boot(LocationServices $locationServices, XeroDataServices $xeroDataServices)
    {
        $this->locationServices = $locationServices;
        $this->xeroDataServices = $xeroDataServices;


    }
    public function onMake(string $DATE, string $SOURCE_TYPE, string $REFERENCE)
    {

        $dataSend = [
            'DATE' => $DATE,
            'SOURCE_TYPE' => $SOURCE_TYPE,
            'REFERENCE' => $REFERENCE,
            'locationid' => $this->locationid,
            'is_forwarded' => $this->isForwarded,
        ];

    

        $this->dispatch('dataSend', $dataSend);
    }
    public function generate()
    {
        $this->dataList = [];
        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);

        $this->isForwarded = false;
        $this->dataList = $this->xeroDataServices->viewData($this->locationid);
    }
    public function generateNoReference()
    {
        $this->dataList = [];
        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);
        $this->isForwarded = true;
        $this->dataList = $this->xeroDataServices->viewNoRefrence($this->locationid);
    }


    public function mount()
    {
        $this->locationList = $this->locationServices->getList();

    }
    public function render()
    {
        return view('livewire.import.xero-import-form');
    }
}
