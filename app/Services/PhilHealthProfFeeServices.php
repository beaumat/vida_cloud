<?php
namespace App\Services;

use App\Models\PhilHealthProfFee;
use Illuminate\Support\Facades\DB;

class PhilHealthProfFeeServices
{

    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }

    public function getProfFee($ID)
    {
        $result = PhilHealthProfFee::query()
            ->select([
                'philhealth_prof_fee.ID',
                'philhealth_prof_fee.CONTACT_ID',
                'philhealth_prof_fee.AMOUNT',
                'philhealth_prof_fee.DISCOUNT',
                'philhealth_prof_fee.FIRST_CASE',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN as PIN_NUM',

            ])
            ->selectRaw("CONCAT(SUBSTRING(c.PIN, 1, 4), '-', SUBSTRING(c.PIN, 5, 7), '-', SUBSTRING(c.PIN, 12, 1)) as PIN")
            ->join('contact as c', 'c.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('PHIC_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getProfFeeFirst(int $PHIC_ID)
    {
        $result = PhilHealthProfFee::query()
            ->select([
                'philhealth_prof_fee.ID',
                'philhealth_prof_fee.CONTACT_ID',
                'philhealth_prof_fee.AMOUNT',
                'philhealth_prof_fee.DISCOUNT',
                'philhealth_prof_fee.FIRST_CASE',
                'philhealth_prof_fee.BILL_ID',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN as PIN_NUM',
            ])
            ->selectRaw("CONCAT(SUBSTRING(c.PIN, 1, 4), '-', SUBSTRING(c.PIN, 5, 7), '-', SUBSTRING(c.PIN, 12, 1)) as PIN")
            ->join('contact as c', 'c.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('PHIC_ID', '=', $PHIC_ID)
            ->orderBy('LINE_NO', 'asc')
            ->first();

        return $result;
    }
    public function UpdatePFContact(int $PHIC_ID, int $NEW_CONTACT_ID)
    {
        PhilHealthProfFee::where('PHIC_ID', '=', $PHIC_ID)
            ->update([
                'CONTACT_ID' => $NEW_CONTACT_ID,
            ]);
    }
    public function GetBill(int $BILL_ID)
    {
        return PhilHealthProfFee::where('BILL_ID', '=', $BILL_ID)->first();
    }
    private function getLine($Id): int
    {
        return (int) PhilHealthProfFee::where('PHIC_ID', $Id)->max('LINE_NO');
    }
    public function StoreProfFee(int $PHIC_ID, int $CONTACT_ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        $this->CleanProfFee($PHIC_ID); // reset purspose

        $ID      = $this->object->ObjectNextID('PHILHEALTH_PROF_FEE');
        $LINE_NO = $this->getLine($PHIC_ID) + 1;

        PhilHealthProfFee::create([
            'ID'         => $ID,
            'PHIC_ID'    => $PHIC_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'AMOUNT'     => $AMOUNT,
            'LINE_NO'    => $LINE_NO,
            'DISCOUNT'   => $DISCOUNT,
            'FIRST_CASE' => $FIRST_CASE,
        ]);
    }
    public function UpdateProfFee(int $ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        PhilHealthProfFee::where('ID', $ID)
            ->update([
                'AMOUNT'     => $AMOUNT,
                'DISCOUNT'   => $DISCOUNT,
                'FIRST_CASE' => $FIRST_CASE,
            ]);
    }
    public function DeleteProfFee(int $ID)
    {
        PhilHealthProfFee::where('ID', $ID)->delete();
    }
    public function CleanProfFee(int $PHIC_ID)
    {
        PhilHealthProfFee::where('PHIC_ID', $PHIC_ID)->delete();
    }
    public function listPatientsByDoctor(int $DOCTOR_ID)
    {
        $result = DB::table('contact as c')
            ->select([
                'c.ID',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN',
                'c.ACCOUNT_NO',
                'l.NAME as LOCATION_NAME',
                DB::raw("COUNT(p.ID) as COUNT"),
            ])

            ->join('philhealth as ph', 'ph.CONTACT_ID', '=', 'c.ID')
            ->join('philhealth_prof_fee as p', 'p.PHIC_ID', '=', 'ph.ID')
            ->join('location as l', 'l.ID', '=', 'ph.LOCATION_ID')
            ->where('p.CONTACT_ID', $DOCTOR_ID)
            ->groupBy('c.ID', 'c.PRINT_NAME_AS', 'c.PIN', 'c.ACCOUNT_NO', 'l.NAME')
            ->orderBy('c.PRINT_NAME_AS', 'asc')
            ->get();

        return $result;

    }
}
