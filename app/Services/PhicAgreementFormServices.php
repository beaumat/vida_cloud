<?php

namespace App\Services;

use App\Models\PhicAgreementFormDetails;
use App\Models\PhicAgreementFormItems;
use App\Models\PhicAgreementFormTitle;
use Illuminate\Support\Facades\DB;

class PhicAgreementFormServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function getTitleList()
    {
        $result = PhicAgreementFormTitle::query()
            ->select([
                'ID',
                'DESCRIPTION'
            ])->get();

        return $result;
    }
    public function getTitleByType(int $TYPE, int $HEMO_ID)
    {

        $result = DB::table('phic_agreement_form_title as t')
            ->select([
                't.ID',
                't.LINE',
                't.DESCRIPTION',
                DB::raw("IFNULL((select d.IS_CHECK from phic_agreement_form_details as d where d.HEMO_ID = '$HEMO_ID' and d.PHIC_AFT_ID = t.ID limit 1 ),false) as IS_CHECK ")

            ])
            ->where("t.TYPE", '=', $TYPE)
            ->orderBy('t.LINE', 'asc')
            ->orderBy('t.ID', 'asc')
            ->get();

        return $result;
    }
    public function getList($HEMO_ID)
    {

        $result = DB::table('Phic_Agreement_Form_Title as t')
            ->select([
                't.ID',
                't.LINE',
                't.DESCRIPTION',
                DB::raw("IFNULL((select d.IS_CHECK from phic_agreement_form_details as d where d.HEMO_ID = '$HEMO_ID' and d.PHIC_AFT_ID = t.ID limit 1 ),false) as IS_CHECK ")
            ])
            ->orderBy('t.TYPE', 'asc')
            ->orderBy('t.LINE', 'asc')
            ->orderBy('t.ID', 'asc')
            ->get();

        return $result;
    }
    public function getTitleByID(int $ID)
    {

        $result = PhicAgreementFormTitle::query()
            ->select([
                'ID',
                'LINE',
                'DESCRIPTION'
            ])
            ->where("ID", '=', $ID)
            ->first();

        return $result;
    }
    public function storeDetails(int $HEMO_ID, int $PHIC_AFT_ID, bool $IS_CHECK)
    {
        $ID = (int) $this->object->ObjectNextID('PHIC_AGREEMENT_FORM_DETAILS');

        PhicAgreementFormDetails::create([
            'ID' => $ID,
            'HEMO_ID' => $HEMO_ID,
            'PHIC_AFT_ID' => $PHIC_AFT_ID,
            'IS_CHECK' => $IS_CHECK

        ]);
    }
    public function updateDetails(int $HEMO_ID, int $PHIC_AFT_ID, bool $IS_CHECK)
    {
        PhicAgreementFormDetails::where('HEMO_ID', '=', $HEMO_ID)
            ->where('PHIC_AFT_ID', '=', $PHIC_AFT_ID)
            ->update([
                'IS_CHECK' => $IS_CHECK
            ]);
    }

    public function isExist(int $HEMO_ID, int $PHIC_AFT_ID)
    {
        return (bool) PhicAgreementFormDetails::where('HEMO_ID', '=', $HEMO_ID)
            ->where('PHIC_AFT_ID', '=', $PHIC_AFT_ID)
            ->exists();
    }
    public function getItem(int $ID)
    {
        $result = PhicAgreementFormItems::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
    public function storeItem(int $HEMO_ID, string $DESCRIPTION, int $QUANTITY, float $RATE)
    {
        $ID = (int) $this->object->ObjectNextID('PHIC_AGREEMENT_FORM_ITEMS');

        PhicAgreementFormItems::create([
            'ID' => $ID,
            'HEMO_ID' => $HEMO_ID,
            'DESCRIPTION' => $DESCRIPTION,
            'QUANTITY' => $QUANTITY,
            'RATE' => $RATE
        ]);
    }

    public function updateItem(int $ID, int $HEMO_ID, string $DESCRIPTION, int $QUANTITY, float $RATE)
    {
        PhicAgreementFormItems::where('ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->update([
                'DESCRIPTION' => $DESCRIPTION,
                'QUANTITY' => $QUANTITY,
                'RATE' => $RATE
            ]);
    }
    public function deleteItem(int $ID, int $HEMO_ID)
    {
        PhicAgreementFormItems::where('ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->delete();
    }
    public function getItemList(int $HEMO_ID)
    {
        $result = PhicAgreementFormItems::where('HEMO_ID', '=', $HEMO_ID)->get();

        return $result;
    }
}