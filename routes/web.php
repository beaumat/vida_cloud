<?php

use App\Livewire\AccountingReport\GeneralLedgerGenerate;
use App\Livewire\AccountingReport\GeneralLedgerReport;
use App\Livewire\AccountingReport\TransactionDetailsGenerate;

use App\Livewire\AccountingReport\TransactionDetailsMissing;
use App\Livewire\AccountingReport\TransactionDetailsReport;
use App\Livewire\AccountingReport\TransactionJournalError;
use App\Livewire\AccountingReport\TransactionJournalGenerate;
use App\Livewire\AccountingReport\TransactionJournalReport;
use App\Livewire\AccountingReport\TransactionJournalUnpost;
use App\Livewire\AccountingReport\TrialBalanceGenerate;
use App\Livewire\AccountingReport\TrialBalanceReport;
use App\Livewire\BankRecon\BankReconForm;
use App\Livewire\BankRecon\BankReconFormPrint;
use App\Livewire\BankRecon\BankReconList;
use App\Livewire\BankStatement\BankStatementForm;
use App\Livewire\BankStatement\BankStatementList;
use App\Livewire\BankStatement\BankStatementPrint;
use App\Livewire\BankTransfer\BankTransferForm;
use App\Livewire\BankTransfer\BankTransferList;
use App\Livewire\BillCredit\BillCreditForm;
use App\Livewire\BillCredit\BillCreditList;
use App\Livewire\BillPayments\BillPaymentForm;
use App\Livewire\BillPayments\BillPaymentList;
use App\Livewire\BillPayments\BillPaymentPrint;
use App\Livewire\BillPayments\DoctorFeePrint2;
use App\Livewire\BillPayments\DoctorFeePrint;
use App\Livewire\Bills\BillingForm;
use App\Livewire\Bills\BillingList;
use App\Livewire\Bills\BillingPrint;
use App\Livewire\BuildAssembly\BuildAssemblyForm;
use App\Livewire\BuildAssembly\BuildAssemblyList;
use App\Livewire\ChartOfAccount\ChartOfAccountEndingBalance;
use App\Livewire\ChartOfAccount\ChartOfAccountForm;
use App\Livewire\ChartOfAccount\ChartOfAccountList;
use App\Livewire\CostAdjustment\CostAdjustmentForm;
use App\Livewire\CostAdjustment\CostAdjustmentList;
use App\Livewire\CreditMemo\CreditMemoForm;
use App\Livewire\CreditMemo\CreditMemoList;
use App\Livewire\CustomerReport\CustomerSalesReport;
use App\Livewire\Customer\CustomerForm;
use App\Livewire\Customer\CustomerList;
use App\Livewire\DashboardPage\Dashboard;
use App\Livewire\Deposit\DepositForm;
use App\Livewire\Deposit\DepositList;
use App\Livewire\Depreciation\DepreciationForm;
use App\Livewire\Depreciation\DepreciationList;
use App\Livewire\DoctorBatchPayment\DoctorBatchForm;
use App\Livewire\DoctorBatchPayment\DoctorBatchList;
use App\Livewire\DoctorBatchPayment\DoctorBatchPrint;
use App\Livewire\DoctorBatchPayment\DoctorBatchSummaryPrint;
use App\Livewire\DoctorFee\DoctorFeeList;
use App\Livewire\Doctor\DoctorForm;
use App\Livewire\Doctor\DoctorList;
use App\Livewire\Employees\EmployeeForm;
use App\Livewire\Employees\EmployeeList;
use App\Livewire\FinancialReport\BalanceSheetReport;
use App\Livewire\FinancialReport\PettyCashReport;
use App\Livewire\FinancialReport\CashFlowReport;
use App\Livewire\FinancialReport\EquityReport;
use App\Livewire\FinancialReport\IncomeStatementReport;
use App\Livewire\FixedAssetItem\FixedAssetItemForm;
use App\Livewire\FixedAssetItem\FixedAssetItemList;
use App\Livewire\FundTransfer\FundTransferForm;
use App\Livewire\FundTransfer\FundTransferList;
use App\Livewire\FundTransfer\FundTransferPrint;
use App\Livewire\GeneralJournal\GeneralJournalForm;
use App\Livewire\GeneralJournal\GeneralJournalList;
use App\Livewire\GeneralJournal\GeneralJournalPrint;
use App\Livewire\HemodialysisMachine\HemoMachineForm;
use App\Livewire\HemodialysisMachine\HemoMachineList;
use App\Livewire\Hemodialysis\AgreementForm;
use App\Livewire\Hemodialysis\HemoForm;
use App\Livewire\Hemodialysis\HemoList;
use App\Livewire\Hemodialysis\PrintForm;
use App\Livewire\Hemodialysis\PrintFormBack;
use App\Livewire\Hemodialysis\PrintFormFrontBack;
use App\Livewire\Import\XeroImportForm;
use App\Livewire\IncomeStatement\IncomeStatementAccountDetails;
use App\Livewire\IncomeStatement\IncomeStatementAccountSummary;
use App\Livewire\InventoryAdjustmentTypePage\InventoryAdjustmentTypeForm;
use App\Livewire\InventoryAdjustmentTypePage\InventoryAdjustmentTypeList;
use App\Livewire\InventoryAdjustment\InventoryAdjustmentForm;
use App\Livewire\InventoryAdjustment\InventoryAdjustmentList;
use App\Livewire\InventoryReport\UsageReport;
use App\Livewire\InventoryReport\ValidationSummaryReport;
use App\Livewire\Invoice\InvoiceForm;
use App\Livewire\Invoice\InvoiceList;
use App\Livewire\Invoice\PrintInvoice;
use App\Livewire\Invoice\QuickPaid;
use App\Livewire\Invoice\QuickPhilhealthPaid;
use App\Livewire\ItemClassPage\ItemClassForm;
use App\Livewire\ItemClassPage\ItemClassList;
use App\Livewire\ItemGroupPage\ItemGroupForm;
use App\Livewire\ItemGroupPage\ItemGroupList;
use App\Livewire\ItemPage\ItemsForm;
use App\Livewire\ItemPage\ItemsList;
use App\Livewire\ItemSubClassPage\ItemSubClassForm;
use App\Livewire\ItemSubClassPage\ItemSubClassList;
use App\Livewire\ItemTreatment\ItemTreatmentForm;
use App\Livewire\ItemTreatment\ItemTreatmentList;
use App\Livewire\List\ItemActiveList;
use App\Livewire\List\ItemInventoryDetails;
use App\Livewire\LocationGroup\LocationGroupForm;
use App\Livewire\LocationGroup\LocationGroupList;
use App\Livewire\Location\DoctorNotes;
use App\Livewire\Location\LocationDoctors;
use App\Livewire\Location\LocationForm;
use App\Livewire\Location\LocationList;
use App\Livewire\Location\SoaItem;
use App\Livewire\ManufacturerPage\ManufacturerForm;
use App\Livewire\ManufacturerPage\ManufacturerList;
use App\Livewire\Option\OptionSettings;
use App\Livewire\PatientPayment\PatientPaymentForm;
use App\Livewire\PatientPayment\PatientPaymentList;
use App\Livewire\PatientReport\DoctorsFeeReportPrint;
use App\Livewire\PatientReport\GuaranteeLetterReport;
use App\Livewire\PatientReport\PatientBalanceReport;
use App\Livewire\PatientReport\PatientInventoryReport;
use App\Livewire\PatientReport\PatientSalesReport2;
use App\Livewire\PatientReport\PatientSalesReportPrint;
use App\Livewire\PatientReport\PatientTreatmentReport;
use App\Livewire\PatientReport\PhilhealthAnnex;
use App\Livewire\PatientReport\PhilhealthAnnexOnePrint;
use App\Livewire\PatientReport\PhilhealthAnnexTwo;
use App\Livewire\PatientReport\PhilhealthAnnexTwoPrint;
use App\Livewire\PatientReport\PhilHealthAvailmentList;
use App\Livewire\PatientReport\PhilHealthAvailmentListPrint;
use App\Livewire\PatientReport\PhilHealthMonitoringReport;
use App\Livewire\Patient\MedcertPrint;
use App\Livewire\Patient\PatientForm;
use App\Livewire\Patient\PatientList;
use App\Livewire\Patient\PrintAvailment;
use App\Livewire\PayableReport\AccountPayableAging;
use App\Livewire\PayableReport\VendorBalance;
use App\Livewire\PaymentMethod\PaymentMethodForm;
use App\Livewire\PaymentMethod\PaymentMethodList;
use App\Livewire\PaymentPeriod\PaymentPeriodForm;
use App\Livewire\PaymentPeriod\PaymentPeriodList;
use App\Livewire\PaymentTerm\PaymentTermForm;
use App\Livewire\PaymentTerm\PaymentTermList;
use App\Livewire\Payment\PaymentForm;
use App\Livewire\Payment\PaymentList;
use App\Livewire\PhilhealthPrint\PrintOutCf2;
use App\Livewire\PhilhealthPrint\PrintOutNCR;
use App\Livewire\PhilhealthPrint\PrintOutCf4;
use App\Livewire\PhilhealthPrint\PrintOutCf4TempOut;
use App\Livewire\PhilhealthPrint\PrintOutCsf;
use App\Livewire\PhilhealthPrint\PrintOutCsfTemp;
use App\Livewire\PhilhealthPrint\PrintOutCsfTempOut;
use App\Livewire\PhilhealthPrint\PrintOutSoa;
use App\Livewire\PhilhealthPrint\PrintOutSoaTemp;
use App\Livewire\PhilhealthPrint\PrintOutSoaTempOut;
use App\Livewire\PhilhealthPrint\PrintOutSummary;
use App\Livewire\PhilhealthPrint\PrintOutSummaryTemp;
use App\Livewire\PhilhealthPrint\PrintOutSummaryTempOut;
use App\Livewire\PhilHealthSoaCustom\PhilCustomSoaForm;
use App\Livewire\PhilHealthSoaCustom\PhilCustomSoaList;
use App\Livewire\PhilHealth\PhilHealthForm;
use App\Livewire\PhilHealth\PhilHealthList;
use App\Livewire\PhilHealth\PhilHealthManualList;
use App\Livewire\PriceLevelPage\PriceLevelForm;
use App\Livewire\PriceLevelPage\PriceLevelList;
use App\Livewire\PriceLocation\PriceLocationList;
use App\Livewire\PullOut\PullOutForm;
use App\Livewire\PullOut\PullOutList;
use App\Livewire\PullOut\PullOutPrint;
use App\Livewire\PurchaseOrder\PurchaseOrderForm;
use App\Livewire\PurchaseOrder\PurchaseOrderList;
use App\Livewire\PurchaseOrder\PurchaseOrderPrint;
use App\Livewire\ReceivableReport\AccountReceivableAging;
use App\Livewire\ReceivableReport\CustomerBalance;
use App\Livewire\ReceiveMoney\ReceiveMoneyForm;
use App\Livewire\ReceiveMoney\ReceiveMoneyList;
use App\Livewire\Requirement\RequirementForm;
use App\Livewire\Requirement\RequirementList;
use App\Livewire\RolePermissionPage\RolePermissionConfig;
use App\Livewire\RolePermissionPage\RolePermissionList;
use App\Livewire\SalesOrder\SalesOrderForm;
use App\Livewire\SalesOrder\SalesOrderList;
use App\Livewire\SalesReceipt\SalesReceiptForm;
use App\Livewire\SalesReceipt\SalesReceiptList;
use App\Livewire\Scheduler\PrintSchedulesPrintOut;
use App\Livewire\Scheduler\SchedulerForm;
use App\Livewire\Scheduler\SchedulerList;
use App\Livewire\ServiceCharge\ServiceChargeForm;
use App\Livewire\ServiceCharge\ServiceChargeList;
use App\Livewire\Shift\ShiftForm;
use App\Livewire\Shift\ShiftList;
use App\Livewire\ShipViaPage\ShipViaForm;
use App\Livewire\ShipViaPage\ShipViaList;
use App\Livewire\SpendMoney\SpendMoneyForm;
use App\Livewire\SpendMoney\SpendMoneyList;
use App\Livewire\Statement\Statement;
use App\Livewire\Statement\StatementPrint;
use App\Livewire\Statement\StatementView;
use App\Livewire\StockBinPage\StockBinForm;
use App\Livewire\StockBinPage\StockBinList;
use App\Livewire\StockTransfer\StockReceived;
use App\Livewire\StockTransfer\StockTransferForm;
use App\Livewire\StockTransfer\StockTransferList;
use App\Livewire\TaxCredit\TaxCreditForm;
use App\Livewire\TaxCredit\TaxCreditList;
use App\Livewire\Tax\TaxForm;
use App\Livewire\Tax\TaxList;
use App\Livewire\TestHemoPage;
use App\Livewire\TestPage;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureForm;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureList;
use App\Livewire\User\UserForm;
use App\Livewire\User\UserList;
use App\Livewire\User\UserRoles;
use App\Livewire\Vendor\VendorForm;
use App\Livewire\Vendor\VendorList;
use App\Livewire\WithHoldingTax\WithHoldingTaxForm;
use App\Livewire\WithHoldingTax\WithHoldingTaxList;
use App\Livewire\WriteCheck\WriteCheckForm;
use App\Livewire\WriteCheck\WriteCheckFormPrint;
use App\Livewire\WriteCheck\WriteCheckList;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
Route::get('/test', TestPage::class)->name('testpage');
Route::get('/test/{id}', TestPage::class)->name('testpage_id');
Route::get('/test-hemo', TestHemoPage::class)->name('test_hemo_page');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('/patients')->name('patients')->group(function () {
        Route::prefix('/schedules')->group(function () {
            Route::get('/', SchedulerList::class)->name('schedules')->middleware(['permission:patient.schedule.view']);
            Route::get('/setup', SchedulerForm::class)->name('schedules_setup')->middleware(['permission:patient.schedule.modify']);
            Route::get('/{year}/{month}/{week}/{location}/{shift}/print-form', PrintSchedulesPrintOut::class)->name('schedules_print')->middleware(['permission:patient.schedule.print']);
        });

        Route::prefix('/service-charges')->group(function () {
            Route::get('/', ServiceChargeList::class)->name('service_charges')->middleware(['permission:patient.service-charges.view']);
            Route::get('/create', ServiceChargeForm::class)->name('service_charges_create')->middleware(['permission:patient.service-charges.create']);
            Route::get('/{id}/edit', ServiceChargeForm::class)->name('service_charges_edit')->middleware(['permission:patient.service-charges.view']);
        });

        Route::prefix('/payments')->group(function () {
            Route::get('/', PatientPaymentList::class)->name('payment')->middleware(['permission:patient.payment.view']);
            Route::get('/create', PatientPaymentForm::class)->name('payment_create')->middleware(['permission:patient.payment.create']);
            Route::get('/{id}/edit', PatientPaymentForm::class)->name('payment_edit')->middleware(['permission:patient.payment.view']);
        });

        Route::prefix('/hemodialysis-treatment')->group(function () {
            Route::get('/', HemoList::class)->name('hemo')->middleware(['permission:patient.treatment.view']);
            Route::get('/create', HemoForm::class)->name('hemo_create')->middleware(['permission:patient.treatment.create']);
            Route::get('/{id}/edit', HemoForm::class)->name('hemo_edit')->middleware(['permission:patient.treatment.view']);
            Route::get('/{id}/print', PrintForm::class)->name('hemo_print')->middleware(['permission:patient.treatment.print']);
            Route::get('/{id}/print_back', PrintFormBack::class)->name('hemo_print_back')->middleware(['permission:patient.treatment.print']);
            Route::get('/{id}/print_front_back', PrintFormFrontBack::class)->name('hemo_print_front_back')->middleware(['permission:patient.treatment.print']);
            Route::get('/{id}/agreement_form', AgreementForm::class)->name('agreement_form')->middleware(['permission:patient.treatment.print']);
        });

        Route::prefix('/phil-health')->group(function () {
            Route::get('/', PhilHealthList::class)->name('phic')->middleware(['permission:patient.philhealth.view']);
            Route::get('/create', PhilHealthForm::class)->name('phic_create')->middleware(['permission:patient.philhealth.create']);
            Route::get('/{id}/edit', PhilHealthForm::class)->name('phic_edit')->middleware(['permission:patient.philhealth.view']);

            // Print on Philhealth
            Route::get('/{id}/printout-soa', PrintOutSoa::class)->name('printout_soa')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-summary', PrintOutSummary::class)->name('printout_summary')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-csf', PrintOutCsf::class)->name('printout_csf')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-cf4', PrintOutCf4::class)->name('printout_cf4')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-cf2', PrintOutCf2::class)->name('printout_cf2')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-ncr', PrintOutNCR::class)->name('printout_ncr')->middleware(['permission:patient.philhealth.print']);
            
            // Print Temporary Pre-sign
            Route::get('/{id}/printout-soa-temp', PrintOutSoaTemp::class)->name('printout_soa_temp')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-summary-temp', PrintOutSummaryTemp::class)->name('printout_summary_temp')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-csf-temp', PrintOutCsfTemp::class)->name('printout_csf_temp')->middleware(['permission:patient.philhealth.print']);

            // Print Temporary Pre-sign Result Only
            Route::get('/{id}/printout-soa-temp-out', PrintOutSoaTempOut::class)->name('printout_soa_temp_out')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-summary-temp-out', PrintOutSummaryTempOut::class)->name('printout_summary_temp_out')->middleware(['permission:patient.philhealth.print']);
            Route::get('/{id}/printout-csf-temp-out', PrintOutCsfTempOut::class)->name('printout_csf_temp_out')->middleware(['permission:patient.philhealth.print']);
            // PrintOutCf4TempOut
            Route::get('/{id}/printout-cf4-temp-out', PrintOutCf4TempOut::class)->name('printout_cf4_temp_out')->middleware(['permission:patient.philhealth.print']);

        });
        Route::prefix('/phil-health-manual')->group(function () {
            Route::get('/', PhilHealthManualList::class)->name('phic-manual')->middleware(['permission:patient.philhealth.manual.view']);
        });
        Route::prefix('/doctor-pf')->group(function () {
            Route::get('/', DoctorFeeList::class)->name('doctor_fee')->middleware(['permission:report.patient.doctor-pf']);
            Route::get('/{id}/{locationid}/print-form', DoctorsFeeReportPrint::class)->name('doctor_fee_print');
        });
        Route::prefix('/doctor-batch-payment')->group(function () {
            Route::get('/', DoctorBatchList::class)->name('doctor_batch')->middleware(['permission:patient.doctor.batch.view']);
            Route::get('/create', DoctorBatchForm::class)->name('doctor_batch_create')->middleware(['permission:patient.doctor.batch.create']);
            Route::get('/{id}/edit', DoctorBatchForm::class)->name('doctor_batch_edit')->middleware(['permission:patient.doctor.batch.view']);
            Route::get('/{id}/print', DoctorBatchPrint::class)->name('doctor_batch_print')->middleware(['permission:patient.doctor.batch.print']);
            Route::get('/{id}/summary', DoctorBatchSummaryPrint::class)->name('doctor_batch_sum_print')->middleware(['permission:patient.doctor.batch.print']);
        });
        Route::prefix('/payment-period')->group(function () {
            Route::get('/', PaymentPeriodList::class)->name('payment_period')->middleware(['permission:patient.payment-period.view']);
            Route::get('/{id}/details', PaymentPeriodForm::class)->name('payment_period_details')->middleware(['permission:patient.payment-period.view']);
        });

        Route::prefix('/phic-paid')->group(function () {
            Route::get('/', QuickPaid::class)->name('phic_paid')->middleware(['permission:patient.payment-period.view']);
        });
        Route::prefix('/phic-payment-2026')->group(function () {
            Route::get('/', QuickPhilhealthPaid::class)->name('phic_payment2026')->middleware(['permission:patient.payment-period.view']);
        });

    });

    Route::prefix('/customers')->name('customers')->group(function () {
        Route::prefix('/sales-order')->group(function () {
            Route::get('/', SalesOrderList::class)->name('sales_order')->middleware(['permission:customer.sales-order.view']);
            Route::get('/create', SalesOrderForm::class)->name('sales_order_create')->middleware(['permission:customer.sales-order.create']);
            Route::get('/{id}/edit', SalesOrderForm::class)->name('sales_order_edit')->middleware(['permission:customer.sales-order.view']);
        });

        Route::prefix('/invoice')->group(function () {
            Route::get('/', InvoiceList::class)->name('invoice')->middleware(['permission:customer.invoice.view']);
            Route::get('/create', InvoiceForm::class)->name('invoice_create')->middleware(['permission:customer.invoice.create']);
            Route::get('/{id}/edit', InvoiceForm::class)->name('invoice_edit')->middleware(['permission:customer.invoice.view']);
            Route::get('/{id}/print', PrintInvoice::class)->name('invoice_print')->middleware(['permission:customer.invoice.print']);
        });

        Route::prefix('/sales-receipt')->group(function () {
            Route::get('/', SalesReceiptList::class)->name('sales_receipt')->middleware(['permission:customer.invoice.view']);
            Route::get('/create', SalesReceiptForm::class)->name('sales_receipt_create')->middleware(['permission:customer.invoice.create']);
            Route::get('/{id}/edit', SalesReceiptForm::class)->name('sales_receipt_edit')->middleware(['permission:customer.invoice.view']);
        });

        Route::prefix('/payment')->group(function () {
            Route::get('/', PaymentList::class)->name('payment')->middleware(['permission:customer.received-payment.view']);
            Route::get('/create', PaymentForm::class)->name('payment_create')->middleware(['permission:customer.received-payment.create']);
            Route::get('/{id}/edit', PaymentForm::class)->name('payment_edit')->middleware(['permission:customer.received-payment.view']);
        });

        Route::prefix('/statement')->group(function () {
            Route::get('/', Statement::class)->name('statement')->middleware(['permission:customer.statement']);
            Route::get('/view/{id}', StatementView::class)->name('statement_view')->middleware(['permission:customer.statement']);
            Route::get('/print/{id}/{datefrom}/{dateto?}', StatementPrint::class)->name('statement_print')->middleware(['permission:customer.statement']);
        });
        Route::prefix('/credit-memo')->group(function () {
            Route::get('/', CreditMemoList::class)->name('credit_memo')->middleware(['permission:customer.credit-memo.view']);
            Route::get('/create', CreditMemoForm::class)->name('credit_memo_create')->middleware(['permission:customer.credit-memo.create']);
            Route::get('/{id}/edit', CreditMemoForm::class)->name('credit_memo_edit')->middleware(['permission:customer.credit-memo.view']);
        });
        Route::prefix('/tax-credit')->group(function () {
            Route::get('/', TaxCreditList::class)->name('tax_credit')->middleware(['permission:customer.tax-credit.view']);
            Route::get('/create', TaxCreditForm::class)->name('tax_credit_create')->middleware(['permission:customer.tax-credit.create']);
            Route::get('/{id}/edit', TaxCreditForm::class)->name('tax_credit_edit')->middleware(['permission:customer.tax-credit.view']);
        });
    });

    Route::prefix('/vendors')->name('vendors')->group(function () {
        Route::prefix('/purchase-order')->group(function () {
            Route::get('/', PurchaseOrderList::class)->name('purchase_order')->middleware(['permission:vendor.purchase-order.view']);
            Route::get('/create', PurchaseOrderForm::class)->name('purchase_order_create')->middleware(['permission:vendor.purchase-order.create']);
            Route::get('/{id}/edit', PurchaseOrderForm::class)->name('purchase_order_edit')->middleware(['permission:vendor.purchase-order.view']);
            Route::get('/{id}/print', PurchaseOrderPrint::class)->name('purchase_order_print')->middleware(['permission:vendor.purchase-order.view']);
        });

        Route::prefix('/bills')->group(function () {
            Route::get('/', BillingList::class)->name('bills')->middleware(['permission:vendor.bill.view']);
            Route::get('/create', BillingForm::class)->name('bills_create')->middleware(['permission:vendor.bill.create']);
            Route::get('/{id}/edit', BillingForm::class)->name('bills_edit')->middleware(['permission:vendor.bill.view']);
            Route::get('/{id}/print', BillingPrint::class)->name('bills_print');
        });

        Route::prefix('/bill-payments')->group(function () {
            Route::get('/', BillPaymentList::class)->name('bill_payment')->middleware(['permission:vendor.bill-payment.view']);
            Route::get('/create', BillPaymentForm::class)->name('bill_payment_create')->middleware(['permission:vendor.bill-payment.create']);
            Route::get('/{id}/edit', BillPaymentForm::class)->name('bill_payment_edit')->middleware(['permission:vendor.bill-payment.view']);
            Route::get('/{id}/print', BillPaymentPrint::class)->name('bill_payment_print')->middleware(['permission:vendor.bill-payment.print']);
            Route::get('/{id}/doctor-fee-print', DoctorFeePrint::class)->name('bill_payment_doctor')->middleware(['permission:vendor.bill-payment.print']);
            Route::get('/{id}/doctor-fee-print-tax', DoctorFeePrint2::class)->name('bill_payment_doctor_tax')->middleware(['permission:vendor.bill-payment.print']);
        });

        Route::prefix('/bill-credits')->group(function () {
            Route::get('/', BillCreditList::class)->name('bill_credit')->middleware(['permission:vendor.bill-credit.view']);
            Route::get('/create', BillCreditForm::class)->name('bill_credit_create')->middleware(['permission:vendor.bill-credit.create']);
            Route::get('/{id}/edit', BillCreditForm::class)->name('bill_credit_edit')->middleware(['permission:vendor.bill-credit.view']);
        });

        Route::prefix('/withholding-tax')->group(function () {
            Route::get('/', WithHoldingTaxList::class)->name('withholding_tax')->middleware(['permission:vendor.withholding-tax.view']);
            Route::get('/create', WithHoldingTaxForm::class)->name('withholding_tax_create')->middleware(['permission:vendor.withholding-tax.create']);
            Route::get('/{id}/edit', WithHoldingTaxForm::class)->name('withholding_tax_edit')->middleware(['permission:vendor.withholding-tax.view']);
        });
    });

    Route::prefix('/company')->name('company')->group(function () {

        Route::prefix('/build-assembly')->group(function () {
            Route::get('/', BuildAssemblyList::class)->name('build_assembly')->middleware(['permission:company.build-assembly.view']);
            Route::get('/create', BuildAssemblyForm::class)->name('build_assembly_create')->middleware(['permission:company.build-assembly.create']);
            Route::get('/{id}/edit', BuildAssemblyForm::class)->name('build_assembly_edit')->middleware(['permission:company.build-assembly.view']);
        });

        Route::prefix('/general-journal')->group(function () {
            Route::get('/', GeneralJournalList::class)->name('general_journal')->middleware(['permission:company.general-journal.view']);
            Route::get('/create', GeneralJournalForm::class)->name('general_journal_create')->middleware(['permission:company.general-journal.create']);
            Route::get('/{id}/edit', GeneralJournalForm::class)->name('general_journal_edit')->middleware(['permission:company.general-journal.view']);
            Route::get('/{id}/print', GeneralJournalPrint::class)->name('general_journal_print')->middleware(['permission:company.general-journal.print']);
        });

        Route::prefix('/inventory-adjustment')->group(function () {
            Route::get('/', InventoryAdjustmentList::class)->name('inventory_adjustment')->middleware(['permission:company.inventory-adjustment.view']);
            Route::get('/create', InventoryAdjustmentForm::class)->name('inventory_adjustment_create')->middleware(['permission:company.inventory-adjustment.create']);
            Route::get('/{id}/edit', InventoryAdjustmentForm::class)->name('inventory_adjustment_edit')->middleware(['permission:company.inventory-adjustment.view']);
        });

        Route::prefix('/stock-transfer')->group(function () {
            Route::get('/', StockTransferList::class)->name('stock_transfer')->middleware(['permission:company.stock-transfer.view']);
            Route::get('/create', StockTransferForm::class)->name('stock_transfer_create')->middleware(['permission:company.stock-transfer.create']);
            Route::get('/{id}/edit', StockTransferForm::class)->name('stock_transfer_edit')->middleware(['permission:company.stock-transfer.view']);

        });

        Route::get('/stock-received', StockReceived::class)->name('stock_received')->middleware(['permission:company.stock-received']);
        // temporary
        Route::prefix('/pull-out')->group(function () {
            Route::get('/', PullOutList::class)->name('pull_out')->middleware(['permission:company.pull-out.view']);
            Route::get('/create', PullOutForm::class)->name('pull_out_create')->middleware(['permission:company.pull-out.create']);
            Route::get('/{id}/edit', PullOutForm::class)->name('pull_out_edit')->middleware(['permission:company.pull-out.view']);
            Route::get('/{id}/print', PullOutPrint::class)->name('pull_out_print')->middleware(['permission:company.pull-out.print']);
        });

        Route::prefix('/depreciation')->group(function () {
            Route::get('/', DepreciationList::class)->name('depreciation');
            Route::get('/create', DepreciationForm::class)->name('depreciation_create');
            Route::get('/{id}/edit', DepreciationForm::class)->name('depreciation_edit');
        });

        Route::prefix('/cost-adjustment')->group(function () {
            Route::get('/', CostAdjustmentList::class)->name('cost_adjustment')->middleware(['permission:company.cost-adjustment.view']);
            Route::get('/create', CostAdjustmentForm::class)->name('cost_adjustment_create')->middleware(['permission:company.cost-adjustment.create']);
            Route::get('/{id}/edit', CostAdjustmentForm::class)->name('cost_adjustment_edit')->middleware(['permission:company.cost-adjustment.view']);
        });
    });

    Route::prefix('/banking')->name('banking')->group(function () {
        Route::prefix('/deposit')->group(function () {
            Route::get('/', DepositList::class)->name('deposit')->middleware(['permission:banking.deposit.view']);
            Route::get('/create', DepositForm::class)->name('deposit_create')->middleware(['permission:banking.deposit.create']);
            Route::get('/{id}/edit', DepositForm::class)->name('deposit_edit')->middleware(['permission:banking.deposit.view']);
        });

        Route::prefix('/fund-transfer')->group(function () {
            Route::get('/', FundTransferList::class)->name('fund_transfer')->middleware(['permission:banking.fund-transfer.view']);
            Route::get('/create', FundTransferForm::class)->name('fund_transfer_credit')->middleware(['permission:banking.fund-transfer.create']);
            Route::get('/{id}/edit', FundTransferForm::class)->name('fund_transfer_edit')->middleware(['permission:banking.fund-transfer.view']);
            Route::get('/{id}/print', FundTransferPrint::class)->name('fund_transfer_print')->middleware(['permission:banking.fund-transfer.print']);
        });

        Route::prefix('/bank-transfer')->group(function () {
            Route::get('/', BankTransferList::class)->name('bank_transfer')->middleware(['permission:banking.bank-transfer.view']);
            Route::get('/create', BankTransferForm::class)->name('bank_transfer_credit')->middleware(['permission:banking.bank-transfer.create']);
            Route::get('/{id}/edit', BankTransferForm::class)->name('bank_transfer_edit')->middleware(['permission:banking.bank-transfer.view']);
        });

        Route::prefix('/spend-money')->group(function () {
            Route::get('/', SpendMoneyList::class)->name('spend_money')->middleware(['permission:banking.spend-money.view']);
            Route::get('/create', SpendMoneyForm::class)->name('spend_money_create')->middleware(['permission:banking.spend-money.create']);
            Route::get('/{id}/edit', SpendMoneyForm::class)->name('spend_money_edit')->middleware(['permission:banking.spend-money.view']);
        });

        Route::prefix('/receive-money')->group(function () {
            Route::get('/', ReceiveMoneyList::class)->name('receive_money')->middleware(['permission:banking.receive-money.view']);
            Route::get('/create', ReceiveMoneyForm::class)->name('receive_money_create')->middleware(['permission:banking.receive-money.create']);
            Route::get('/{id}/edit', ReceiveMoneyForm::class)->name('receive_money_edit')->middleware(['permission:banking.receive-money.view']);
        });

        Route::prefix('/make-cheque')->group(function () {
            Route::get('/', WriteCheckList::class)->name('make_cheque')->middleware(['permission:banking.make-cheque.view']);
            Route::get('/create', WriteCheckForm::class)->name('make_cheque_create')->middleware(['permission:banking.make-cheque.create']);
            Route::get('/{id}/edit', WriteCheckForm::class)->name('make_cheque_edit')->middleware(['permission:banking.make-cheque.view']);
            Route::get('/{id}/print', WriteCheckFormPrint::class)->name('make_cheque_print')->middleware(['permission:banking.make-cheque.print']);
        });

        Route::prefix('/bank-recon')->group(function () {
            Route::get('/', BankReconList::class)->name('bank_recon')->middleware(['permission:banking.bank-recon.view']);
            Route::get('/create', BankReconForm::class)->name('bank_recon_create')->middleware(['permission:banking.bank-recon.create']);
            Route::get('/{id}/edit', BankReconForm::class)->name('bank_recon_edit')->middleware(['permission:banking.bank-recon.view']);
            Route::get('/{id}/print', BankReconFormPrint::class)->name('bank_recon_print')->middleware(['permission:banking.bank-recon.print']);
        });

        Route::prefix('/bank-statement')->group(function () {
            Route::get('/', BankStatementList::class)->name('bank_statement')->middleware(['permission:banking.bank-statement.view']);
            Route::get('/create', BankStatementForm::class)->name('bank_statement_create')->middleware(['permission:banking.bank-statement.create']);
            Route::get('/{id}/edit', BankStatementForm::class)->name('bank_statement_edit')->middleware(['permission:banking.bank-statement.view']);
            Route::get('/{id}/print', BankStatementPrint::class)->name('bank_statement_print')->middleware(['permission:banking.bank-statement.view']);
        });

    });

    Route::prefix('/maintenance')->name('maintenance')->group(function () {
        Route::prefix('/contact')->name('contact')->group(function () {
            Route::prefix('/customer')->group(function () {
                Route::get('/', CustomerList::class)->name('customer')->middleware(['permission:contact.customer.view']);
                Route::get('/create', CustomerForm::class)->name('customer_create')->middleware(['permission:contact.customer.create']);
                Route::get('/{id}/edit', CustomerForm::class)->name('customer_edit')->middleware(['permission:contact.customer.view']);
            });
            Route::prefix('/vendor')->group(function () {
                Route::get('/', VendorList::class)->name('vendor')->middleware(['permission:contact.vendor.view']);
                Route::get('/create', VendorForm::class)->name('vendor_create')->middleware(['permission:contact.vendor.create']);
                Route::get('/{id}/edit', VendorForm::class)->name('vendor_edit')->middleware(['permission:contact.vendor.view']);
            });

            Route::prefix('/employees')->group(function () {
                Route::get('/', EmployeeList::class)->name('employees')->middleware(['permission:contact.employee.view']);
                Route::get('/create', EmployeeForm::class)->name('employees_create')->middleware(['permission:contact.employee.create']);
                Route::get('/{id}/edit', EmployeeForm::class)->name('employees_edit')->middleware(['permission:contact.employee.view']);
            });

            Route::prefix('/patients')->group(function () {
                Route::get('/', PatientList::class)->name('patients')->middleware(['permission:contact.patient.view']);
                Route::get('/create', PatientForm::class)->name('patients_create')->middleware(['permission:contact.patient.create']);
                Route::get('/{id}/edit', PatientForm::class)->name('patients_edit')->middleware(['permission:contact.patient.view']);
                Route::get('/{id}/{locationid}/edit', PatientForm::class)->name('patients_view')->middleware(['permission:contact.patient.view']);
                Route::get('/{id}/{year}/{locationid}', PrintAvailment::class)->name('print_availment');
                Route::get('/{id}/medical-certificate', MedcertPrint::class)->name('print_medical_cert');
            });

            Route::prefix('/doctors')->group(function () {
                Route::get('/', DoctorList::class)->name('doctors')->middleware(['permission:contact.doctor.view']);
                Route::get('/create', DoctorForm::class)->name('doctors_create')->middleware(['permission:contact.doctor.create']);
                Route::get('/{id}/edit', DoctorForm::class)->name('doctors_edit')->middleware(['permission:contact.doctor.view']);
            });
        });

        Route::prefix('/financial')->name('financial')->group(function () {

            Route::prefix('/chart-of-account')->group(function () {
                Route::get('/', ChartOfAccountList::class)->name('coa')->middleware(['permission:chart-of-account.view']);
                Route::get('/create', ChartOfAccountForm::class)->name('coa_create')->middleware(['permission:chart-of-account.create']);
                Route::get('/{id}/edit', ChartOfAccountForm::class)->name('coa_edit')->middleware(['permission:chart-of-account.view']);
                Route::get('/{id}/{locationid}/balance', ChartOfAccountEndingBalance::class)->name('coa_balance')->middleware(['permission:chart-of-account.view']);
            });

            Route::prefix('/payment-method')->group(function () {
                Route::get('/', PaymentMethodList::class)->name('payment_method')->middleware(['permission:payment-method.view']);
                Route::get('/create', PaymentMethodForm::class)->name('payment_method_create')->middleware(['permission:payment-method.create']);
                Route::get('/{id}/edit', PaymentMethodForm::class)->name('payment_method_edit')->middleware(['permission:payment-method.view']);
            });

            Route::prefix('/payment-term')->group(function () {
                Route::get('/', PaymentTermList::class)->name('payment_term')->middleware(['permission:payment-term.view']);
                Route::get('/create', PaymentTermForm::class)->name('payment_term_create')->middleware(['permission:payment-term.create']);
                Route::get('/{id}/edit', PaymentTermForm::class)->name('payment_term_edit')->middleware(['permission:payment-term.view']);
            });

            Route::prefix('/tax-list')->group(function () {
                Route::get('/', TaxList::class)->name('tax_list')->middleware(['permission:tax-list.view']);
                Route::get('/create', TaxForm::class)->name('tax_list_create')->middleware(['permission:tax-list.create']);
                Route::get('/{id}/edit', TaxForm::class)->name('tax_list_edit')->middleware(['permission:tax-list.view']);
            });
        });

        Route::prefix('/inventory')->name('inventory')->group(function () {
            Route::prefix('/items')->group(function () {
                Route::get('/', ItemsList::class)->name('item')->middleware(['permission:items.view']);
                Route::get('/create', ItemsForm::class)->name('item_create')->middleware(['permission:items.create']);
                Route::get('/{id}/edit', ItemsForm::class)->name('item_edit')->middleware(['permission:items.edit']);
            });
            Route::prefix('/item-class')->group(function () {
                Route::get('/', ItemClassList::class)->name('item_class')->middleware(['permission:item-class.view']);
                Route::get('/create', ItemClassForm::class)->name('item_class_create')->middleware(['permission:item-class.create']);
                Route::get('/{id}/edit', ItemClassForm::class)->name('item_class_edit')->middleware(['permission:item-class.edit']);
            });
            Route::prefix('/item-sub-class')->group(function () {
                Route::get('/', ItemSubClassList::class)->name('item_sub_class')->middleware(['permission:item-sub-class.view']);
                Route::get('/create', ItemSubClassForm::class)->name('item_sub_class_create')->middleware(['permission:item-sub-class.create']);
                Route::get('/{id}/edit', ItemSubClassForm::class)->name('item_sub_class_edit')->middleware(['permission:item-sub-class.edit']);
            });
            Route::prefix('/item-group')->group(function () {
                Route::get('/', ItemGroupList::class)->name('item_group')->middleware(['permission:item-group.view']);
                Route::get('/create', ItemGroupForm::class)->name('item_group_create')->middleware(['permission:item-group.create']);
                Route::get('/{id}/edit', ItemGroupForm::class)->name('item_group_edit')->middleware(['permission:item-group.edit']);
            });
            Route::prefix('/manufacturers')->group(function () {
                Route::get('/', ManufacturerList::class)->name('manufacturers')->middleware(['permission:manufacturer.view']);
                Route::get('/create', ManufacturerForm::class)->name('manufacturers_create')->middleware(['permission:manufacturer.create']);
                Route::get('/{id}/edit', ManufacturerForm::class)->name('manufacturers_edit')->middleware(['permission:manufacturer.edit']);
            });
            Route::prefix('/ship-via')->group(function () {
                Route::get('/', ShipViaList::class)->name('ship_via')->middleware(['permission:ship-via.view']);
                Route::get('/create', ShipViaForm::class)->name('ship_via_create')->middleware(['permission:ship-via.create']);
                Route::get('/{id}/edit', ShipViaForm::class)->name('ship_via_edit')->middleware(['permission:ship-via.edit']);
            });
            Route::prefix('/unit-of-measure')->group(function () {
                Route::get('/', UnitOfMeasureList::class)->name('unit_of_measure')->middleware(['permission:unit-of-measure.view']);
                Route::get('/create', UnitOfMeasureForm::class)->name('unit_of_measure_create')->middleware(['permission:unit-of-measure.create']);
                Route::get('/{id}/edit', UnitOfMeasureForm::class)->name('unit_of_measure_edit')->middleware(['permission:unit-of-measure.edit']);
            });

            Route::prefix('/inventory-adjustment-type')->group(function () {
                Route::get('/', InventoryAdjustmentTypeList::class)->name('inventory_adjustment_type')->middleware(['permission:inventory-adjustment-type.view']);
                Route::get('/create', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_create')->middleware(['permission:inventory-adjustment-type.create']);
                Route::get('/{id}/edit', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_edit')->middleware(['permission:inventory-adjustment-type.edit']);
            });

            Route::prefix('/stock-bin')->group(function () {
                Route::get('/', StockBinList::class)->name('stock_bin')->middleware(['permission:stock-bin.view']);
                Route::get('/create', StockBinForm::class)->name('stock_bin_create')->middleware(['permission:stock-bin.create']);
                Route::get('/{id}/edit', StockBinForm::class)->name('stock_bin_edit')->middleware(['permission:stock-bin.edit']);
            });

            Route::prefix('/price-level')->group(function () {
                Route::get('/', PriceLevelList::class)->name('price_level')->middleware(['permission:price-level.view']);
                Route::get('/create', PriceLevelForm::class)->name('price_level_create')->middleware(['permission:price-level.create']);
                Route::get('/{id}/edit', PriceLevelForm::class)->name('price_level_edit')->middleware(['permission:price-level.edit']);
            });

            Route::prefix('/price-location')->group(function () {
                Route::get('/', PriceLocationList::class)->name('price_location')->middleware(['permission:price-location']);
            });

            Route::prefix('/fixed-asset-items')->group(function () {
                Route::get('/', FixedAssetItemList::class)->name('fixed_asset_item');
                Route::get('/create', FixedAssetItemForm::class)->name('fixed_asset_item_create');
                Route::get('/{id}/edit', FixedAssetItemForm::class)->name('fixed_asset_item_edit');
            });
        });

        Route::prefix('/others')->name('others')->group(function () {
            Route::prefix('/shift')->group(function () {
                Route::get('/', ShiftList::class)->name('shift')->middleware(['permission:others.shift.view']);
                Route::get('/create', ShiftForm::class)->name('shift_create')->middleware(['permission:others.shift.create']);
                Route::get('/{id}/edit', ShiftForm::class)->name('shift_edit')->middleware(['permission:others.shift.view']);
            });

            Route::prefix('/hemodialysis-machine')->group(function () {
                Route::get('/', HemoMachineList::class)->name('hemo_machine')->middleware(['permission:others.hemodialysis-machine.view']);
                Route::get('/create', HemoMachineForm::class)->name('hemo_machine_create')->middleware(['permission:others.hemodialysis-machine.create']);
                Route::get('/{id}/edit', HemoMachineForm::class)->name('hemo_machine_edit')->middleware(['permission:others.hemodialysis-machine.view']);
            });

            Route::prefix('/requirement')->group(function () {
                Route::get('/', RequirementList::class)->name('requirement')->middleware(['permission:others.requirement.view']);
                Route::get('/create', RequirementForm::class)->name('requirement_create')->middleware(['permission:others.requirement.create']);
                Route::get('/{id}/edit', RequirementForm::class)->name('requirement_edit')->middleware(['permission:others.requirement.view']);
            });

            Route::prefix('/item-treatment')->group(function () {
                Route::get('/', ItemTreatmentList::class)->name('item_treatment')->middleware(['permission:others.item-treatment.view']);
                Route::get('/create', ItemTreatmentForm::class)->name('item_treatment_create')->middleware(['permission:others.item-treatment.view']);
                Route::get('/{id}/edit', ItemTreatmentForm::class)->name('item_treatment_edit')->middleware(['permission:others.item-treatment.view']);
            });

            Route::prefix('/item-active-list')->group(function () {
                Route::get('/', ItemActiveList::class)->name('item-active-list')->middleware(['permission:others.item-active-list.view']);
                Route::get('/details/{id}/{locationid}', ItemInventoryDetails::class)->name('item-active-list_details')->middleware(['permission:others.item-active-list.view']);
            });
        });

        Route::prefix('/settings')->name('settings')->group(function () {
            Route::prefix('/user')->middleware(['permission:users'])->group(function () {
                Route::get('/', UserList::class)->name('users');
                Route::get('/create', UserForm::class)->name('users_create');
                Route::get('/{id}/edit', UserForm::class)->name('users_edit');
                Route::get('/{id}/role', UserRoles::class)->name('users_role');
            });

            Route::prefix('/rolespermission')->middleware(['permission:roles-and-permission'])->group(function () {
                Route::get('/', RolePermissionList::class)->name('roles');
                Route::get('/{id}/permission', RolePermissionConfig::class)->name('roles_permission');
            });

            Route::prefix('/location')->group(function () {
                Route::get('/', LocationList::class)->name('location')->middleware(['permission:location.view']);
                Route::get('/{id}/doctor', LocationDoctors::class)->name('location_doctor')->middleware(['permission:location.view']);
                Route::get('/{id}/soa-item', SoaItem::class)->name('soa_item')->middleware(['permission:location.edit']);
                Route::get('/{id}/doctor-notes', DoctorNotes::class)->name('doctor_notes')->middleware(['permission:location.view']);
                Route::get('/create', LocationForm::class)->name('location_create')->middleware(['permission:location.create']);
                Route::get('/{id}/edit', LocationForm::class)->name('location_edit')->middleware(['permission:location.edit']);

                Route::get('/{id}/custom-soa', PhilCustomSoaList::class)->name('location_custom_soa')->middleware(['permission:location.view']);
                Route::get('/{id}/custom-soa/create', PhilCustomSoaForm::class)->name('location_custom_soa_create')->middleware(['permission:location.view']);
                Route::get('/{id}/custom-soa/{custom}/edit', PhilCustomSoaForm::class)->name('location_custom_soa_edit')->middleware(['permission:location.view']);
            });
            Route::prefix('/location-group')->group(function () {
                Route::get('/', LocationGroupList::class)->name('location_group')->middleware(['permission:location-group.view']);
                Route::get('/create', LocationGroupForm::class)->name('location_group_create')->middleware(['permission:location-group.create']);
                Route::get('/{id}/edit', LocationGroupForm::class)->name('location_group_edit')->middleware(['permission:location-group.edit']);
            });

            Route::prefix('/option')->group(function () {
                Route::get('/', OptionSettings::class)->name('option')->middleware(['permission:option']);
            });

            Route::prefix('/import')->group(function () {
                Route::get('/', XeroImportForm::class)->name('import')->middleware(['permission:option']);
            });
        });
    });

    Route::prefix('/reports')->name('reports')->group(function () {
        Route::prefix('/patients')->group(function () {

            Route::prefix('/sales')->group(function () {
                Route::get('/', PatientSalesReport2::class)->name('patient_sales_report')->middleware(['permission:report.patient.sales']);
                Route::get('/{date_from}/{date_to}/{location_id}/print', PatientSalesReportPrint::class)->name('patient_sales_report_print')->middleware(['permission:report.patient.sales']);
            });
            Route::prefix('/inventory')->group(function () {
                Route::get('/', PatientInventoryReport::class)->name('patient_inventory_report')->middleware(['permission:report.patient.sales']);
                Route::get('/usage', UsageReport::class)->name('inventory_usage_report');
            });
            Route::prefix('/treatment')->group(function () {
                Route::get('/', PatientTreatmentReport::class)->name('patient_treatment_report')->middleware(['permission:report.patient.treatment']);
            });
            Route::prefix('/balance')->group(function () {
                Route::get('/', PatientBalanceReport::class)->name('patient_balance_report')->middleware(['permission:report.patient.balance']);
            });
            Route::prefix('/philhealth-monitoring')->group(function () {
                Route::get('/', PhilHealthMonitoringReport::class)->name('philhealth_monitoring')->middleware(['permission:report.philhealth.monitoring']);
            });
            Route::prefix('/philhealth-availment-list')->group(function () {
                Route::get('/', PhilHealthAvailmentList::class)->name('philhealth_availment_list')->middleware(['permission:report.philhealth.availment']);
                Route::get('/{id}/{locationid}/{year}', PhilHealthAvailmentListPrint::class)->name('philhealth_availment_list_print')->middleware(['permission:report.philhealth.availment']);
            });
            Route::prefix('/guarantee-letter')->group(function () {
                Route::get('/', GuaranteeLetterReport::class)->name('guarantee_letter')->middleware(['permission:report.guarantee.letter']);
            });
            Route::prefix('/philhealth-annex')->group(function () {
                Route::get('/one', PhilhealthAnnex::class)->name('philhealth_annex_report')->middleware(['permission:report.philhealth.annex']);
                Route::get('/one/Print/{locationid}/{year}/{month}', PhilhealthAnnexOnePrint::class)->name('philhealth_annex_one_print')->middleware(['permission:report.philhealth.annex']);
                Route::get('/two', PhilhealthAnnexTwo::class)->name('philhealth_annex_two_report')->middleware(['permission:report.philhealth.annex']);
                Route::get('/two/Print/{locationid}/{year}/{show}', PhilhealthAnnexTwoPrint::class)->name('philhealth_annex_two_print')->middleware(['permission:report.philhealth.annex']);
            });

        });
        Route::prefix('/accounting')->name('accounting')->group(function () {
            Route::prefix('/general-ledger')->middleware(['permission:report.accounting.general-ledger'])->group(function () {
                Route::get('/', GeneralLedgerReport::class)->name('general_ledeger_report');
                Route::get('/view/{from}/{to}/{location}/{account?}/{accounttype?}', GeneralLedgerGenerate::class)->name('general_ledeger_view');
            });
            Route::prefix('/trial-balance')->middleware(['permission:report.accounting.trial-balance'])->group(function () {
                Route::get('/', TrialBalanceReport::class)->name('trial_balance_report');
                Route::get('/view/{from}/{to?}/{location}/{account?}/{accounttype?}', TrialBalanceGenerate::class)->name('trial_balance_view');
            });
            Route::prefix('/account-transaction')->middleware(['permission:report.accounting.transaction-details'])->group(function () {
                Route::get('/', TransactionDetailsReport::class)->name('transaction_details_report');
                Route::get('/{from}/{to?}/{location}/{account?}/{accounttype?}', TransactionDetailsGenerate::class)->name('transaction_details_view');
            });

           
            //     Route::get('/', PettyCashDetailsReport::class)->name('pettycash_details_report');
            //     Route::get('/{from}/{to?}/{location}/{account?}/{accounttype?}', PettyCashDetailsGenerate::class)->name('pettycash_details_view');
            // });

            Route::prefix('/transaction-journal')->middleware(['permission:report.accounting.transaction-details'])->group(function () {
                Route::get('/', TransactionJournalReport::class)->name('transaction_journal_report');
                Route::get('/view/{from}/{to?}/{location}/{account?}/{accounttype?}', TransactionJournalGenerate::class)->name('transaction_journal_view');
                Route::get('/error/{from}/{to?}/{location}/{account?}/{accounttype?}', TransactionJournalError::class)->name('transaction_journal_error');
                Route::get('/miss/{from}/{to?}/{location}/{account?}/{accounttype?}', TransactionDetailsMissing::class)->name('transaction_journal_miss');
                Route::get('/unpost/{from}/{to?}/{location}/{account?}/{accounttype?}', TransactionJournalUnpost::class)->name('transaction_journal_unpost');
            });
        });

        Route::prefix('/financial')->name('financial')->group(function () {
            Route::prefix('/income-statement')->middleware(['permission:report.financial.income-statement'])->group(function () {
                Route::get('/', IncomeStatementReport::class)->name('income_statement_report');
                Route::get('/details/{id}/{year}/{month}/{locationid}', IncomeStatementAccountDetails::class)->name('income_statement_report_account_viewer');
                Route::get('/summary/{id}/{datefrom}/{dateto}/{locationid}', IncomeStatementAccountSummary::class)->name('income_statement_report_account_viewer_summary');

            });
            Route::prefix('/balance-sheet')->middleware(['permission:report.financial.balance-sheet'])->group(function () {
                Route::get('/', BalanceSheetReport::class)->name('balance_sheet_report');
            });
            Route::prefix('/cash-flow')->middleware(['permission:report.financial.cash-flow'])->group(function () {
                Route::get('/', CashFlowReport::class)->name('cash_flow_report');
            });

            Route::prefix('/equity')->middleware(['permission:report.financial.equity'])->group(function () {
                Route::get('/', EquityReport::class)->name('equity_report');
            });

             Route::prefix('/pettycash')->middleware(['permission:report.pettycash.petty-cash'])->group(function () {
                Route::get('/', PettyCashReport::class)->name('petty_cash_report');
                

            });

        });

        Route::prefix('/customer')->group(function () {
            Route::get('/sales', CustomerSalesReport::class)->name('customer_sales_report')->middleware(['permission:report.customer.sales']);
        });
        Route::prefix('/receivables')->group(function () {
            Route::get('/ar-aging', AccountReceivableAging::class)->name('ar_aging')->middleware(['permission:report.receivables.ar-aging']);
            Route::get('/customer-balance', CustomerBalance::class)->name('customer_balance')->middleware(['permission:report.receivables.customer-balance']);
        });
        Route::prefix('/payables')->group(function () {
            Route::get('/ap-aging', AccountPayableAging::class)->name('ap_aging')->middleware(['permission:report.payables.ap-aging']);
            Route::get('/vendor-balance', VendorBalance::class)->name('vendor_balance')->middleware(['permission:report.payables.vendor-balance']);
        });
        // Route::prefix('/purchases')->group(function () { });
        // Route::prefix('/expenses')->group(function () { });

        Route::prefix('/inventory')->group(function () {
            Route::get('/validation-summary', ValidationSummaryReport::class)->name('validation_summry')->middleware(['permission:report.inventory.validation-summary']);
        });

        // Route::prefix('/documents')->group(function () { });
    });
});

require __DIR__ . '/auth.php';
