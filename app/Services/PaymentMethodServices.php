<?php

namespace App\Services;

use App\Models\PaymentMethods;

class PaymentMethodServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public array $CASH_N_GL =  [0, 10]; //[1, 92, 93, 94, 96];
    public int $PHIL_HEALTH_ID = 91;
    public function get($id)
    {
        return PaymentMethods::where('ID', $id)->first();
    }
    public function getList(): object
    {
        $result = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])->get();

        return $result;
    }
    public function getPaymentMethodViaPatientPayment(): object
    {
        $result = PaymentMethods::query()
            ->select(['ID', 'DESCRIPTION'])
            ->whereIn('PAYMENT_TYPE', $this->CASH_N_GL)
            ->get();

        return $result;
    }

    public function getPaymentMethodViaPhilHealth(): object
    {
        $result = PaymentMethods::query()
            ->select(['ID', 'DESCRIPTION'])
            ->where('ID', '=', $this->PHIL_HEALTH_ID)
            ->get();

        return $result;
    }
    public function getListNotIncludeOneParam(int $ID): object
    {
        $result = PaymentMethods::query()->select(['ID', 'DESCRIPTION'])
            ->where('ID', '<>', $ID)
            ->get();
        return $result;
    }
    public function getListNonPatient(): object
    {
        $result = PaymentMethods::query()
            ->select(['ID', 'DESCRIPTION'])
            ->where('PAYMENT_TYPE', '<=', '8')
            ->get();

        return $result;
    }
    public function Store(string $CODE, string $DESCRIPTION, int $PAYMENT_TYPE, int $GL_ACCOUNT_ID): int
    {
        $ID = $this->object->ObjectNextID('PAYMENT_METHOD');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PAYMENT_METHOD');
        PaymentMethods::create([
            'ID'            => $ID,
            'CODE'          => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DESCRIPTION'   => $DESCRIPTION,
            'PAYMENT_TYPE'  => $PAYMENT_TYPE,
            'GL_ACCOUNT_ID' => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
        ]);
        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $PAYMENT_TYPE, int $GL_ACCOUNT_ID): void
    {
        PaymentMethods::where('ID', $ID)->update([
            'CODE'          => $CODE,
            'DESCRIPTION'   => $DESCRIPTION,
            'PAYMENT_TYPE'  => $PAYMENT_TYPE,
            'GL_ACCOUNT_ID' => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
        ]);
    }

    public function Delete(int $ID): void
    {
        PaymentMethods::where('ID', $ID)->delete();
    }
    public function Search($search): object
    {
        return PaymentMethods::query()
            ->select([
                'payment_method.ID',
                'payment_method.CODE',
                'payment_method.DESCRIPTION',
                'payment_method.PAYMENT_TYPE',
                'payment_method.GL_ACCOUNT_ID',
                't.DESCRIPTION as TYPE',
                'a.NAME as ACCOUNT'
            ])
            ->join('payment_type_map as t', 't.ID', '=', 'payment_method.PAYMENT_TYPE')
            ->leftJoin('account as a', 'a.ID', '=', 'payment_method.GL_ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment_method.CODE', 'like', '%' . $search . '%')
                    ->orWhere('payment_method.DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->orderBy('payment_method.ID', 'desc')
            ->get();
    }
    public function PaymentMethodSwitch(int $PAYMENT_TYPE): array
    {

        switch ($PAYMENT_TYPE) {
            case 0:
                $data = [
                    'showCardNo'            => false,
                    'showCardDateExpire'    => false,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => false,
                    'showFileName'          => false,
                    'titleRef'              => "SL/OR No.",
                    'titleDate'             => '',
                    'showTax'               => false
                ];
                return $data;

            case 1:

                $data = [
                    'showCardNo'            => false,
                    'showCardDateExpire'    => false,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => true,
                    'showFileName'          => false,
                    'titleRef'              => "Check No.",
                    'titleDate'             => 'Ref Date',
                    'showTax'               => false
                ];

                return $data;

            case 4:

                $data = [
                    'showCardNo'            => true,
                    'showCardDateExpire'    => true,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => false,
                    'showFileName'          => false,
                    'titleRef'              => 'Approved No.',
                    'titleDate'             => '',
                    'showTax'               => false
                ];

                return $data;
            case 5:

                $data = [
                    'showCardNo'            => true,
                    'showCardDateExpire'    => true,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => false,
                    'showFileName'          => false,
                    'titleRef'              => "Approved No.",
                    'titleDate'             => '',
                    'showTax'               => false
                ];

                return $data;

            case 8:
                $data = [
                    'showCardNo'            => false,
                    'showCardDateExpire'    => false,
                    'showReceiptNo'         => false,
                    'showReceiptDate'       => false,
                    'showFileName'          => false,
                    'titleRef'              => "Ref No.",
                    'titleDate'             => '',
                    'showTax'               => false
                ];

                return $data;


            case 9:

                $data = [
                    'showCardNo'            => false,
                    'showCardDateExpire'    => false,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => true,
                    'showFileName'          => false,
                    'titleRef'              => "OR No.",
                    'titleDate'             => "OR Date",
                    'showTax'               => true
                ];

                return $data;

            default:

                $data = [
                    'showCardNo'            => false,
                    'showCardDateExpire'    => false,
                    'showReceiptNo'         => true,
                    'showReceiptDate'       => true,
                    'showFileName'          => true,
                    'titleRef'              => "GL No.",
                    'titleDate'             => "GL Date",
                    'showTax'               => false
                ];

                return $data;
        }
    }
}
