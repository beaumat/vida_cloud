<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Models\AccountJournal;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

class AccountJournalServices
{
    public string $CHECK_TYPE = "
        CASE
        WHEN aj.OBJECT_TYPE = 57 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE where check.ID = aj.OBJECT_ID limit 1 )
        WHEN aj.OBJECT_TYPE = 58 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_bills on check_bills.CHECK_ID = check.ID where  check_bills.ID = aj.OBJECT_ID limit 1 )
        WHEN aj.OBJECT_TYPE = 75 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_items on check_items.CHECK_ID = check.ID where  check_items.ID = aj.OBJECT_ID limit 1)
        WHEN aj.OBJECT_TYPE = 79 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_expenses on check_expenses.CHECK_ID = check.ID where  check_expenses.ID = aj.OBJECT_ID limit 1)
        END  ";
    public function GetFullDescription(): string
    {
        return "if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE";
    }
    public string $CHECK_TYPE2 = "
        (CASE
        WHEN aj.OBJECT_TYPE = 57 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE where check.ID = aj.OBJECT_ID limit 1 )
        WHEN aj.OBJECT_TYPE = 58 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_bills on check_bills.CHECK_ID = check.ID where  check_bills.ID = aj.OBJECT_ID limit 1 )
        WHEN aj.OBJECT_TYPE = 75 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_items on check_items.CHECK_ID = check.ID where  check_items.ID = aj.OBJECT_ID limit 1)
        WHEN aj.OBJECT_TYPE = 79 THEN (select `check_type_map`.`NAME` from `check` inner join check_type_map on check_type_map.ID = check.TYPE inner join check_expenses on check_expenses.CHECK_ID = check.ID where  check_expenses.ID = aj.OBJECT_ID limit 1)
        END)  ";
    public string $TX_PO = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`CUSTOM_FIELD1` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select bill.`CUSTOM_FIELD1` from bill_items  join bill on bill.ID = bill_items.BILL_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill.`CUSTOM_FIELD1` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select credit_memo.`CUSTOM_FIELD1` from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`CUSTOM_FIELD1` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment.`CUSTOM_FIELD1` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`PO_NUMBER` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select invoice.`PO_NUMBER` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`CUSTOM_FIELD1` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select stock_transfer.`CUSTOM_FIELD1` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 41    THEN ( select payment.`RECEIPT_REF_NO` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select payment.`RECEIPT_REF_NO` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`PAYMENT_REF_NO` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select `sales_receipt`.`PAYMENT_REF_NO` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`CUSTOM_FIELD1` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`CUSTOM_FIELD1` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select `check`.`CUSTOM_FIELD1` from `check_items` join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select `check`.`CUSTOM_FIELD1` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 59    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit.`CUSTOM_FIELD1` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`CASH_BACK_NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`CASH_BACK_NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal.`NOTES` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`CODE` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`CUSTOM_FIELD1` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select build_assembly.`CUSTOM_FIELD1` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select  0  as `CODE` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select  0  as `CODE` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113    THEN ( select pull_out.`CUSTOM_FIELD1` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114    THEN ( select pull_out.`CUSTOM_FIELD1` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 135    THEN ( select bank_transfer.`CODE` from bank_transfer where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))

        END as TX_PO';

    public string $TX_CODE = '
    CASE
        WHEN o.`ID` = 2     THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill join document_status_map on document_status_map.ID = bill.STATUS  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill_items join bill on bill.ID = bill_items.BILL_ID  join document_status_map on document_status_map.ID = bill.STATUS where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  join document_status_map on document_status_map.ID = bill.STATUS where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 12    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo join document_status_map on document_status_map.ID = credit_memo.STATUS  where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID  join document_status_map on document_status_map.ID = credit_memo.STATUS where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  join document_status_map on document_status_map.ID = credit_memo.STATUS where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 19    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",inventory_adjustment.`CODE`) as `CODE` from inventory_adjustment join document_status_map on document_status_map.ID = inventory_adjustment.STATUS  where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",inventory_adjustment.`CODE`) as `CODE` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID join document_status_map on document_status_map.ID = inventory_adjustment.STATUS  where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 23    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",invoice.`CODE`) as `CODE` from invoice join document_status_map on document_status_map.ID = invoice.STATUS  where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",invoice.`CODE`) as `CODE` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID  join document_status_map on document_status_map.ID = invoice.STATUS where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 38    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",stock_transfer.`CODE`) as `CODE` from stock_transfer join document_status_map on document_status_map.ID = stock_transfer.STATUS  where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",stock_transfer.`CODE`) as `CODE` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID join document_status_map on document_status_map.ID = stock_transfer.STATUS where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )

        WHEN o.`ID` = 41    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",payment.`CODE`) as `CODE` from payment join document_status_map on document_status_map.ID = payment.STATUS  where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",payment.`CODE`) as `CODE` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID join document_status_map on document_status_map.ID = payment.STATUS where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 52    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",sales_receipt.`CODE`) as `CODE` from `sales_receipt` join document_status_map on document_status_map.ID = sales_receipt.STATUS where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",sales_receipt.`CODE`) as `CODE` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  join document_status_map on document_status_map.ID = sales_receipt.STATUS  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 57    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check` join document_status_map on document_status_map.ID = `check`.STATUS   where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID join document_status_map on document_status_map.ID = `check`.STATUS where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_items` join `check` on check.ID = check_items.CHECK_ID join document_status_map on document_status_map.ID = `check`.STATUS  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  join document_status_map on document_status_map.ID = `check`.STATUS where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 59   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit join document_status_map on document_status_map.ID = bill_credit.STATUS   where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID join document_status_map on document_status_map.ID = bill_credit.STATUS where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  join document_status_map on document_status_map.ID = bill_credit.STATUS where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 81   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`deposit`.`CODE`) as `CODE` from deposit join document_status_map on document_status_map.ID = deposit.STATUS where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`deposit`.`CODE`) as `CODE` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID join document_status_map on document_status_map.ID = deposit.STATUS where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 84   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`general_journal`.`CODE`) as `CODE` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID join document_status_map on document_status_map.ID = general_journal.STATUS  where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 93   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`fund_transfer`.`CODE`) as `CODE` from fund_transfer join document_status_map on document_status_map.ID = fund_transfer.STATUS  where  fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))

        WHEN o.`ID` = 70   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`build_assembly`.`CODE`) as `CODE` from build_assembly join document_status_map on document_status_map.ID = build_assembly.STATUS  where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`build_assembly`.`CODE`) as `CODE` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID join document_status_map on document_status_map.ID = build_assembly.STATUS  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 72   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`tax_credit`.`CODE`) as `CODE` from tax_credit join document_status_map on document_status_map.ID = tax_credit.STATUS  where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`tax_credit`.`CODE`) as `CODE` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  join document_status_map on document_status_map.ID = tax_credit.STATUS where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 113  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`pull_out`.`CODE`) as `CODE` from pull_out join document_status_map on document_status_map.ID = pull_out.STATUS  where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`pull_out`.`CODE`) as `CODE` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID join document_status_map on document_status_map.ID = pull_out.STATUS  where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 127  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`depreciation`.`CODE`) as `CODE` from depreciation join document_status_map on document_status_map.ID = depreciation.STATUS  where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`depreciation`.`CODE`) as `CODE` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID join document_status_map on document_status_map.ID = depreciation.STATUS where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 135  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bank_transfer`.`CODE`) as `CODE` from bank_transfer join document_status_map on document_status_map.ID = bank_transfer.STATUS  where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))

        WHEN o.`ID` = 67   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`withholding_tax`.`CODE`) as `CODE` from withholding_tax join document_status_map on document_status_map.ID = withholding_tax.STATUS  where withholding_tax.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE  and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 68   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`withholding_tax`.`CODE`) as `CODE` from withholding_tax_bills join withholding_tax on withholding_tax.ID = withholding_tax_bills.WITHHOLDING_TAX_ID join document_status_map on document_status_map.ID = withholding_tax.STATUS where withholding_tax_bills.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 95   THEN ( select concat(LEFT(hemo_status.DESCRIPTION,1),":",`hemodialysis`.`CODE`) as `CODE` from hemodialysis join hemo_status on hemo_status.ID = hemodialysis.STATUS_ID  where hemodialysis.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 109  THEN ( select concat(LEFT(hemo_status.DESCRIPTION,1),":",`hemodialysis`.`CODE`) as `CODE` from hemodialysis inner join hemodialysis_items on hemodialysis.ID = hemodialysis_items.HEMO_ID join hemo_status on hemo_status.ID = hemodialysis.STATUS_ID where hemodialysis_items.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )


        END as TX_CODE';
    public string $TX_CODE_E = '
    CASE
        WHEN o.`ID` = 2     THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill join document_status_map on document_status_map.ID = bill.STATUS  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill_items join bill on bill.ID = bill_items.BILL_ID  join document_status_map on document_status_map.ID = bill.STATUS where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",bill.`CODE`) as `CODE` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  join document_status_map on document_status_map.ID = bill.STATUS where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 12    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo join document_status_map on document_status_map.ID = credit_memo.STATUS  where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID  join document_status_map on document_status_map.ID = credit_memo.STATUS where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",credit_memo.`CODE`) as `CODE`  from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  join document_status_map on document_status_map.ID = credit_memo.STATUS where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 19    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",inventory_adjustment.`CODE`) as `CODE` from inventory_adjustment join document_status_map on document_status_map.ID = inventory_adjustment.STATUS  where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",inventory_adjustment.`CODE`) as `CODE` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID join document_status_map on document_status_map.ID = inventory_adjustment.STATUS  where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 23    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",invoice.`CODE`) as `CODE` from invoice join document_status_map on document_status_map.ID = invoice.STATUS  where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",invoice.`CODE`) as `CODE` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID  join document_status_map on document_status_map.ID = invoice.STATUS where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 38    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",stock_transfer.`CODE`) as `CODE` from stock_transfer join document_status_map on document_status_map.ID = stock_transfer.STATUS  where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",stock_transfer.`CODE`) as `CODE` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID join document_status_map on document_status_map.ID = stock_transfer.STATUS where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )

        WHEN o.`ID` = 41    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",payment.`CODE`) as `CODE` from payment join document_status_map on document_status_map.ID = payment.STATUS  where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",payment.`CODE`) as `CODE` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID join document_status_map on document_status_map.ID = payment.STATUS where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 52    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",sales_receipt.`CODE`) as `CODE` from `sales_receipt` join document_status_map on document_status_map.ID = sales_receipt.STATUS where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",sales_receipt.`CODE`) as `CODE` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  join document_status_map on document_status_map.ID = sales_receipt.STATUS  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 57    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check` join document_status_map on document_status_map.ID = `check`.STATUS   where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID join document_status_map on document_status_map.ID = `check`.STATUS where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_items` join `check` on check.ID = check_items.CHECK_ID join document_status_map on document_status_map.ID = `check`.STATUS  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`check`.`CODE`) as `CODE` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  join document_status_map on document_status_map.ID = `check`.STATUS where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 59   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit join document_status_map on document_status_map.ID = bill_credit.STATUS   where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID join document_status_map on document_status_map.ID = bill_credit.STATUS where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bill_credit`.`CODE`) as `CODE` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  join document_status_map on document_status_map.ID = bill_credit.STATUS where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)

        WHEN o.`ID` = 81   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`deposit`.`CODE`) as `CODE` from deposit join document_status_map on document_status_map.ID = deposit.STATUS where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`deposit`.`CODE`) as `CODE` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID join document_status_map on document_status_map.ID = deposit.STATUS where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 84   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`general_journal`.`CODE`) as `CODE` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID join document_status_map on document_status_map.ID = general_journal.STATUS  where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 93   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`fund_transfer`.`CODE`) as `CODE` from fund_transfer join document_status_map on document_status_map.ID = fund_transfer.STATUS  where  fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))

        WHEN o.`ID` = 70   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`build_assembly`.`CODE`) as `CODE` from build_assembly join document_status_map on document_status_map.ID = build_assembly.STATUS  where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`build_assembly`.`CODE`) as `CODE` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID join document_status_map on document_status_map.ID = build_assembly.STATUS  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 72   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`tax_credit`.`CODE`) as `CODE` from tax_credit join document_status_map on document_status_map.ID = tax_credit.STATUS  where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`tax_credit`.`CODE`) as `CODE` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  join document_status_map on document_status_map.ID = tax_credit.STATUS where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 113  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`pull_out`.`CODE`) as `CODE` from pull_out join document_status_map on document_status_map.ID = pull_out.STATUS  where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`pull_out`.`CODE`) as `CODE` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID join document_status_map on document_status_map.ID = pull_out.STATUS  where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 127  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`depreciation`.`CODE`) as `CODE` from depreciation join document_status_map on document_status_map.ID = depreciation.STATUS  where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`depreciation`.`CODE`) as `CODE` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID join document_status_map on document_status_map.ID = depreciation.STATUS where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 135  THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`bank_transfer`.`CODE`) as `CODE` from bank_transfer join document_status_map on document_status_map.ID = bank_transfer.STATUS  where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))

        WHEN o.`ID` = 67   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`withholding_tax`.`CODE`) as `CODE` from withholding_tax join document_status_map on document_status_map.ID = withholding_tax.STATUS  where withholding_tax.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE  and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 68   THEN ( select concat(LEFT(document_status_map.DESCRIPTION,1),":",`withholding_tax`.`CODE`) as `CODE` from withholding_tax_bills join withholding_tax on withholding_tax.ID = withholding_tax_bills.WITHHOLDING_TAX_ID join document_status_map on document_status_map.ID = withholding_tax.STATUS where withholding_tax_bills.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )

        WHEN o.`ID` = 95   THEN ( select concat(LEFT(hemo_status.DESCRIPTION,1),":",`hemodialysis`.`CODE`) as `CODE` from hemodialysis join hemo_status on hemo_status.ID = hemodialysis.STATUS_ID  where hemodialysis.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 109  THEN ( select concat(LEFT(hemo_status.DESCRIPTION,1),":",`hemodialysis`.`CODE`) as `CODE` from hemodialysis inner join hemodialysis_items on hemodialysis.ID = hemodialysis_items.HEMO_ID join hemo_status on hemo_status.ID = hemodialysis.STATUS_ID where hemodialysis_items.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )


        END ';
    public string $TX_NOTES = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`NOTES` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select item.`DESCRIPTION` as `NOTES` from bill_items  join bill on bill.ID = bill_items.BILL_ID inner join item on item.ID = bill_items.ITEM_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill_expenses.PARTICULARS as `NOTES` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`NOTES` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`NOTES` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select item.DESCRIPTION as `NOTES` from credit_memo_items join item on item.ID = credit_memo_items.ITEM_ID join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`NOTES` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select item.DESCRIPTION as `NOTES` from inventory_adjustment_items join item on item.ID = inventory_adjustment_items.ITEM_ID join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`NOTES` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select item.DESCRIPTION as `NOTES` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID INNER JOIN item on item.ID = invoice_items.ITEM_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`NOTES` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 39    THEN ( select item.DESCRIPTION as `NOTES` from stock_transfer_items join item on item.ID = stock_transfer_items.ITEM_ID join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE  )
        WHEN o.`ID` = 41    THEN ( select payment.`NOTES` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select concat("Invoice Ref NO. :", invoice.`CODE`) as `NOTES` from payment_invoices inner join invoice on invoice.ID = payment_invoices.INVOICE_ID join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`NOTES` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select item.DESCRIPTION as `NOTES` from `sales_receipt_items` inner join item on item.ID = sales_receipt_items.ITEM_ID join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`NOTES` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`NOTES` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select item.DESCRIPTION as `NOTES` from `check_items` inner join item on item.ID = check_items.ITEM_ID join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select `check_expenses`.`PARTICULARS` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 59    THEN ( select bill_credit.`NOTES` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select item.DESCRIPTION as `NOTES` from bill_credit_items inner join item on item.ID = bill_credit_items.ITEM_ID join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit_expenses.`PARTICULARS` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit_funds.CHECK_NO as `NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal_details.NOTES from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`NOTES` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`NOTES` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select item.DESCRIPTION as `NOTES` from build_assembly_items inner join item on item.ID = build_assembly_items.ITEM_ID  join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select tax_credit.`NOTES` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select concat("Invoice Ref NO. :", invoice.`CODE`) as `NOTES` from tax_credit_invoices join invoice on invoice.ID = tax_credit_invoices.INVOICE_ID join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113   THEN ( select pull_out.`NOTES` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114   THEN ( select item.DESCRIPTION as `NOTES` from pull_out_items inner join item on item.ID = pull_out_items.ITEM_ID join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 127   THEN ( select depreciation.`NOTES` from depreciation where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128   THEN ( select depreciation.`NOTES` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 135   THEN ( select bank_transfer.`NOTES` from bank_transfer where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 67   THEN ( select withholding_tax.`NOTES` from withholding_tax where withholding_tax.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE  and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 68   THEN ( select concat("Billing Ref NO. :", bill.`CODE`) as `NOTES` from withholding_tax_bills inner join bill on bill.ID = withholding_tax_bills.BILL_ID join withholding_tax on withholding_tax.ID = withholding_tax_bills.WITHHOLDING_TAX_ID  where withholding_tax_bills.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 95    THEN ( select "" as NOTES from hemodialysis left outer join contact on contact.ID = hemodialysis.EMPLOYEE_ID where hemodialysis.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 109   THEN ( select item.DESCRIPTION as `NOTES` from hemodialysis_items inner join item on item.ID = hemodialysis_items.ITEM_ID inner join hemodialysis on hemodialysis.ID = hemodialysis_items.HEMO_ID  where hemodialysis_items.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        END as TX_NOTES';

    public string $TX_NAME = '
    CASE
        WHEN o.`ID` = 2     THEN ( select contact.PRINT_NAME_AS from bill join contact on contact.ID = bill.VENDOR_ID where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select contact.PRINT_NAME_AS from bill_items join bill on bill.ID = bill_items.BILL_ID  join contact on contact.ID = bill.VENDOR_ID where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select contact.PRINT_NAME_AS from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID join contact on contact.ID = bill.VENDOR_ID where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select contact.PRINT_NAME_AS from credit_memo join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select contact.PRINT_NAME_AS from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select contact.PRINT_NAME_AS from credit_memo_items join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID join contact on contact.ID = credit_memo.CUSTOMER_ID where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment_type.`DESCRIPTION` from inventory_adjustment join inventory_adjustment_type on inventory_adjustment_type.ID = inventory_adjustment.ADJUSTMENT_TYPE_ID where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment_type.`DESCRIPTION` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID join inventory_adjustment_type on inventory_adjustment_type.ID = inventory_adjustment.ADJUSTMENT_TYPE_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select contact.PRINT_NAME_AS from invoice join contact on contact.ID = invoice.CUSTOMER_ID where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select contact.PRINT_NAME_AS from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID join contact on contact.ID = invoice.CUSTOMER_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( null)
        WHEN o.`ID` = 39    THEN ( null)
        WHEN o.`ID` = 41    THEN ( select contact.PRINT_NAME_AS from payment join contact on contact.ID = payment.CUSTOMER_ID where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select contact.PRINT_NAME_AS from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID join contact on contact.ID = payment.CUSTOMER_ID  where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select contact.PRINT_NAME_AS from `sales_receipt` join contact on contact.ID = sales_receipt.CUSTOMER_ID where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select contact.PRINT_NAME_AS from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID join contact on contact.ID = sales_receipt.CUSTOMER_ID   where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select contact.PRINT_NAME_AS from `check` join contact on contact.ID = check.PAY_TO_ID where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select contact.PRINT_NAME_AS from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select contact.PRINT_NAME_AS from `check_items` join `check` on check.ID = check_items.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select contact.PRINT_NAME_AS from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  join contact on contact.ID = check.PAY_TO_ID where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 59    THEN ( select contact.PRINT_NAME_AS from bill_credit join contact on contact.ID = bill_credit.VENDOR_ID  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select contact.PRINT_NAME_AS from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID join contact on contact.ID = bill_credit.VENDOR_ID   where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select contact.PRINT_NAME_AS from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID join contact on contact.ID = bill_credit.VENDOR_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`NOTES` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`NOTES` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select contact.PRINT_NAME_AS from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID left outer join contact on contact.ID = general_journal.CONTACT_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select if(aj.LOCATION_ID = fund_transfer.FROM_LOCATION_ID , ( select contact.PRINT_NAME_AS from contact where contact.ID = fund_transfer.FROM_NAME_ID   limit 1) , if(aj.LOCATION_ID = fund_transfer.TO_LOCATION_ID , ( select contact.PRINT_NAME_AS from contact where contact.ID = fund_transfer.TO_NAME_ID   limit 1) , null ) )   as PRIN_NAME_AS  from fund_transfer  where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select item.DESCRIPTION from build_assembly join item on item.ID = build_assembly.ASSEMBLY_ITEM_ID  where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select item.DESCRIPTION from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  join  item on item.ID = build_assembly_items.ITEM_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select contact.`PRINT_NAME_AS` from tax_credit left join contact on contact.ID = tax_credit.CUSTOMER_ID where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select contact.`PRINT_NAME_AS` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  left join contact on contact.ID = tax_credit.CUSTOMER_ID where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113   THEN ( select contact.`PRINT_NAME_AS` from pull_out  left join contact on contact.ID = pull_out.PREPARED_BY_ID where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114   THEN ( select contact.`PRINT_NAME_AS` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID left join contact on contact.ID = pull_out.PREPARED_BY_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 135   THEN ( select if(aj.LOCATION_ID = bank_transfer.FROM_LOCATION_ID , ( select contact.PRINT_NAME_AS from contact where contact.ID = bank_transfer.FROM_NAME_ID   limit 1) , if(aj.LOCATION_ID = bank_transfer.TO_LOCATION_ID , ( select contact.PRINT_NAME_AS from contact where contact.ID = bank_transfer.TO_NAME_ID   limit 1) , null ) )   as PRIN_NAME_AS  from bank_transfer  where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 95    THEN ( select contact.`PRINT_NAME_AS` from hemodialysis left outer join contact on contact.ID = hemodialysis.CUSTOMER_ID where hemodialysis.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 109   THEN ( select contact.`PRINT_NAME_AS` from hemodialysis left outer join contact on contact.ID = hemodialysis.CUSTOMER_ID inner join hemodialysis_items on hemodialysis.ID = hemodialysis_items.HEMO_ID where hemodialysis_items.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 67   THEN ( select contact.`PRINT_NAME_AS` from withholding_tax left join contact on contact.ID = withholding_tax.WITHHELD_FROM_ID where withholding_tax.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE  and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 68   THEN ( select contact.`PRINT_NAME_AS` from withholding_tax_bills join withholding_tax on withholding_tax.ID = withholding_tax_bills.WITHHOLDING_TAX_ID left join contact on contact.ID = withholding_tax.WITHHELD_FROM_ID  where withholding_tax_bills.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )


    END as TX_NAME';

    public string $TX_ROUTE_ID = '
    CASE
        WHEN o.`ID` = 2     THEN ( select bill.`ID` from bill  where bill.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 3     THEN ( select bill.`ID` from bill_items  join bill on bill.ID = bill_items.BILL_ID  where bill_items.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 78    THEN ( select bill.`ID` from bill_expenses  join bill on bill.ID = bill_expenses.BILL_ID  where bill_expenses.ID = aj.OBJECT_ID and bill.DATE = aj.OBJECT_DATE  and bill.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 12    THEN ( select credit_memo.`ID` from credit_memo where credit_memo.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 13    THEN ( select credit_memo.`ID` from credit_memo_invoices join credit_memo on credit_memo.ID = credit_memo_invoices.CREDIT_MEMO_ID where credit_memo_invoices.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 14    THEN ( select credit_memo.`ID` from credit_memo_items  join credit_memo on credit_memo.ID = credit_memo_items.CREDIT_MEMO_ID  where credit_memo_items.ID = aj.OBJECT_ID and credit_memo.DATE = aj.OBJECT_DATE  and credit_memo.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 19    THEN ( select inventory_adjustment.`ID` from inventory_adjustment where inventory_adjustment.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 20    THEN ( select inventory_adjustment.`ID` from inventory_adjustment_items join inventory_adjustment on inventory_adjustment.ID = inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID where inventory_adjustment_items.ID = aj.OBJECT_ID and inventory_adjustment.DATE = aj.OBJECT_DATE  and inventory_adjustment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 23    THEN ( select invoice.`ID` from invoice where invoice.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 24    THEN ( select invoice.`ID` from invoice_items join invoice on invoice.ID = invoice_items.INVOICE_ID where invoice_items.ID = aj.OBJECT_ID and invoice.DATE = aj.OBJECT_DATE  and invoice.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 38    THEN ( select stock_transfer.`ID` from stock_transfer where stock_transfer.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE   )
        WHEN o.`ID` = 39    THEN ( select stock_transfer.`ID` from stock_transfer_items join stock_transfer on stock_transfer.ID = stock_transfer_items.STOCK_TRANSFER_ID where stock_transfer_items.ID = aj.OBJECT_ID and stock_transfer.DATE = aj.OBJECT_DATE )
        WHEN o.`ID` = 41    THEN ( select payment.`ID` from payment where payment.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 42    THEN ( select payment.`ID` from payment_invoices join payment on payment.ID = payment_invoices.PAYMENT_ID where payment_invoices.ID = aj.OBJECT_ID and payment.DATE = aj.OBJECT_DATE  and payment.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 52    THEN ( select sales_receipt.`ID` from `sales_receipt`  where `sales_receipt`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 53    THEN ( select `sales_receipt`.`ID` from `sales_receipt_items` join sales_receipt on sales_receipt.ID = sales_receipt_items.SALES_RECEIPT_ID  where `sales_receipt_items`.ID = aj.OBJECT_ID and `sales_receipt`.DATE = aj.OBJECT_DATE  and `sales_receipt`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 57    THEN ( select `check`.`ID` from `check`  where `check`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 58    THEN ( select `check`.`ID` from `check_bills` join `check` on check.ID = check_bills.CHECK_ID  where `check_bills`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 75    THEN ( select `check`.`ID` from `check_items` join `check` on check.ID = check_items.CHECK_ID  where `check_items`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 79    THEN ( select `check`.`ID` from `check_expenses` join `check` on check.ID = check_expenses.CHECK_ID  where `check_expenses`.ID = aj.OBJECT_ID and `check`.DATE = aj.OBJECT_DATE  and `check`.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 59    THEN ( select bill_credit.`ID` from bill_credit  where bill_credit.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 60    THEN ( select bill_credit.`ID` from bill_credit_items join bill_credit on bill_credit.ID = bill_credit_items.BILL_CREDIT_ID  where bill_credit_items.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 80    THEN ( select bill_credit.`ID` from bill_credit_expenses join bill_credit on bill_credit.ID = bill_credit_expenses.BILL_CREDIT_ID  where bill_credit_expenses.ID = aj.OBJECT_ID and bill_credit.DATE = aj.OBJECT_DATE  and bill_credit.LOCATION_ID = aj.LOCATION_ID)
        WHEN o.`ID` = 81    THEN ( select deposit.`ID` from deposit where deposit.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 82    THEN ( select deposit.`ID` from deposit_funds join deposit on deposit.ID = deposit_funds.DEPOSIT_ID where deposit_funds.ID = aj.OBJECT_ID and deposit.DATE = aj.OBJECT_DATE  and deposit.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 84    THEN ( select general_journal.`ID` from general_journal_details join general_journal on general_journal.ID = general_journal_details.GENERAL_JOURNAL_ID where general_journal_details.ID = aj.OBJECT_ID and general_journal.DATE = aj.OBJECT_DATE  and general_journal.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 93    THEN ( select fund_transfer.`ID` from fund_transfer where fund_transfer.ID = aj.OBJECT_ID and fund_transfer.DATE = aj.OBJECT_DATE  and (fund_transfer.TO_LOCATION_ID = aj.LOCATION_ID or fund_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 70    THEN ( select build_assembly.`ID` from build_assembly where build_assembly.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 71    THEN ( select build_assembly.`ID` from build_assembly_items join build_assembly on build_assembly.ID = build_assembly_items.BUILD_ASSEMBLY_ID  where build_assembly_items.ID = aj.OBJECT_ID and build_assembly.DATE = aj.OBJECT_DATE  and build_assembly.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 72    THEN ( select tax_credit.`ID` from tax_credit where tax_credit.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 73    THEN ( select tax_credit.`ID` from tax_credit_invoices join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID  where tax_credit_invoices.ID = aj.OBJECT_ID and tax_credit.DATE = aj.OBJECT_DATE  and tax_credit.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 113   THEN ( select pull_out.`ID` from pull_out where pull_out.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID   )
        WHEN o.`ID` = 114   THEN ( select pull_out.`ID` from pull_out_items join pull_out on pull_out.ID = pull_out_items.PULL_OUT_ID where pull_out_items.ID = aj.OBJECT_ID and pull_out.DATE = aj.OBJECT_DATE and pull_out.LOCATION_ID = aj.LOCATION_ID )

        WHEN o.`ID` = 127  THEN ( select depreciation.`ID` from depreciation where depreciation.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 128  THEN ( select depreciation.`ID` from depreciation_items join depreciation on depreciation.ID = depreciation_items.DEPRECIATION_ID where depreciation_items.ID = aj.OBJECT_ID and depreciation.DATE = aj.OBJECT_DATE  and depreciation.LOCATION_ID = aj.LOCATION_ID )
        WHEN o.`ID` = 135  THEN ( select bank_transfer.`ID` from bank_transfer where bank_transfer.ID = aj.OBJECT_ID and bank_transfer.DATE = aj.OBJECT_DATE  and (bank_transfer.TO_LOCATION_ID = aj.LOCATION_ID or bank_transfer.FROM_LOCATION_ID = aj.LOCATION_ID ))
        WHEN o.`ID` = 95   THEN ( select hemodialysis.`ID` from hemodialysis where hemodialysis.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 109  THEN ( select hemodialysis.`ID` from hemodialysis inner join hemodialysis_items on hemodialysis.ID = hemodialysis_items.HEMO_ID where hemodialysis_items.ID = aj.OBJECT_ID and hemodialysis.DATE = aj.OBJECT_DATE  and hemodialysis.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 67   THEN ( select withholding_tax.`ID` from withholding_tax where withholding_tax.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE  and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )
        WHEN o.`ID` = 68   THEN ( select withholding_tax.`ID` from withholding_tax_bills join withholding_tax on withholding_tax.ID = withholding_tax_bills.WITHHOLDING_TAX_ID  where withholding_tax_bills.ID = aj.OBJECT_ID and withholding_tax.DATE = aj.OBJECT_DATE and withholding_tax.LOCATION_ID = aj.LOCATION_ID  )

        END as TX_ROUTE_ID';

    private $object;
    private $dateServices;
    private $accountJournalEndingServices;
    public function __construct(ObjectServices $objectService, DateServices $dateServices, AccountJournalEndingServices $accountJournalEndingServices)
    {
        $this->object                       = $objectService;
        $this->dateServices                 = $dateServices;
        $this->accountJournalEndingServices = $accountJournalEndingServices;
    }
    public function DeleteJournal(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $JOURNAL_NO,
        int $SUBSIDIARY_ID,
        int $OBJECT_ID,
        int $OBJECT_TYPE,
        string $OBJECT_DATE,
        $ENTRY_TYPE
    ) {
        $this->JournalModify(
            $ACCOUNT_ID,
            $LOCATION_ID,
            $JOURNAL_NO,
            $SUBSIDIARY_ID,
            $OBJECT_ID,
            $OBJECT_TYPE,
            $OBJECT_DATE,
            $ENTRY_TYPE,
            0,
            0,
            ''
        );
    }
    private function UpdateEntry(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        $EXTENDED_OPTIONS = null
    ) {

        if ($ACCOUNT_ID > 0) {

            $source = AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('ACCOUNT_ID', $ACCOUNT_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->where('ENTRY_TYPE', $ENTRY_TYPE)
                ->first();

            if ($source) {
                $ID = $source->ID;
                $source->update([
                    'AMOUNT'     => $AMOUNT,
                    'ENTRY_TYPE' => $ENTRY_TYPE,
                ]);
                $this->accountJournalEndingServices->Recount($ID);
            }

        } else {

            $source = AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->where('ENTRY_TYPE', $ENTRY_TYPE)
                ->first();

            if ($source) {
                $ID = $source->ID;
                $source->update([
                    'SEQUENCE_GROUP' => $SEQUENCE_GROUP,
                    'ENTRY_TYPE'     => $ENTRY_TYPE,
                    'AMOUNT'         => $AMOUNT,
                ]);
                $this->accountJournalEndingServices->Recount($ID);

            }
        }
    }
    private function Update(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        $EXTENDED_OPTIONS = null
    ) {

        if ($ACCOUNT_ID > 0) {

            $source = AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('ACCOUNT_ID', $ACCOUNT_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->first();

            if ($source) {
                $ID = $source->ID;
                $source->update([
                    'AMOUNT'     => $AMOUNT,
                    'ENTRY_TYPE' => $ENTRY_TYPE,
                ]);
                $this->accountJournalEndingServices->Recount($ID);
            }

        } else {

            $source = AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->first();

            if ($source) {
                $ID = $source->ID;
                $source->update([
                    'SEQUENCE_GROUP' => $SEQUENCE_GROUP,
                    'ENTRY_TYPE'     => $ENTRY_TYPE,
                    'AMOUNT'         => $AMOUNT,
                ]);
                $this->accountJournalEndingServices->Recount($ID);

            }
        }
    }
    private function Store(
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        int $JOURNAL_NO,
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $SUBSIDIARY_ID,
        int $SEQUENCE_GROUP,
        int $OBJECT_TYPE,
        int $OBJECT_ID,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        float $ENDING_BALANCE,
        $EXTENDED_OPTIONS = null
    ) {

        $ID = (int) $this->object->ObjectNextID('ACCOUNT_JOURNAL');
        AccountJournal::create([
            'ID'               => $ID,
            'PREVIOUS_ID'      => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO'      => $SEQUENCE_NO,
            'JOURNAL_NO'       => $JOURNAL_NO,
            'ACCOUNT_ID'       => $ACCOUNT_ID,
            'LOCATION_ID'      => $LOCATION_ID,
            'SUBSIDIARY_ID'    => $SUBSIDIARY_ID,
            'SEQUENCE_GROUP'   => $SEQUENCE_GROUP,
            'OBJECT_TYPE'      => $OBJECT_TYPE,
            'OBJECT_ID'        => $OBJECT_ID,
            'OBJECT_DATE'      => $OBJECT_DATE,
            'ENTRY_TYPE'       => $ENTRY_TYPE,
            'AMOUNT'           => $AMOUNT,
            'ENDING_BALANCE'   => $ENDING_BALANCE,
            'EXTENDED_OPTIONS' => $EXTENDED_OPTIONS,
        ]);

        if (Carbon::parse($this->dateServices->NowDate())->ne(Carbon::parse($OBJECT_DATE))) {
            $this->accountJournalEndingServices->Recount($ID);
        }

    }
    public function getJournalNo(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return (int) AccountJournal::max('JOURNAL_NO');
    }

    public function getRecord(int $OBJECT_TYPE, int $OBJECT_ID): int
    {
        $data = AccountJournal::query()
            ->select(['JOURNAL_NO'])
            ->where('OBJECT_TYPE', $OBJECT_TYPE)
            ->where('OBJECT_ID', $OBJECT_ID)
            ->first();

        if ($data) { // if exists
            return (int) $data->JOURNAL_NO;
        }

        return 0;
    }
    private function getPreviousID(int $ACCOUNT_ID, int $LOCATION_ID): int
    {
        $result = DB::table('account_journal')
            ->select(['ID'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }
        return 0;
    }

    private function getEndingLastOutPut(int $ACCOUNT_ID, int $LOCATION_ID, string $OBJECT_DATE)
    {
        $result = DB::table('account_journal')
            ->select(['SEQUENCE_NO', 'ENDING_BALANCE'])
            ->where('ACCOUNT_ID', $ACCOUNT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('OBJECT_DATE', '<=', $OBJECT_DATE)
            ->orderBy('OBJECT_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return [
                'SEQUENCE_NO'    => $result->SEQUENCE_NO,
                'ENDING_BALANCE' => $result->ENDING_BALANCE,
            ];
        }

        return [
            'SEQUENCE_NO'    => -1,
            'ENDING_BALANCE' => 0,
        ];
    }

    public function getSumDebitCredit(int $JOURNAL_NO)
    {
        $result = AccountJournal::query()
            ->select([
                DB::raw('IFNULL(SUM(IF(ENTRY_TYPE=0, AMOUNT, 0)),0) as DEBIT'),
                DB::raw('IFNULL(SUM(IF(ENTRY_TYPE=1, AMOUNT, 0)),0) as CREDIT'),
            ])
            ->where('ACCOUNT_JOURNAL.JOURNAL_NO', '=', $JOURNAL_NO)
            ->first();

        if ($result) {

            return [
                'DEBIT'  => $result->DEBIT ?? 0,
                'CREDIT' => $result->CREDIT ?? 0,
            ];
        }

        return [
            'DEBIT'  => 0,
            'CREDIT' => 0,
        ];
    }
    private function JournalExists(int $ACCOUNT_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $LOCATION_ID, int $SUBSIDIARY_ID): bool
    {
        $result = (bool) AccountJournal::query()
            ->where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->where('OBJECT_ID', '=', $OBJECT_ID)
            ->where('OBJECT_TYPE', '=', $OBJECT_TYPE)
            ->where('OBJECT_DATE', '=', $OBJECT_DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SUBSIDIARY_ID', '=', $SUBSIDIARY_ID)
            ->exists();

        return $result;
    }
    private function JournalExistsEntity(int $ACCOUNT_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $LOCATION_ID, int $SUBSIDIARY_ID, int $ENTRY_TYPE): bool
    {
        $result = (bool) AccountJournal::query()
            ->where('ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->where('OBJECT_ID', '=', $OBJECT_ID)
            ->where('OBJECT_TYPE', '=', $OBJECT_TYPE)
            ->where('OBJECT_DATE', '=', $OBJECT_DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SUBSIDIARY_ID', '=', $SUBSIDIARY_ID)
            ->where('ENTRY_TYPE', '=', $ENTRY_TYPE)
            ->exists();

        return $result;
    }
    public function AccountSwitch(int $NEW_ACCOUNT_ID, int $OLD_ACCOUNT_ID, int $LOCATION_ID, int $JOURNAL_NO, int $SUBSIDIARY_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, )
    {

        if (
            $this->JournalExists(
                $OLD_ACCOUNT_ID,
                $OBJECT_ID,
                $OBJECT_TYPE,
                $OBJECT_DATE,
                $LOCATION_ID,
                $SUBSIDIARY_ID,
            )
        ) {
            $source = AccountJournal::where('LOCATION_ID', $LOCATION_ID)
                ->where('JOURNAL_NO', $JOURNAL_NO)
                ->where('ACCOUNT_ID', $OLD_ACCOUNT_ID)
                ->where('OBJECT_TYPE', $OBJECT_TYPE)
                ->where('OBJECT_ID', $OBJECT_ID)
                ->where('OBJECT_DATE', $OBJECT_DATE)
                ->where('SUBSIDIARY_ID', $SUBSIDIARY_ID)
                ->first();

            if ($source) {

                $ID = $source->ID;
                $source->update([
                    'ACCOUNT_ID' => $NEW_ACCOUNT_ID,
                ]);

                if (Carbon::parse($this->dateServices->NowDate())->ne(Carbon::parse($OBJECT_DATE))) {
                    $this->accountJournalEndingServices->Recount($ID);
                }
            }

        }
    }
    public function JournalModify(
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $JOURNAL_NO,
        int $SUBSIDIARY_ID,
        int $OBJECT_ID,
        int $OBJECT_TYPE,
        string $OBJECT_DATE,
        int $ENTRY_TYPE,
        float $AMOUNT,
        int $SEQUENCE_GROUP,
        string $EXTENDED_OPTIONS
    ) {

        $JOURNAL_EXISTS = false;
        if ($OBJECT_TYPE == LogEntity::FUND_TRANSFER->value || $OBJECT_TYPE == LogEntity::FUND_TRANSFER_REVERSE->value) {
            $JOURNAL_EXISTS = $this->JournalExistsEntity($ACCOUNT_ID, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $SUBSIDIARY_ID, $ENTRY_TYPE);
        } else {
            $JOURNAL_EXISTS = $this->JournalExists($ACCOUNT_ID, $OBJECT_ID, $OBJECT_TYPE, $OBJECT_DATE, $LOCATION_ID, $SUBSIDIARY_ID);
        }

        if (! $JOURNAL_EXISTS) {

            if ($ACCOUNT_ID == 0) {
                return;
            }

            $PREV_ID        = (int) $this->getPreviousID($ACCOUNT_ID, $LOCATION_ID);
            $ENDING         = $this->getEndingLastOutPut($ACCOUNT_ID, $LOCATION_ID, $OBJECT_DATE);
            $SEQUENCE_NO    = (int) $ENDING['SEQUENCE_NO'];
            $ENDING_BALANCE = 0;

            if ($ENTRY_TYPE == 0) {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] + $AMOUNT;
            } else {
                $ENDING_BALANCE = (float) $ENDING['ENDING_BALANCE'] - $AMOUNT;
            }

            $this->Store(
                $PREV_ID,
                $SEQUENCE_NO + 1,
                $JOURNAL_NO,
                $ACCOUNT_ID,
                $LOCATION_ID,
                $SUBSIDIARY_ID,
                $SEQUENCE_GROUP,
                $OBJECT_TYPE,
                $OBJECT_ID,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT,
                $ENDING_BALANCE,
                $EXTENDED_OPTIONS
            );
            return;
        }

        if ($OBJECT_TYPE == LogEntity::FUND_TRANSFER->value || $OBJECT_TYPE == LogEntity::FUND_TRANSFER_REVERSE->value) {
            $this->UpdateEntry(
                $ACCOUNT_ID,
                $LOCATION_ID,
                $SUBSIDIARY_ID,
                $SEQUENCE_GROUP,
                $OBJECT_TYPE,
                $OBJECT_ID,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT,
                $EXTENDED_OPTIONS
            );
            return;
        }

        $this->Update(
            $ACCOUNT_ID,
            $LOCATION_ID,
            $SUBSIDIARY_ID,
            $SEQUENCE_GROUP,
            $OBJECT_TYPE,
            $OBJECT_ID,
            $OBJECT_DATE,
            $ENTRY_TYPE,
            $AMOUNT,
            $EXTENDED_OPTIONS
        );
        // no more textended function

    }
    public function JournalExecute(int $JOURNAL_NO, $data, int $LOCATION_ID, int $OBJECT_TYPE, string $OBJECT_DATE, string $EXTENDED = '')
    {
        foreach ($data as $list) {
            $OBJECT_ID        = (int) $list->ID;
            $ACCOUNT_ID       = (int) $list->ACCOUNT_ID;
            $SUBSIDIARY_ID    = (int) $list->SUBSIDIARY_ID;
            $ENTRY_TYPE       = (int) $list->ENTRY_TYPE;
            $AMOUNT           = (float) $list->AMOUNT;
            $SEQUENCE_GROUP   = 0;
            $EXTENDED_OPTIONS = $EXTENDED;

            if (isset($list->SEQUENCE_GROUP)) {
                $SEQUENCE_GROUP = $list->SEQUENCE_GROUP;
            }

            $this->JournalModify(
                $ACCOUNT_ID,
                $LOCATION_ID,
                $JOURNAL_NO,
                $SUBSIDIARY_ID,
                $OBJECT_ID,
                $OBJECT_TYPE,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT < 0 ? $AMOUNT * -1 : $AMOUNT,
                $SEQUENCE_GROUP,
                $EXTENDED_OPTIONS
            );
        }
    }

    public function getJournalList(int $JOURNAL_NO): object
    {

        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.JOURNAL_NO', $JOURNAL_NO)
            ->get();

        return $result;
    }

    public function getGeneralLedgerList(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = []): object
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.ID',
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->when($dateTo != "none", function ($query) use (&$dateFrom, &$dateTo) {
                $query->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo]);
            })
            ->when($dateTo == "none", function ($query) use (&$dateFrom) {
                $query->where('aj.OBJECT_DATE', '<=', $dateFrom);
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->orderBy('a.TAG', 'asc')
            ->orderBy('aj.OBJECT_DATE', 'asc')
            ->get();

        return $result;
    }

    public function getTrialBalance(string $dateAs, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = [])
    {
        $result = DB::table('account as a')
            ->select(
                [
                    'a.NAME as ACCOUNT_TITLE',
                    DB::raw("sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)) as AMOUNT "),
                    DB::raw("
                    CASE
                        WHEN t.`ACCOUNT_ORDER` = 0 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 1 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 5 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                    END as TX_DEBIT
                    "),
                    DB::raw("
                    CASE
                        WHEN t.`ACCOUNT_ORDER` = 2 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 3 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                        WHEN t.`ACCOUNT_ORDER` = 4 THEN (sum( if(aj.ENTRY_TYPE = 0, aj.AMOUNT,0) - if(aj.ENTRY_TYPE = 1, aj.AMOUNT, 0)))
                    END as TX_CREDIT
                    "),
                    't.ACCOUNT_ORDER',
                ]
            )
            ->leftJoin('account_journal as aj', 'aj.ACCOUNT_ID', '=', 'a.ID')
            ->leftJoin('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->when($dateTo != "none", function ($query) use (&$dateAs, &$dateTo) {
                $query->whereBetween('aj.OBJECT_DATE', [$dateAs, $dateTo]);
            })
            ->when($dateTo == "none", function ($query) use (&$dateAs) {
                $query->where('aj.OBJECT_DATE', '<=', $dateAs);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('t.ID', $accountType);
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->groupBy(['a.NAME', 't.ACCOUNT_ORDER'])
            ->orderBy('t.ACCOUNT_ORDER')
            ->get();

        return $result;
    }

    public function getTransactionJournal(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = [])
    {

        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, (" . $this->CHECK_TYPE . "), d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
                DB::raw($this->TX_ROUTE_ID),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->orderBy('aj.JOURNAL_NO')
            ->get();

        return $result;
    }
    public function getTransactionJournalError(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = [])
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.ID',
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, (" . $this->CHECK_TYPE . "), d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
                DB::raw($this->TX_ROUTE_ID),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->whereRaw("($this->TX_CODE_E) IS NULL")
            ->get();

        return $result;
    }
    public function getTransactionJournalMissing(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {

        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE',

                DB::raw('COUNT(*) as C_COUNT'),
                DB::raw('SUM(CASE WHEN aj.ENTRY_TYPE = 0 THEN aj.AMOUNT ELSE 0 END) as DEBIT'),
                DB::raw('SUM(CASE WHEN aj.ENTRY_TYPE = 1 THEN aj.AMOUNT ELSE 0 END) as CREDIT'),
                'l.NAME as LOCATION',
            ])
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', 0)

        // Better than YEAR() and MONTH()
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->groupBy([
                'aj.JOURNAL_NO',
                'aj.LOCATION_ID',
                'aj.OBJECT_DATE',
            ])

            ->havingRaw('DEBIT <> CREDIT')

            ->get();

        return $result;
    }
    public function getTransactionJournalUnposted(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = [])
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.ID',
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, (" . $this->CHECK_TYPE . "), d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
                DB::raw($this->TX_ROUTE_ID),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })
            ->whereRaw("($this->TX_CODE_E) like 'U:%'")
            ->get();

        return $result;
    }

    public function getTransactionJournalErrorUpdate(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        $result = DB::table('account_journal as aj')
            ->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereRaw("($this->TX_CODE_E) IS NULL")
            ->update([
                'AMOUNT' => 0,
            ]);

    }
    public function getUndepositedActiveList(int $LOCATION_ID)
    {
        $undeposited_account_id = 0;
        $result                 = DB::table('account_journal as aj')
            ->select([
                'aj.OBJECT_DATE as DATE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', '=', $undeposited_account_id)
            ->get();

        return $result;
    }

    public function getAccountTransaction(string $dateFrom, string $dateTo, int $LOCATION_ID, array $account = [], array $accountType = []): object
    {
        $forwardedQuery = DB::table('account_journal as aj')
            ->select([
                DB::raw("'F' as JOURNAL_NO"),
                DB::raw("'2020-1-1' as DATE"),
                DB::raw("'' as ACCOUNT_CODE"),
                DB::raw("a.NAME as ACCOUNT_TITLE"),
                DB::raw("'' as TYPE"),
                DB::raw("'' as LOCATION"),
                DB::raw("'' as TX_NAME"),
                DB::raw("'' as TX_CODE"),
                DB::raw("'' as TX_NOTES"),
                DB::raw("0 as DEBIT"),
                DB::raw("0 as CREDIT"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = 0,AMOUNT,0)) - SUM(if(aj.ENTRY_TYPE = 1,AMOUNT,0))  as BALANCE"),

            ])
            ->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.OBJECT_DATE', '<', $dateFrom)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            })->groupBy(['aj.ACCOUNT_ID', 'a.NAME']);

        $resultQuery = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_NAME),
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw(" 0  as BALANCE"),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($account, function ($query) use (&$account) {
                $query->whereIn('aj.ACCOUNT_ID', $account);
            })
            ->when($accountType, function ($query) use (&$accountType) {
                $query->whereIn('a.TYPE', $accountType);
            });

        $final_result = DB::query()
            ->fromSub(
                $forwardedQuery->union($resultQuery),
                'combined_results'
            )
            ->orderBy('ACCOUNT_TITLE', 'asc')
            ->orderBy('DATE', 'asc')
            ->get();

        return $final_result;
    }
    public function getTransactionJournalViewer(int $ACCOUNT_ID, int $YEAR, int $MONTH, int $LOCATION_ID)
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereYear('aj.OBJECT_DATE', '=', $YEAR)
            ->whereMonth('aj.OBJECT_DATE', '=', $MONTH)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->get();

        return $result;
    }
    public function getTransactionJournalViewerSummary(int $ACCOUNT_ID, string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->get();

        return $result;
    }
    public function getTransactionBalance(int $ACCOUNT_ID, int $LOCATION_ID)
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'aj.JOURNAL_NO',
                'aj.OBJECT_DATE as DATE',
                'a.TAG as ACCOUNT_CODE',
                'a.NAME as ACCOUNT_TITLE',
                DB::raw("if(d.ID = 21, $this->CHECK_TYPE, d.DESCRIPTION) as TYPE"),
                'l.NAME as LOCATION',
                DB::raw($this->TX_CODE),
                DB::raw($this->TX_NOTES),
                DB::raw(" if(aj.ENTRY_TYPE = 0, aj.AMOUNT, '' ) as DEBIT "),
                DB::raw(" if(aj.ENTRY_TYPE = 1, aj.AMOUNT, '' ) as CREDIT "),
                DB::raw($this->TX_NAME),
                'aj.ENDING_BALANCE',
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->leftJoin('object_type_map as o', 'o.ID', '=', 'aj.OBJECT_TYPE')
            ->leftJoin('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->leftJoin('location as l', 'l.ID', '=', 'aj.LOCATION_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', '=', $ACCOUNT_ID)
            ->orderBy('aj.OBJECT_DATE', 'desc')
            ->orderBy('aj.ID', 'desc')
            ->get();

        return $result;
    }
    public function updateObjectDate(int $JOURNAL_NO, string $NEW_DATE)
    {
        if ($JOURNAL_NO > 0) {
            AccountJournal::where('JOURNAL_NO', '=', $JOURNAL_NO)
                ->update(['OBJECT_DATE' => $NEW_DATE]);
        }

    }
    public function updateAccount(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $LOCATION_ID, int $OLD_ACCOUNT_ID, int $NEW_ACCOUNT_ID)
    {
        AccountJournal::where('OBJECT_ID', '=', $OBJECT_ID)
            ->where('OBJECT_TYPE', '=', $OBJECT_TYPE)
            ->where('OBJECT_DATE', '=', $OBJECT_DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('ACCOUNT_ID', '=', $OLD_ACCOUNT_ID)
            ->update([
                'ACCOUNT_ID' => $NEW_ACCOUNT_ID,
            ]);

    }
    public function parameterUpdate($where = [], $update = [])
    {

        AccountJournal::where($where)->update($update);
    }
    public function getUrlBy(int $Journal_no): string
    {
        $URL    = "";
        $result = AccountJournal::select(['d.ID as DOC_ID', 'account_journal.OBJECT_ID'])
            ->join('object_type_map as o', 'o.ID', '=', 'account_journal.OBJECT_TYPE')
            ->join('document_type_map as d', 'd.ID', '=', 'o.DOCUMENT_TYPE')
            ->where('account_journal.JOURNAL_NO', '=', $Journal_no)
            ->whereIn('account_journal.OBJECT_TYPE', ['2', '12', '16', '19', '23', '38', '41', '52', '59', '57', '67', '70', '72', '81', '83', '84', '93', '95', '113', '121', '127', '135'])
            ->limit(1)
            ->first();

        if ($result) {

            $DOC_ID = (int) $result->DOC_ID;

            switch ($DOC_ID) {
                case 1:
                    $URL = route('vendorsbills_edit', ['id' => $result->OBJECT_ID]); // bill
                    break;
                case 2:
                    $URL = route('vendorsbill_payment_edit', ['id' => $result->OBJECT_ID]); // bill payment
                    break;
                case 3:
                    $URL = route('vendorsbill_credit_edit', ['id' => $result->OBJECT_ID]); // bill credit
                    break;
                case 6:
                    $URL = route('companyinventory_adjustment_edit', ['id' => $result->OBJECT_ID]); // inventory adjustment
                    break;
                case 7:
                    $URL = route('companystock_transfer_edit', ['id' => $result->OBJECT_ID]); // stock transfer
                    break;
                case 10:
                    $URL = route('customersinvoice_edit', ['id' => $result->OBJECT_ID]); // invoice
                    break;
                case 11:
                    $URL = route('customerspayment_edit', ['id' => $result->OBJECT_ID]); // payment
                    break;
                case 12:
                    $URL = route('customerscredit_memo_edit', ['id' => $result->OBJECT_ID]); // credit_memo
                    break;
                case 13:
                    $URL = route('customerssales_receipt_edit', ['id' => $result->OBJECT_ID]); // sales receipt
                    break;
                case 18:
                    $URL = route('vendorswithholding_tax_edit', ['id' => $result->OBJECT_ID]); // withholding tax
                    break;
                case 19:
                    $URL = route('companybuild_assembly_edit', ['id' => $result->OBJECT_ID]); // build assembply
                    break;
                case 20:
                    $URL = route('customerstax_credit_edit', ['id' => $result->OBJECT_ID]); // tax credit
                    break;
                case 21:

                    $dataResult = DB::table('check')->select(['TYPE'])->where('ID', '=', $result->OBJECT_ID)->first();
                    if ($dataResult) {
                        if ($dataResult->TYPE == 0) {
                            $URL = route('bankingmake_cheque_edit', ['id' => $result->OBJECT_ID]); // write check
                        } else {
                            $URL = route('vendorsbill_payment_edit', ['id' => $result->OBJECT_ID]); // bill payment
                        }
                    }
                    break;
                case 22:
                    $URL = route('bankingdeposit_edit', ['id' => $result->OBJECT_ID]); // deposit
                    break;
                case 23:
                    $dataRes = DB::table("general_journal_details")->select(['GENERAL_JOURNAL_ID'])->where('ID', '=', $result->OBJECT_ID)->first();
                    if ($dataRes) {
                        $URL = route('companygeneral_journal_edit', ['id' => $dataRes->GENERAL_JOURNAL_ID]); // general journal
                    }
                    break;
                case 26:
                    $URL = route('bankingfund_transfer_edit', ['id' => $result->OBJECT_ID]); // fund transfer
                    break;
                case 27:
                    $URL = route('patientshemo_edit', ['id' => $result->OBJECT_ID]); // fund transfer
                    break;
                case 31:
                    $URL = route('companypull_out_edit', ['id' => $result->OBJECT_ID]); // pull out
                    break;
                case 32:
                    $URL = route('bankingbank_recon_edit', ['id' => $result->OBJECT_ID]); // bank recon
                    break;
                case 33:
                    $URL = route('companydepreciation_edit', ['id' => $result->OBJECT_ID]); // depreciation
                    break;
                case 34:
                    $URL = route('bankingbank_transfer_credit', ['id' => $result->OBJECT_ID]); // bank transfer
                    break;
                default:
                    # code...
                    break;
            }

        }

        return $URL;
    }
    public function setZeroUpdate(int $id)
    {
        AccountJournal::where('ID', '=', $id)
            ->update([
                'AMOUNT' => 0,
            ]);
    }

    public function DeleteRecordJournal(int $JOURNAL_NO, string $OBJECT_DATE, int $LOCATION_ID)
    {
        AccountJournal::where('JOURNAL_NO', '=', $JOURNAL_NO)
            ->where('OBJECT_DATE', '=', $OBJECT_DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->update([
                'AMOUNT' => 0,
            ]);
    }
    public function UpdatedJournalAmountZero(int $JOURNAL_NO)
    {
        AccountJournal::where('JOURNAL_NO', '=', $JOURNAL_NO)
            ->update([
                'AMOUNT' => 0,
            ]);
    }
}
