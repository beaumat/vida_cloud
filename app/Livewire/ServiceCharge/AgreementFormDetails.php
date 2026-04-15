<?php

namespace App\Livewire\ServiceCharge;

use App\Services\HemoServices;
use App\Services\PhicAgreementFormServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormDetails extends Component
{
    #[Reactive]
    public $HEMO_ID;

    public $dataList = [];
    public $titleSelected = [];
    public $checkedItems = [];
    private $phicAgreementFormServices;
    private $serviceChargeServices;
    private $hemoServices;
    public function boot(PhicAgreementFormServices $phicAgreementFormServices, ServiceChargeServices $serviceChargeServices, HemoServices $hemoServices)
    {
        $this->phicAgreementFormServices = $phicAgreementFormServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->hemoServices = $hemoServices;
    }

    private function getList()
    {
        if ($this->HEMO_ID > 0) {
            $data = $this->phicAgreementFormServices->getList($this->HEMO_ID);

            foreach ($data as $list) {
                $this->checkedItems[$list->ID] = (bool) $list->IS_CHECK;
            }
            $this->dataList = $data;
        }

    }
    public function update(int $PHIC_AFT_ID, bool $STATUS)
    {

        if ($this->phicAgreementFormServices->isExist($this->HEMO_ID, $PHIC_AFT_ID)) {
            // Update
            $this->phicAgreementFormServices->updateDetails($this->HEMO_ID, $PHIC_AFT_ID, $STATUS);
        } else {
            
            if ($STATUS) {

                $this->phicAgreementFormServices->storeDetails($this->HEMO_ID, $PHIC_AFT_ID, $STATUS);
            }
            // INSERT

        }
    }
    public function AutoDetect()
    {

        $hemoData = $this->hemoServices->Get($this->HEMO_ID);
        if ($hemoData) {
            $scdata = $this->serviceChargeServices->get2($hemoData->CUSTOMER_ID, $hemoData->LOCATION_ID, $hemoData->DATE);
            if ($scdata) {
                foreach ($this->dataList as $list) {
                    if ($this->serviceChargeServices->checkifSchavePAF($list->ID, $scdata->ID)) {
                        $this->update($list->ID, true);
                    } else {
                        $this->update($list->ID, false);
                    }
                }
            }


            $this->update(26, true);
            $this->update(27, true);
            $this->getList();
        }






    }
    public function render()
    {

        $this->getList();


        return view('livewire.service-charge.agreement-form-details');
    }
}
