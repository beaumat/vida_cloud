<?php

namespace App\Services;

use App\Models\XeroData;

class XeroDataServices
{

    public function viewData(int $locationId)
    {


        $result = XeroData::query()
            ->where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->whereNotNull('REFERENCE')
            ->where('OBJECT_ID', '=', 0)
            ->orderBy('DATE')
            ->orderBy('REFERENCE')
            ->orderBy('SOURCE_TYPE')
            ->limit(1000)
            ->get();

        return $result;

    }
    public function viewNoRefrence(int $locationId)
    {
        $result = XeroData::where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->whereNull('REFERENCE')
            ->get();

        return $result;
    }
    public function viewDataPerGroupReference(int $locationId, int $year = 0, int $month = 0)
    {
        $result = XeroData::where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->when($year > 0, function ($query) use (&$year) {
                $query->whereYear('DATE', $year);
            })
            ->when($month > 0, function ($query) use (&$month) {
                $query->whereMonth('DATE', $month);
            })
            ->groupBy(['REFERENCE', 'DATE', 'SOURCE_TYPE'])
            ->get();

        return $result;
    }
    public function callReference(string $REFERENCE, string $date, string $SOURCE_TYPE, int $locationid)
    {

        if ($REFERENCE != "") {
            return XeroData::query()->where('REFERENCE', '=', $REFERENCE)
                ->where('DATE', '=', $date)
                ->where('SOURCE_TYPE', '=', $SOURCE_TYPE)
                ->where('LOCATION_ID', '=', $locationid)
                ->where('POSTED', '=', 0)
                ->get();
        } else {
           return XeroData::where('LOCATION_ID', '=', $locationid)
                ->where('POSTED', '=', 0)
                ->whereNull('REFERENCE')
                ->get();

  
        }
    }
    public function callReferenceFirst(string $REFERENCE, string $date, string $SOURCE_TYPE)
    {
        $result = XeroData::query()
            ->where('REFERENCE', '=', $REFERENCE)
            ->where('DATE', '=', $date)
            ->where('SOURCE_TYPE', '=', $SOURCE_TYPE)
            ->where('POSTED', '=', 0)
            ->first();

        return $result;
    }
    public function DocumentType(string $SOURCE_TYPE): array
    {


        $docType = ['ID' => 0, 'NAME' => ''];
        switch ($SOURCE_TYPE) {
            case 'Payable Invoice':
                $docType = ['ID' => 1, 'NAME' => 'BILL']; //bill
                break;
            case 'Payable Payment':
                $docType = ['ID' => 2, 'NAME' => 'BILL PAYMENT']; // bill payment
                break;
            case 'Receivable Invoice':
                $docType = ['ID' => 10, 'NAME' => 'INVOICE']; // invoice
                break;
            case 'Receivable Payment':
                $docType = ['ID' => 11, 'NAME' => 'PAYMENT']; //payment;
                break;
            case 'Manual Journal':
                $docType = ['ID' => 23, 'NAME' => 'GENERAL JOURNAL']; //General Journal;
                break;
            case 'Bank Transfer':
                $docType = ['ID' => 26, 'NAME' => 'Fund Transfer']; //Fund Transfer;
                break;
            case 'Receive Money':
                $docType = ['ID' => 36, 'NAME' => 'Receive Money']; //Fund Transfer;
                break;
            case 'Spend Money':
                $docType = ['ID' => 35, 'NAME' => 'Spend Money']; //Fund Transfer;
                break;
            default:
                $docType = ['ID' => 23, 'NAME' => 'GENERAL JOURNAL']; //General Journal;
                break;
        }


   
        return $docType;
    }


    public function updatePosted(int $ID, int $OBJECT_ID, int $OBJECT_TYPE)
    {

        $result = XeroData::where('ID', $ID)
            ->update([
                'POSTED' => 1,
                'OBJECT_ID' => $OBJECT_ID,
                'OBJECT_TYPE' => $OBJECT_TYPE
            ]);

        return $result;
    }

}