<?php
namespace App\Livewire\PhilHealth;

use App\Services\HemoServices;
use App\Services\ItemSoaItemizedServices;
use App\Services\ItemSoaServices;
use App\Services\LocationServices;
use App\Services\PhilhealthDrugsMedicineServices;
use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DrugMedicines extends Component
{

    public $ID = null;
    #[Reactive]
    public int $PHILHEALTH_ID;
    public int $LOCATION_ID;
    public string $DATE_ADMITTED;
    public string $DATE_DISCHARGED;
    public int $CONTACT_ID;
    public $dataList = [];
    public string $GENERIC_NAME;
    public float $QUANTITY;
    public string $DOSSAGE;
    public string $ROUTE;
    public string $FREQUENCY;
    public float $TOTAL_COST;
    public string $CONT_GENERIC_NAME;
    public float $CONT_QUANTITY;
    public string $CONT_DOSSAGE;
    public string $CONT_ROUTE;
    public string $CONT_FREQUENCY;
    public float $CONT_TOTAL_COST;
    public bool $exists = false;
    public bool $isItemized = false;

    private $philhealthDrugsMedicineServices;
    private $locationServices;
    private $philHealthServices;
    private $itemSoaItemizedServices;
    private $itemSoaServices;
    private $hemoServices;
    public function boot(
        PhilhealthDrugsMedicineServices $philhealthDrugsMedicineServices,
        LocationServices $locationServices,
        PhilHealthServices $philHealthServices,
        ItemSoaServices $itemSoaServices,
        ItemSoaItemizedServices $itemSoaItemizedServices,
        HemoServices $hemoServices
    ) {
        $this->philhealthDrugsMedicineServices = $philhealthDrugsMedicineServices;
        $this->locationServices = $locationServices;
        $this->philHealthServices = $philHealthServices;
        $this->itemSoaServices = $itemSoaServices;
        $this->itemSoaItemizedServices = $itemSoaItemizedServices;
        $this->hemoServices = $hemoServices;
    }
    public function mount()
    {

        $this->clearField();
        $this->canceled();

    }
    private function GetAutoAllowed()
    {
        $this->exists = $this->philhealthDrugsMedicineServices->drugMedicineAlreadyEntry($this->PHILHEALTH_ID);
        $data = $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->DATE_ADMITTED = $data->DATE_ADMITTED;
            $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
            $this->CONTACT_ID = $data->CONTACT_ID;
            $this->isItemized = $this->locationServices->isITEMIZED($this->LOCATION_ID);

        }
    }
    private function clearField()
    {
        $this->GENERIC_NAME = '';
        $this->QUANTITY = 0;
        $this->DOSSAGE = '';
        $this->ROUTE = '';
        $this->FREQUENCY = '';
        $this->TOTAL_COST = 0;
        $this->CONT_GENERIC_NAME = '';
        $this->CONT_QUANTITY = 0;
        $this->CONT_DOSSAGE = '';
        $this->CONT_ROUTE = '';
        $this->CONT_FREQUENCY = '';
        $this->CONT_TOTAL_COST = 0;
        $this->GetAutoAllowed();
    }
    public function save()
    {


        $this->validate([
            'PHILHEALTH_ID' => 'required|integer',
            'GENERIC_NAME' => 'required|string|max:255',
            'QUANTITY' => 'required|numeric|min:0',
            'DOSSAGE' => 'nullable|string|max:255',
            'ROUTE' => 'nullable|string|max:255',
            'FREQUENCY' => 'nullable|string|max:255',
            'TOTAL_COST' => 'required|numeric|min:0',

        ], [], [
            'PHILHEALTH_ID' => 'PhilHealth ID',
            'GENERIC_NAME' => 'Generic Name',
            'QUANTITY' => 'Quantity',
            'DOSSAGE' => 'Dosage',
            'ROUTE' => 'Route',
            'FREQUENCY' => 'Frequency',
            'TOTAL_COST' => 'Total Cost',
        ]);


        $this->philhealthDrugsMedicineServices->DrugMedicineStore(
            $this->PHILHEALTH_ID,
            $this->GENERIC_NAME,
            $this->QUANTITY,
            $this->DOSSAGE,
            $this->ROUTE,
            $this->FREQUENCY,
            $this->TOTAL_COST,
            $this->CONT_GENERIC_NAME,
            $this->CONT_QUANTITY,
            $this->CONT_DOSSAGE,
            $this->CONT_ROUTE,
            $this->CONT_FREQUENCY,
            $this->CONT_TOTAL_COST
        );
        $this->clearField();
    }

    public string $E_GENERIC_NAME;
    public float $E_QUANTITY;
    public string $E_DOSSAGE;
    public string $E_ROUTE;
    public string $E_FREQUENCY;
    public float $E_TOTAL_COST;
    public string $E_CONT_GENERIC_NAME;
    public float $E_CONT_QUANTITY;
    public string $E_CONT_DOSSAGE;
    public string $E_CONT_ROUTE;
    public string $E_CONT_FREQUENCY;
    public float $E_CONT_TOTAL_COST;

    public function edit(int $ID)
    {
        $data = $this->philhealthDrugsMedicineServices->GetDrugMedicine($ID);
        if ($data) {
            $this->ID = $data->ID;
            $this->E_GENERIC_NAME = $data->GENERIC_NAME;
            $this->E_QUANTITY = $data->QUANTITY;
            $this->E_DOSSAGE = $data->DOSSAGE;
            $this->E_ROUTE = $data->ROUTE;
            $this->E_FREQUENCY = $data->FREQUENCY;
            $this->E_TOTAL_COST = $data->TOTAL_COST;
            $this->E_CONT_GENERIC_NAME = $data->CONT_GENERIC_NAME;
            $this->E_CONT_QUANTITY = $data->CONT_QUANTITY;
            $this->E_CONT_DOSSAGE = $data->CONT_DOSSAGE;
            $this->E_CONT_ROUTE = $data->CONT_ROUTE;
            $this->E_CONT_FREQUENCY = $data->CONT_FREQUENCY;
            $this->E_CONT_TOTAL_COST = $data->CONT_TOTAL_COST;
        }
    }
    public function canceled()
    {
        $this->ID = null;
        $this->E_GENERIC_NAME = '';
        $this->E_QUANTITY = 0;
        $this->E_DOSSAGE = '';
        $this->E_ROUTE = '';
        $this->E_FREQUENCY = '';
        $this->E_TOTAL_COST = 0;
        $this->E_CONT_GENERIC_NAME = '';
        $this->E_CONT_QUANTITY = 0;
        $this->E_CONT_DOSSAGE = '';
        $this->E_CONT_ROUTE = '';
        $this->E_CONT_FREQUENCY = '';
        $this->E_CONT_TOTAL_COST = 0;
    }
    public function update(
    ) {

        $this->philhealthDrugsMedicineServices->DrugMedicineUpdate(
            $this->ID,
            $this->PHILHEALTH_ID,
            $this->E_GENERIC_NAME,
            $this->E_QUANTITY,
            $this->E_DOSSAGE,
            $this->E_ROUTE,
            $this->E_FREQUENCY,
            $this->E_TOTAL_COST,
            $this->E_CONT_GENERIC_NAME,
            $this->E_CONT_QUANTITY,
            $this->E_CONT_DOSSAGE,
            $this->E_CONT_ROUTE,
            $this->E_CONT_FREQUENCY,
            $this->E_CONT_TOTAL_COST
        );
        $this->canceled();
    }

    public function delete(int $ID)
    {
        $this->philhealthDrugsMedicineServices->DrugMedicineDelete($ID);
        $this->clearField();
    }
    public function DeleteAll()
    {
        $this->philhealthDrugsMedicineServices->DrugMedicineDeleteAll($this->PHILHEALTH_ID);
        $this->clearField();
    }
    public function AutoFillUp()
    {
        $exists = $this->philhealthDrugsMedicineServices->drugMedicineAlreadyEntry($this->PHILHEALTH_ID);
        if ($exists) {
            session()->flash('error', 'data already entry.');
            return;
        }

        $IS_ONE_QTY = false;

        $dateList = [];
        $qty = 0;
        $dataList = $this->hemoServices->GetSummary($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED ?? '', $this->DATE_DISCHARGED ?? '');
        foreach ($dataList as $list) {
            $dateList[] = $list->DATE;
            $qty++;
        }

        $isSC_BASE = $this->itemSoaServices->HaveServiceChargeBase($this->LOCATION_ID);
        // Check if the location has a service charge base
        if ($isSC_BASE) {
            // If it has a service charge base, get the medicine list based on the service charge base
            $AS_ONE_PER = (bool) $this->locationServices->AllowedFixLocation($this->LOCATION_ID, [32, 33]);
            $itemList = $this->itemSoaServices->GetMedicineListBySCBase($this->LOCATION_ID);
            foreach ($itemList as $list) {
                $defult_Qty = (int) $this->itemSoaItemizedServices->getQuantityActual($dateList, $this->LOCATION_ID, $this->CONTACT_ID, $list->ID, );

                if ($AS_ONE_PER) {
                    // If it has a service charge base and is set to one per day, loop through the quantity
                    for ($i = 0; $i < $defult_Qty; $i++) {
                        $AMOUNT = 1 * $list->RATE ?? 0;
                        if ($AMOUNT > 0) {
                            $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                            $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                                $this->PHILHEALTH_ID,
                                $list->GENERIC_NAME . $GEN_NAME,
                                1,
                                $list->DOSAGE ?? '',
                                $list->ROUTE ?? '',
                                $list->FREQUENCY ?? '',
                                $AMOUNT,
                                "",
                                0,
                                "",
                                "",
                                "",
                                0,
                            );
                        }
                    }
                } else {
                    // If it has a service charge base and is not set to one per day, use the default quantity
                    $AMOUNT = $defult_Qty * $list->RATE ?? 0;
                    if ($AMOUNT > 0) {
                        $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                        $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                            $this->PHILHEALTH_ID,
                            $list->GENERIC_NAME . $GEN_NAME,
                            $defult_Qty,
                            $list->DOSAGE ?? '',
                            $list->ROUTE ?? '',
                            $list->FREQUENCY ?? '',
                            $AMOUNT,
                            "",
                            0,
                            "",
                            "",
                            "",
                            0,
                        );
                    }

                }

            }

        }

        $IS_SOA_BASE = $this->itemSoaServices->HaveSOABase($this->LOCATION_ID);
        // Check if the location has a SOA base
        if ($IS_SOA_BASE) {
            // If it has a SOA base, get the medicine list based on the SOA base
            $dataPhic = $this->philHealthServices->get($this->PHILHEALTH_ID);
            $defult_Qty = $this->philHealthServices->getNumberOfTreatment($dataPhic->CONTACT_ID, $dataPhic->LOCATION_ID, $dataPhic->DATE_ADMITTED, $dataPhic->DATE_DISCHARGED);

            $itemList = $this->itemSoaServices->GetMedicineListBySOA_Base($this->LOCATION_ID);
            $AS_ONE_PER = (bool) $this->locationServices->AllowedFixLocation($this->LOCATION_ID, [32, 33]);
            foreach ($itemList as $list) {
                if ($AS_ONE_PER) {
                    // If it has a service charge base and is set to one per day, loop through the quantity
                    for ($i = 0; $i < $defult_Qty; $i++) {
                        $AMOUNT = 1 * $list->RATE ?? 0;
                        if ($AMOUNT > 0) {
                            $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                            $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                                $this->PHILHEALTH_ID,
                                $list->GENERIC_NAME . $GEN_NAME,
                                1,
                                $list->DOSAGE ?? '',
                                $list->ROUTE ?? '',
                                $list->FREQUENCY ?? '',
                                $AMOUNT,
                                "",
                                0,
                                "",
                                "",
                                "",
                                0,
                            );
                        }
                    }
                } else {
                    $AMOUNT = $defult_Qty * $list->RATE ?? 0;
                    if ($AMOUNT > 0) {
                        $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                        $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                            $this->PHILHEALTH_ID,
                            $list->ITEM_NAME . $GEN_NAME,
                            $defult_Qty,
                            $list->DOSAGE ?? '',
                            $list->ROUTE ?? '',
                            $list->FREQUENCY ?? '',
                            $AMOUNT,
                            "",
                            0,
                            "",
                            "",
                            "",
                            0,
                        );
                    }
                }

            }

            return;
        }

        if (!$isSC_BASE) {

            if (!$IS_ONE_QTY) {

                // If it does not have a service charge base, get the medicine list based on the actual base
                $itemList = $this->itemSoaServices->GetMedicineList($this->LOCATION_ID);
                foreach ($itemList as $list) {
                    if ($list->ACTUAL_BASE) {
                        $defult_Qty = $this->itemSoaItemizedServices->getQuantityActual($dateList, $this->LOCATION_ID, $this->CONTACT_ID, $list->ID, );
                        $AMOUNT = $defult_Qty * $list->RATE ?? 0;
                    } else {
                        $defult_Qty = $qty;
                        $AMOUNT = $qty * $list->RATE ?? 0;
                    }

                    if ($AMOUNT > 0) {
                        $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                        $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                            $this->PHILHEALTH_ID,
                            $list->ITEM_NAME . $GEN_NAME,
                            $defult_Qty,
                            $list->DOSAGE ?? '',
                            $list->ROUTE ?? '',
                            $list->FREQUENCY ?? '',
                            $AMOUNT,
                            "",
                            0,
                            "",
                            "",
                            "",
                            0,
                        );
                    }
                }
            } else {
                // If it has a service charge base, loop through the quantity
                for ($i = 0; $i < $qty; $i++) { // Loop through the quantity
                    $F_QTY = 1;                     // Default quantity is 1
                    // If it has a service charge base, get the medicine list based on the service charge
                    $itemList = $this->itemSoaServices->GetMedicineList($this->LOCATION_ID);
                    foreach ($itemList as $list) {
                        $AMOUNT = $F_QTY * $list->RATE ?? 0;
                        if ($AMOUNT > 0) {
                            $GEN_NAME = $list->BRAND ? ' (' . $list->BRAND . ')' : '';
                            $this->philhealthDrugsMedicineServices->DrugMedicineStore(
                                $this->PHILHEALTH_ID,
                                $list->ITEM_NAME . $GEN_NAME,
                                $F_QTY,
                                $list->DOSAGE ?? '',
                                $list->ROUTE ?? '',
                                $list->FREQUENCY ?? '',
                                $AMOUNT,
                                "",
                                0,
                                "",
                                "",
                                "",
                                0,
                            );
                        }

                    }
                }
            }

        }
        $this->clearField();
    }
    public function render()
    {
        $this->dataList = $this->philhealthDrugsMedicineServices->DrugMedicineList($this->PHILHEALTH_ID);
        return view('livewire.phil-health.drug-medicines');
    }
}
