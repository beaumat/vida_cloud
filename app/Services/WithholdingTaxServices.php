<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\WithholdingTax;
use App\Models\WithholdingTaxBills;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WithholdingTaxServices
{
    public int $object_type_withholding_tax_id      = 67;
    public int $object_type_witholding_tax_bills_id = 68;

    private $object;
    private $systemSettingServices;
    private $dateServices;
    private $billingServices;
    private $accountJournalServices;

    private $usersLogServices;
    public function __construct(
        ObjectServices $objectServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        BillingServices $billingServices,
        AccountJournalServices $accountJournalServices,
        UsersLogServices $usersLogServices
    ) {
        $this->object                 = $objectServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->dateServices           = $dateServices;
        $this->billingServices        = $billingServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->usersLogServices       = $usersLogServices;
    }
    public function Get(int $ID)
    {
        $result = WithholdingTax::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return null;
    }
    public function Store(string $CODE, string $DATE, int $WITHHELD_FROM_ID, float $EWT_RATE, int $EWT_ID, int $EWT_ACCOUNT_ID, int $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID): int
    {

        $ID          = (int) $this->object->ObjectNextID('WITHHOLDING_TAX');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('WITHHOLDING_TAX');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        WithholdingTax::create([
            'ID'                  => $ID,
            'RECORDED_ON'         => $this->dateServices->Now(),
            'CODE'                => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                => $DATE,
            'WITHHELD_FROM_ID'    => $WITHHELD_FROM_ID,
            'EWT_ID'              => $EWT_ID,
            'EWT_RATE'            => $EWT_RATE,
            'EWT_ACCOUNT_ID'      => $EWT_ACCOUNT_ID,
            'LOCATION_ID'         => $LOCATION_ID,
            'AMOUNT'              => 0,
            'NOTES'               => $NOTES,
            'STATUS'              => 0,
            'STATUS_DATE'         => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::WITHHOLDING_TAX, $ID);
        return $ID;
    }

    public function Update(int $ID, $CODE, int $WITHHELD_FROM_ID, float $EWT_RATE, int $EWT_ID, int $EWT_ACCOUNT_ID, int $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID, float $AMOUNT)
    {

        WithholdingTax::where('ID', '=', $ID)
            ->update([
                'CODE'                => $CODE,
                'WITHHELD_FROM_ID'    => $WITHHELD_FROM_ID,
                'EWT_ID'              => $EWT_ID,
                'EWT_RATE'            => $EWT_RATE,
                'EWT_ACCOUNT_ID'      => $EWT_ACCOUNT_ID,
                'LOCATION_ID'         => $LOCATION_ID,
                'NOTES'               => $NOTES,
                'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID,
                'AMOUNT'              => $AMOUNT,
            ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::WITHHOLDING_TAX, $ID);
    }

    public function Delete(int $ID)
    {
        WithholdingTaxBills::where('WITHHOLDING_TAX_ID', '=', $ID)->delete();
        WithholdingTax::where('ID', '=', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::WITHHOLDING_TAX, $ID);
    }

    public function Search($search, int $LOCATION_ID, int $perPage): LengthAwarePaginator
    {

        $result = WithholdingTax::query()
            ->select([
                'withholding_tax.ID',
                'withholding_tax.CODE',
                'withholding_tax.DATE',
                'withholding_tax.AMOUNT',
                'withholding_tax.NOTES',
                'withholding_tax.EWT_RATE',
                'c.PRINT_NAME_AS as NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('contact as c', 'c.ID', '=', 'withholding_tax.WITHHELD_FROM_ID')
            ->join('account as a', 'a.ID', '=', 'withholding_tax.EWT_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'withholding_tax.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'withholding_tax.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('tax as t', 't.ID', '=', 'withholding_tax.EWT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('withholding_tax.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('withholding_tax.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('withholding_tax.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('withholding_tax.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function StatusUpdate(int $ID, int $STATUS): void
    {
        WithholdingTax::where('ID', '=', $ID)
            ->update([
                'STATUS'      => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate(),
            ]);

        $this->usersLogServices->StatusLog($STATUS, LogEntity::WITHHOLDING_TAX, $ID);
    }
    public function getTotal(int $WITHHOLDING_TAX_ID): float
    {
        $TOTAL  = 0;
        $result = WithholdingTaxBills::query()
            ->select([
                'withholding_tax_bills.AMOUNT_WITHHELD',
            ])
            ->where('WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        foreach ($result as $row) {
            $AMOUNT_WITHHELD  = (float) $row->AMOUNT_WITHHELD ?? 0;
            $TOTAL           += $AMOUNT_WITHHELD;
        }

        return $TOTAL;
    }

    public function WtaxJournal(int $WITHHOLDING_TAX_ID)
    {
        $result = WithholdingTax::query()
            ->select([
                'ID',
                'EWT_ACCOUNT_ID as ACCOUNT_ID',
                'WITHHELD_FROM_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->where('ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        return $result;
    }
    public function WtaxRemaining(int $WITHHOLDING_TAX_ID)
    {
        $result = WithholdingTax::query()
            ->select([
                'withholding_tax.ID',
                'withholding_tax.ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'withholding_tax.WITHHELD_FROM_ID as SUBSIDIARY_ID',
                DB::raw("(withholding_tax.AMOUNT - (select IFNULL(sum(withholding_tax_bills.AMOUNT_WITHHELD),0)  from withholding_tax_bills where withholding_tax_bills.WITHHOLDING_TAX_ID = withholding_tax.ID limit 1)) as AMOUNT"),
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->where('withholding_tax.ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        return $result;
    }
    public function WTaxBillJournal(int $WITHHOLDING_TAX_ID)
    {
        $result = WithholdingTaxBills::query()
            ->select([
                'withholding_tax_bills.ID',
                'withholding_tax_bills.ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'withholding_tax_bills.BILL_ID as SUBSIDIARY_ID',
                'withholding_tax_bills.AMOUNT_WITHHELD as AMOUNT',
                DB::raw('0 as ENTRY_TYPE'),
            ])->join('withholding_tax', 'withholding_tax.ID', '=', 'withholding_tax_bills.WITHHOLDING_TAX_ID')
            ->where('withholding_tax_bills.WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        return $result;
    }
    public function UpdateAMOUNT_WITHHELD(int $WITHHOLDING_TAX_ID, float $EWT_RATE): float
    {
        $TOTAL  = 0;
        $result = WithholdingTaxBills::query()
            ->select([
                'withholding_tax_bills.BILL_ID',
                'withholding_tax_bills.ID',
                'i.AMOUNT',
            ])
            ->join('bill as i', 'i.ID', '=', 'withholding_tax_bills.BILL_ID')
            ->where('WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        foreach ($result as $row) {
            $BILL_AMOUNT   = (float) $row->AMOUNT ?? 0;
            $AMT_WITHHELD  = (float) $BILL_AMOUNT * ($EWT_RATE / 100);
            $TOTAL        += $AMT_WITHHELD;
            $this->UpdateBill($row->ID, $WITHHOLDING_TAX_ID, $row->BILL_ID, $AMT_WITHHELD);
            $this->billingServices->UpdateBalance($row->BILL_ID);
        }

        return $TOTAL;
    }

    public function StoreBill(int $WITHHOLDING_TAX_ID, int $BILL_ID, float $AMOUNT_WITHHELD, int $ACCOUNTS_PAYABLE_ID): int
    {

        $ID = (int) $this->object->ObjectNextID('WITHHOLDING_TAX_BILLS');

        WithholdingTaxBills::create(
            [
                'ID'                  => $ID,
                'WITHHOLDING_TAX_ID'  => $WITHHOLDING_TAX_ID,
                'BILL_ID'             => $BILL_ID,
                'AMOUNT_WITHHELD'     => $AMOUNT_WITHHELD,
                'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
            ]
        );
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::WITHHOLDING_TAX_BILLS, $WITHHOLDING_TAX_ID);

        return $ID;
    }
    public function UpdateBill(int $ID, int $WITHHOLDING_TAX_ID, int $BILL_ID, float $AMOUNT_WITHHELD)
    {

        WithholdingTaxBills::where('ID', '=', $ID)
            ->where('WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->update(
                [
                    'AMOUNT_WITHHELD' => $AMOUNT_WITHHELD,
                ]
            );

                $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::WITHHOLDING_TAX_BILLS, $WITHHOLDING_TAX_ID);
    }
    public function WTaxBillExists(int $WITHHOLDING_TAX_ID, int $BILL_ID): bool
    {
        return WithholdingTaxBills::where('WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->exists();
    }
    public function BillExists(int $BILL_ID): bool
    {
        return WithholdingTaxBills::where('BILL_ID', '=', $BILL_ID)->exists();
    }

    public function GetID(int $BILL_ID): int
    {

        $result = WithholdingTaxBills::query()->select(['WITHHOLDING_TAX_ID'])->where('BILL_ID', '=', $BILL_ID)->first();
        if ($result) {
            return $result->WITHHOLDING_TAX_ID ?? 0;
        }

        return 0;
    }
    public function GetWTaxBillExists(int $ID, int $WITHHOLDING_TAX_ID, int $BILL_ID)
    {
        $result = WithholdingTaxBills::where('ID', '=', $ID)
            ->where('WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->first();

        if ($result) {
            return $result;
        }

        return [];
    }
    public function DeleteBill(int $ID, int $WITHHOLDING_TAX_ID)
    {
        WithholdingTaxBills::where('ID', '=', $ID)->delete();
            $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::WITHHOLDING_TAX_BILLS, $WITHHOLDING_TAX_ID);
    }
    public function GetWTaxBill(int $ID)
    {

        $result = WithholdingTaxBills::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return null;
    }
    public function GetBillList(int $WITHHOLDING_TAX_ID)
    {
        $result = WithholdingTaxBills::query()
            ->select([
                'withholding_tax_bills.ID',
                'withholding_tax_bills.BILL_ID',
                'withholding_tax_bills.AMOUNT_WITHHELD',
                'withholding_tax_bills.ACCOUNTS_PAYABLE_ID',
                'i.CODE',
                'i.DATE',
                'i.AMOUNT as ORG_AMOUNT',
                'i.INPUT_TAX_AMOUNT',
                'i.BALANCE_DUE',

            ])
            ->join('bill as i', 'i.ID', '=', 'withholding_tax_bills.BILL_ID')
            ->where('withholding_tax_bills.WITHHOLDING_TAX_ID', '=', $WITHHOLDING_TAX_ID)
            ->get();

        return $result;
    }

    public function setTotal(int $WITHHOLDING_TAX_ID, float $AMOUNT)
    {
        WithholdingTax::where('ID', '=', $WITHHOLDING_TAX_ID)
            ->update(
                [
                    'AMOUNT' => $AMOUNT,
                ]
            );
    }
    public function getPosted(int $WTAX_ID, string $DATE, int $LOCATION_ID): bool
    {
        try {
            $JOURNAL_NO = (int) $this->accountJournalServices->getRecord($this->object_type_withholding_tax_id, $WTAX_ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($this->object_type_withholding_tax_id, $WTAX_ID) + 1;
            }

            $paymentData = $this->WtaxJournal($WTAX_ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentData,
                $LOCATION_ID,
                $this->object_type_withholding_tax_id,
                $DATE,
                "TAX"
            );

            $paymentDataR = $this->WtaxRemaining($WTAX_ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $paymentDataR,
                $LOCATION_ID,
                $this->object_type_withholding_tax_id,
                $DATE,
                "A/P"
            );

            $taxBillData = $this->WTaxBillJournal($WTAX_ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $taxBillData,
                $LOCATION_ID,
                $this->object_type_witholding_tax_bills_id,
                $DATE,
                "A/P"
            );

            $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum  = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {

                $this->StatusUpdate($WTAX_ID, 15);
                DB::commit();
                $data = $this->get($WTAX_ID);
                if ($data) {
                    return true;
                }
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function getAmountBetweenBillPayment(int $CHECK_ID): float
    {
        $result = DB::table('withholding_tax_bills')
            ->select([
                DB::raw('sum(withholding_tax_bills.AMOUNT_WITHHELD) as TOTAL_TAX'),
            ])->whereExists(function ($query) use ($CHECK_ID) {
            $query->select(DB::raw(1))
                ->from('check_bills as bpay')
                ->whereRaw('bpay.BILL_ID = withholding_tax_bills.BILL_ID')
                ->where('bpay.CHECK_ID', '=', $CHECK_ID);
        })->first();

        return $result->TOTAL_TAX ?? 0;
    }
    public function listViaContact(int $CONTACT_ID)
    {
        $result = WithholdingTax::query()
            ->select([
                'withholding_tax.ID',
                'withholding_tax.CODE',
                'withholding_tax.DATE',
                'withholding_tax.AMOUNT',
                'withholding_tax.NOTES',
                'withholding_tax.EWT_RATE',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',

            ])
            ->join('location as l', 'l.ID', '=', 'withholding_tax.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'withholding_tax.STATUS')
            ->where('withholding_tax.WITHHELD_FROM_ID', '=', $CONTACT_ID)
            ->orderBy('withholding_tax.DATE', 'desc')
            ->get();

        return $result;
    }
}
