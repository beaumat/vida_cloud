<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Livewire\Component;

class PreviousOperation extends Component
{

    public int $NO_OF_POSTED = 0;
    public int $NO_OF_UNPOSTED = 0;
    public int $NO_OF_VOID = 0;
    public int $NO_OF_CHARGE = 0;
    public string $LOCATION_NAME = '';
    public $itemList = [];
    public $DATE;
    public $LOCATION_ID;
    public $locationList = [];
    public bool $isShow = false;
    private $serviceChargeServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $hemoServices;
    public function boot(
        ServiceChargeServices $serviceChargeServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        HemoServices $hemoServices
    ) {

        $this->serviceChargeServices = $serviceChargeServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->hemoServices = $hemoServices;

    }
    public function mount()
    {

        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->DATE = $this->serviceChargeServices->getDatePreviousTransaction($this->dateServices->NowDate(), $this->LOCATION_ID);
        $dataLoc = $this->locationServices->get($this->LOCATION_ID);
        if ($dataLoc) {
            $this->LOCATION_NAME = $dataLoc->NAME;
        }
    }

    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {
        if ($this->isShow) {
            $this->NO_OF_CHARGE = $this->serviceChargeServices->getCountCharges($this->DATE, $this->LOCATION_ID);
            $this->NO_OF_POSTED = $this->hemoServices->getCountPosted($this->DATE, $this->LOCATION_ID);
            $this->NO_OF_UNPOSTED = $this->hemoServices->getCountUnPosted($this->DATE, $this->LOCATION_ID);
            $this->NO_OF_VOID = $this->hemoServices->getCountVoid($this->DATE, $this->LOCATION_ID);
            $this->itemList = $this->hemoServices->getCountItemRelease($this->DATE, $this->LOCATION_ID);
        }


        return view('livewire.dashboard-page.previous-operation');
    }
}
