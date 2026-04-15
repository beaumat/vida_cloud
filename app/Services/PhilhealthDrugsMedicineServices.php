<?php

namespace App\Services;

use App\Models\PhilhealthDrugsMedicines;

class PhilhealthDrugsMedicineServices
{

    private $object;
    private $dateServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->dateServices = $dateServices;
    }
    public function DrugMedicineStore(int $PHILHEALTH_ID, string $GENERIC_NAME, float $QUANTITY, string $DOSSAGE, string $ROUTE, string $FREQUENCY, float $TOTAL_COST, string $CONT_GENERIC_NAME, float $CONT_QUANTITY, string $CONT_DOSSAGE, string $CONT_ROUTE, string $CONT_FREQUENCY, float $CONT_TOTAL_COST)
    {
        $ID = $this->object->ObjectNextID('PHILHEALTH_DRUGS_MEDICINES');

        PhilhealthDrugsMedicines::create([
            'ID' => $ID,
            'PHILHEALTH_ID' => $PHILHEALTH_ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'GENERIC_NAME' => $GENERIC_NAME,
            'QUANTITY' => $QUANTITY,
            'DOSSAGE' => $DOSSAGE,
            'ROUTE' => $ROUTE,
            'FREQUENCY' => $FREQUENCY,
            'TOTAL_COST' => $TOTAL_COST,
            'CONT_GENERIC_NAME' => $CONT_GENERIC_NAME,
            'CONT_QUANTITY' => $CONT_QUANTITY,
            'CONT_DOSSAGE' => $CONT_DOSSAGE,
            'CONT_ROUTE' => $CONT_ROUTE,
            'CONT_FREQUENCY' => $CONT_FREQUENCY,
            'CONT_TOTAL_COST' => $CONT_TOTAL_COST

        ]);
    }
    public function DrugMedicineUpdate(int $ID, int $PHILHEALTH_ID, string $GENERIC_NAME, float $QUANTITY, string $DOSSAGE, string $ROUTE, string $FREQUENCY, float $TOTAL_COST, string $CONT_GENERIC_NAME, float $CONT_QUANTITY, string $CONT_DOSSAGE, string $CONT_ROUTE, string $CONT_FREQUENCY, float $CONT_TOTAL_COST)
    {
        PhilhealthDrugsMedicines::where('ID', $ID)
            ->update([
                'PHILHEALTH_ID' => $PHILHEALTH_ID,
                'GENERIC_NAME' => $GENERIC_NAME,
                'QUANTITY' => $QUANTITY,
                'DOSSAGE' => $DOSSAGE,
                'ROUTE' => $ROUTE,
                'FREQUENCY' => $FREQUENCY,
                'TOTAL_COST' => $TOTAL_COST,
                'CONT_GENERIC_NAME' => $CONT_GENERIC_NAME,
                'CONT_QUANTITY' => $CONT_QUANTITY,
                'CONT_DOSSAGE' => $CONT_DOSSAGE,
                'CONT_ROUTE' => $CONT_ROUTE,
                'CONT_FREQUENCY' => $CONT_FREQUENCY,
                'CONT_TOTAL_COST' => $CONT_TOTAL_COST

            ]);
    }
    public function DrugMedicineDelete(int $ID)
    {
        PhilhealthDrugsMedicines::where('ID', $ID, )->delete();
    }
    public function DrugMedicineDeleteAll(int $PHILHEALTH_ID)
    {
        PhilhealthDrugsMedicines::where('PHILHEALTH_ID', '=', $PHILHEALTH_ID, )->delete();
    }
    public function drugMedicineAlreadyEntry(int $PHILHEALTH_ID): bool
    {
        return PhilhealthDrugsMedicines::where('PHILHEALTH_ID', '=', $PHILHEALTH_ID)->exists();
    }
    public function DrugMedicineList(int $PHILHEALTH_ID): object
    {
        return PhilhealthDrugsMedicines::query()
            ->select([
                'ID',
                'GENERIC_NAME',
                'QUANTITY',
                'DOSSAGE',
                'ROUTE',
                'FREQUENCY',
                'TOTAL_COST',
                'CONT_GENERIC_NAME',
                'CONT_QUANTITY',
                'CONT_DOSSAGE',
                'CONT_ROUTE',
                'CONT_FREQUENCY',
                'CONT_TOTAL_COST'
            ])
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->orderBy('ID', 'asc')
            ->get();
    }

    public function GetDrugMedicine(int $ID): object
    {
        return PhilhealthDrugsMedicines::where('ID', $ID)->first();
    }
}