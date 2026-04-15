<?php

namespace App\Services;

use App\Models\PaymentTerms;
use Carbon\Carbon;

class PaymentTermServices
{
    private $object;
    private $dateServices;
    public function __construct(ObjectServices $objectService, DateServices $dateServices)
    {
        $this->object = $objectService;
        $this->dateServices = $dateServices;
    }
    public function getDueDate(int $ID, $DATE = null): string
    {
        // Set $currentDate as a Carbon object (use Carbon for date manipulation)
        $currentDate = $DATE ? Carbon::parse($DATE . " 00:00:00") : $this->dateServices->Now();
        $NET_DUE = PaymentTerms::where('INACTIVE', '=', false)->where('ID', $ID)->first()->NET_DUE;
        $netDueDate = $currentDate->addDays($NET_DUE);

        return $netDueDate->format('Y-m-d');
    }
    public function get(int $ID)
    {
        return (string) PaymentTerms::where('INACTIVE', '0')->where('ID', $ID)->first()->DESCRIPTION ?? '';
    }
    public function getList()
    {
        $result = PaymentTerms::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->get();
        return  $result;
    }
    public function Store(string $CODE, string $DESCRIPTION, int $TYPE, int $NET_DUE, float $DISCOUNT_PCT, int $DISCOUNT_DUE, int $DATE_MONTH_PARAM, int $DATE_DAY_PARAM, int $DATE_MIN_DAYS, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('PAYMENT_TERMS');

        PaymentTerms::create([
            'ID'                => $ID,
            'CODE'              => $CODE,
            'DESCRIPTION'       => $DESCRIPTION,
            'TYPE'              => $TYPE,
            'NET_DUE'           => $NET_DUE,
            'DISCOUNT_PCT'      => $DISCOUNT_PCT,
            'DISCOUNT_DUE'      => $DISCOUNT_DUE,
            'DATE_MONTH_PARAM'  => $DATE_MONTH_PARAM,
            'DATE_DAY_PARAM'    => $DATE_DAY_PARAM,
            'DATE_MIN_DAYS'     => $DATE_MIN_DAYS,
            'INACTIVE'          => $INACTIVE

        ]);
        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $TYPE, int $NET_DUE, float $DISCOUNT_PCT, int $DISCOUNT_DUE, int $DATE_MONTH_PARAM, int $DATE_DAY_PARAM, int $DATE_MIN_DAYS, bool $INACTIVE): void
    {
        PaymentTerms::where('ID', $ID)
            ->update([
                'CODE'              => $CODE,
                'DESCRIPTION'       => $DESCRIPTION,
                'TYPE'              => $TYPE,
                'NET_DUE'           => $NET_DUE,
                'DISCOUNT_PCT'      => $DISCOUNT_PCT,
                'DISCOUNT_DUE'      => $DISCOUNT_DUE,
                'DATE_MONTH_PARAM'  => $DATE_MONTH_PARAM,
                'DATE_DAY_PARAM'    => $DATE_DAY_PARAM,
                'DATE_MIN_DAYS'     => $DATE_MIN_DAYS,
                'INACTIVE'          => $INACTIVE
            ]);
    }

    public function Delete(int $ID): void
    {
        PaymentTerms::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return PaymentTerms::query()
            ->select([
                'payment_terms.ID',
                'payment_terms.CODE',
                'payment_terms.DESCRIPTION',
                't.DESCRIPTION as TYPE',
                'payment_terms.INACTIVE'

            ])
            ->join('payment_terms_type_map as t', 't.ID', '=', 'payment_terms.TYPE')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment_terms.CODE', 'like', '%' . $search . '%')
                    ->orWhere('payment_terms.DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('payment_terms.ID', 'desc')
            ->get();
    }
}
