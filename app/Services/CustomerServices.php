<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\SalesReceipt;
use Illuminate\Support\Facades\DB;

class CustomerServices
{

    public function GenerateSales(string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, int $PAYMENT_METHOD_ID)
    {
        $result = SalesReceipt::query()
            ->select([
                DB::raw("'SALES RECEIPT' as TYPE"),
                'sales_receipt.ID',
                'sales_receipt.CODE',
                'sales_receipt.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('sales_receipt.PAYMENT_REF_NO as OR_NUMBER'),
                'contact.PRINT_NAME_AS as CONTACT_NAME',
                'sales_receipt.AMOUNT',
                'location.NAME as LOCATION_NAME'
            ])
            ->join('contact', 'contact.ID', '=', 'sales_receipt.CUSTOMER_ID')
            ->join('payment_method', function ($query) use (&$PAYMENT_METHOD_ID) {
                $query->on('payment_method.ID', '=', 'sales_receipt.PAYMENT_METHOD_ID');
                if ($PAYMENT_METHOD_ID > 0) {
                    $query->where('payment_method.ID', '=', $PAYMENT_METHOD_ID);
                }
            })
            ->join('location', function ($query) use (&$LOCATION_ID) {
                $query->on('location.ID', '=', 'sales_receipt.LOCATION_ID');

                if ($LOCATION_ID > 0) {
                    $query->where('location.ID', '=', $LOCATION_ID);
                }
            })
            ->where('sales_receipt.STATUS', '=', '15')
            ->whereBetween('sales_receipt.DATE', [$DATE_FROM, $DATE_TO])
            ->unionAll(
                Payment::query()
                    ->select([
                        DB::raw("'PAYMENT' as TYPE"),
                        'payment.ID',
                        'payment.CODE',
                        'payment.DATE',
                        'payment_method.DESCRIPTION as PAYMENT_METHOD',
                        DB::raw('payment.RECEIPT_REF_NO as OR_NUMBER'),
                        'contact.PRINT_NAME_AS as CONTACT_NAME',
                        'payment.AMOUNT',
                        'location.NAME as LOCATION_NAME'
                    ])
                    ->join('contact', 'contact.ID', '=', 'payment.CUSTOMER_ID')
                    ->join('payment_method', function ($query) use (&$PAYMENT_METHOD_ID) {
                        $query->on('payment_method.ID', '=', 'payment.PAYMENT_METHOD_ID');
                        if ($PAYMENT_METHOD_ID > 0) {
                            $query->where('payment_method.ID', '=', $PAYMENT_METHOD_ID);
                        }
                    })
                    ->join('location', function ($query) use (&$LOCATION_ID) {
                        $query->on('location.ID', '=', 'payment.LOCATION_ID');
                        if ($LOCATION_ID > 0) {
                            $query->where('location.ID', '=', $LOCATION_ID);
                        }
                    })
                    ->where('payment.STATUS', '=', '15')
                    ->whereBetween('payment.DATE', [$DATE_FROM, $DATE_TO])
            )
            ->orderBy('DATE')
            ->get();

        return $result;
    }
}
