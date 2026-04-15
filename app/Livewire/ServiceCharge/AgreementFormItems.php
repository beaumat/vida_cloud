<?php

namespace App\Livewire\ServiceCharge;

use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormItems extends Component
{
    #[Reactive]
    public int $HEMO_ID;



    public string $DESCRIPTION;
    public int $QUANTITY;
    public float $RATE;

    public $E_ID = null;
    public string $E_DESCRIPTION;
    public int $E_QUANTITY;
    public float $E_RATE;

    public $dataList = [];
    private $phicAgreementFormServices;
    public function boot(PhicAgreementFormServices $phicAgreementFormServices)
    {
        $this->phicAgreementFormServices = $phicAgreementFormServices;
    }

    public function store()
    {


        $this->validate(
            [
                'DESCRIPTION' => 'required|string',
                'QUANTITY' => 'required|numeric',
                'RATE' => 'required|numeric'
            ],
            [''],
            [
                'DESCRIPTION' => 'Item description',
                'QUANTITY' => 'Quantity',
                'RATE' => 'Price'
            ]
        );

        try {
            $this->phicAgreementFormServices->storeItem(
                $this->HEMO_ID,
                $this->DESCRIPTION,
                $this->QUANTITY,
                $this->RATE
            );

            $this->DESCRIPTION = "";
            $this->QUANTITY = 0;
            $this->RATE = 0;


            session()->flash('message', 'Successfuly Added');
        } catch (\Throwable $th) {
            session()->flash('error', 'Error: ' . $th->getMessage());
        }

    }
    public function canceled()
    {
        $this->E_ID = null;
    }
    public function update()
    {



        if ($this->E_ID > 0) {
            
            $this->validate(
                [
                    'E_DESCRIPTION' => 'required|string',
                    'E_QUANTITY' => 'required|numeric',
                    'E_RATE' => 'required|numeric'
                ],
                [''],
                [
                    'E_DESCRIPTION' => 'Item description',
                    'E_QUANTITY' => 'Quantity',
                    'E_RATE' => 'Price'
                ]
            );


            try {
                $this->phicAgreementFormServices->updateItem(
                    $this->E_ID,
                    $this->HEMO_ID,
                    $this->E_DESCRIPTION,
                    $this->E_QUANTITY,
                    $this->E_RATE
                );
                $this->canceled();
                session()->flash('message', 'Successfuly updated');
            } catch (\Throwable $th) {
                session()->flash('error', 'Error: ' . $th->getMessage());
            }

        }
    }
    public function delete(int $ID)
    {
        try {

            $this->phicAgreementFormServices->deleteItem($ID, $this->HEMO_ID);
            session()->flash('message', 'Successfuly deleted');
        } catch (\Throwable $th) {
            session()->flash('error', 'Error: ' . $th->getMessage());
        }
    }
    public function edit(int $ID)
    {
        $data = $this->phicAgreementFormServices->getItem($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_DESCRIPTION = $data->DESCRIPTION;
            $this->E_QUANTITY = (int) $data->QUANTITY;
            $this->E_RATE = (float) $data->RATE ?? 0;
        }
    }
    public function render()
    {
        $this->dataList = $this->phicAgreementFormServices->getItemList($this->HEMO_ID);

        return view('livewire.service-charge.agreement-form-items');
    }
}
