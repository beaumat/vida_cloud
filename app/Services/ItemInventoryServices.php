<?php
namespace App\Services;

use App\Models\ItemInventory;
use Illuminate\Support\Facades\DB;

class ItemInventoryServices
{

    private string $TX_ID = '
     CASE
		WHEN document_type_map.`ID` = 1 THEN  (SELECT bill.`ID` FROM bill_items  JOIN bill ON bill.`ID` =  bill_items.`BILL_ID` WHERE bill_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 3 THEN  (SELECT bill_credit.`ID` FROM bill_credit_items  JOIN bill_credit ON bill_credit.`ID` =  bill_credit_items.`BILL_CREDIT_ID`  WHERE bill_credit_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill_credit.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill_credit.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_credit_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 6 THEN  (SELECT inventory_adjustment.`ID` FROM inventory_adjustment_items  JOIN inventory_adjustment ON inventory_adjustment.`ID` =  inventory_adjustment_items.`INVENTORY_ADJUSTMENT_ID` WHERE inventory_adjustment_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND inventory_adjustment.`DATE` = item_inventory.`SOURCE_REF_DATE` AND inventory_adjustment.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND inventory_adjustment_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 7 THEN  (SELECT stock_transfer.`ID` FROM stock_transfer_items  JOIN stock_transfer ON stock_transfer.`ID` =  stock_transfer_items.`STOCK_TRANSFER_ID`  WHERE stock_transfer_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND stock_transfer.`DATE` = item_inventory.`SOURCE_REF_DATE` AND stock_transfer.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND stock_transfer_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 7 THEN  (SELECT stock_transfer.`ID` FROM stock_transfer_items  JOIN stock_transfer ON stock_transfer.`ID` =  stock_transfer_items.`STOCK_TRANSFER_ID`  WHERE stock_transfer_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND stock_transfer.`DATE` = item_inventory.`SOURCE_REF_DATE` AND stock_transfer.`TRANSFER_TO_ID` = item_inventory.`LOCATION_ID` AND stock_transfer_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 10 THEN  (SELECT invoice.`ID` FROM invoice_items  JOIN invoice ON invoice.`ID` =  invoice_items.`INVOICE_ID`  WHERE invoice_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND invoice.`DATE` = item_inventory.`SOURCE_REF_DATE` AND invoice.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND invoice_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 12 THEN  (SELECT credit_memo.`ID` FROM credit_memo_items  JOIN credit_memo ON credit_memo.`ID` =  credit_memo_items.`CREDIT_MEMO_ID` WHERE credit_memo_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND credit_memo.`DATE` = item_inventory.`SOURCE_REF_DATE` AND credit_memo.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND credit_memo_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 13 THEN  (SELECT sales_receipt.`ID` FROM sales_receipt_items  JOIN sales_receipt ON sales_receipt.`ID` =  sales_receipt_items.`SALES_RECEIPT_ID`  WHERE sales_receipt_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND sales_receipt.`DATE` = item_inventory.`SOURCE_REF_DATE` AND sales_receipt.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND sales_receipt_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 19 THEN  (SELECT build_assembly.`ID` FROM build_assembly_items  JOIN build_assembly ON build_assembly.`ID` =  build_assembly_items.`BUILD_ASSEMBLY_ID`  WHERE build_assembly_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND build_assembly.`DATE` = item_inventory.`SOURCE_REF_DATE` AND build_assembly.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND build_assembly_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 19 THEN  (SELECT build_assembly.`ID` FROM build_assembly  WHERE build_assembly.`ASSEMBLY_ITEM_ID` =  item_inventory.`SOURCE_REF_ID` AND build_assembly.`DATE` = item_inventory.`SOURCE_REF_DATE` AND build_assembly.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND build_assembly.`ASSEMBLY_ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 21 THEN  (SELECT `check`.`ID` FROM check_items  JOIN  `check` ON `check`.`ID` =  check_items.`CHECK_ID` WHERE check_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `check`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `check`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND check_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 27 THEN  (SELECT hemodialysis.`ID` FROM `hemodialysis_items`  JOIN hemodialysis ON hemodialysis.`ID` =  hemodialysis_items.`HEMO_ID` WHERE hemodialysis_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND hemodialysis.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  hemodialysis.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND hemodialysis_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
        WHEN document_type_map.`ID` = 29 THEN  (SELECT service_charges.`ID` FROM `service_charges_items`  JOIN service_charges ON service_charges.`ID` =  service_charges_items.`SERVICE_CHARGES_ID` WHERE service_charges_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND service_charges.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  service_charges.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND service_charges_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
		WHEN document_type_map.`ID` = 31 THEN  (SELECT pull_out.`ID` FROM pull_out_items  JOIN  pull_out ON pull_out.`ID` =  pull_out_items.`PULL_OUT_ID`  WHERE pull_out_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `pull_out`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `pull_out`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND pull_out_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
	END AS TX_ID';

    private string $TX_CODE = '
    CASE
		WHEN document_type_map.`ID` = 1 THEN  (SELECT bill.`CODE` FROM bill_items  JOIN bill ON bill.`ID` =  bill_items.`BILL_ID` WHERE bill_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 3 THEN  (SELECT bill_credit.`CODE` FROM bill_credit_items  JOIN bill_credit ON bill_credit.`ID` =  bill_credit_items.`BILL_CREDIT_ID`  WHERE bill_credit_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill_credit.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill_credit.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_credit_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 6 THEN  (SELECT inventory_adjustment.`CODE` FROM inventory_adjustment_items  JOIN inventory_adjustment ON inventory_adjustment.`ID` =  inventory_adjustment_items.`INVENTORY_ADJUSTMENT_ID` WHERE inventory_adjustment_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND inventory_adjustment.`DATE` = item_inventory.`SOURCE_REF_DATE` AND inventory_adjustment.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND inventory_adjustment_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 7 THEN  (SELECT stock_transfer.`CODE` FROM stock_transfer_items  JOIN stock_transfer ON stock_transfer.`ID` =  stock_transfer_items.`STOCK_TRANSFER_ID`  WHERE stock_transfer_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND stock_transfer.`DATE` = item_inventory.`SOURCE_REF_DATE` AND stock_transfer_items.`ITEM_ID` = item_inventory.`ITEM_ID`  AND ( stock_transfer.`LOCATION_ID` = item_inventory.`LOCATION_ID` or stock_transfer.`TRANSFER_TO_ID` = item_inventory.`LOCATION_ID`))
		WHEN document_type_map.`ID` = 10 THEN  (SELECT invoice.`CODE` FROM invoice_items  JOIN invoice ON invoice.`ID` =  invoice_items.`INVOICE_ID`  WHERE invoice_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND invoice.`DATE` = item_inventory.`SOURCE_REF_DATE` AND invoice.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND invoice_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 12 THEN  (SELECT credit_memo.`CODE` FROM credit_memo_items  JOIN credit_memo ON credit_memo.`ID` =  credit_memo_items.`CREDIT_MEMO_ID` WHERE credit_memo_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND credit_memo.`DATE` = item_inventory.`SOURCE_REF_DATE` AND credit_memo.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND credit_memo_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 13 THEN  (SELECT sales_receipt.`CODE` FROM sales_receipt_items  JOIN sales_receipt ON sales_receipt.`ID` =  sales_receipt_items.`SALES_RECEIPT_ID`  WHERE sales_receipt_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND sales_receipt.`DATE` = item_inventory.`SOURCE_REF_DATE` AND sales_receipt.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND sales_receipt_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 19 THEN  (SELECT build_assembly.`CODE` FROM build_assembly_items  JOIN build_assembly ON build_assembly.`ID` =  build_assembly_items.`BUILD_ASSEMBLY_ID`  WHERE (  build_assembly.`ID` =  item_inventory.`SOURCE_REF_ID` or build_assembly_items.`ID` =  item_inventory.`SOURCE_REF_ID`) AND build_assembly.`DATE` = item_inventory.`SOURCE_REF_DATE` AND build_assembly.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND (build_assembly_items.`ITEM_ID` = item_inventory.`ITEM_ID` or build_assembly.`ASSEMBLY_ITEM_ID` = item_inventory.`ITEM_ID`) )
		WHEN document_type_map.`ID` = 21 THEN  (SELECT `check`.`CODE` FROM check_items  JOIN  `check` ON `check`.`ID` =  check_items.`CHECK_ID` WHERE check_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `check`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `check`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND check_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 27 THEN  (SELECT hemodialysis.`CODE` FROM `hemodialysis_items`  JOIN hemodialysis ON hemodialysis.`ID` =  hemodialysis_items.`HEMO_ID` WHERE hemodialysis_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND hemodialysis.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  hemodialysis.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND hemodialysis_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
		WHEN document_type_map.`ID` = 29 THEN  (SELECT service_charges.`CODE` FROM `service_charges_items`  JOIN service_charges ON service_charges.`ID` =  service_charges_items.`SERVICE_CHARGES_ID` WHERE service_charges_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND service_charges.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  service_charges.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND service_charges_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
        WHEN document_type_map.`ID` = 31 THEN  (SELECT pull_out.`CODE` FROM pull_out_items  JOIN  pull_out ON pull_out.`ID` =  pull_out_items.`PULL_OUT_ID`  WHERE pull_out_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `pull_out`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `pull_out`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND pull_out_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
	END AS TX_CODE
                ';
    private string $TX_CONTACT_NAME = '
    CASE
		WHEN document_type_map.`ID` = 1 THEN  (SELECT contact.`PRINT_NAME_AS` FROM bill_items  JOIN bill ON bill.`ID` =  bill_items.`BILL_ID` JOIN contact ON contact.`ID` = bill.`VENDOR_ID` WHERE bill_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 3 THEN  (SELECT contact.`PRINT_NAME_AS` FROM bill_credit_items  JOIN bill_credit ON bill_credit.`ID` =  bill_credit_items.`BILL_CREDIT_ID` JOIN contact ON contact.`ID` = bill_credit.`VENDOR_ID` WHERE bill_credit_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill_credit.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill_credit.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_credit_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 6 THEN  (SELECT inventory_adjustment_type.`DESCRIPTION` FROM inventory_adjustment_items  JOIN inventory_adjustment ON inventory_adjustment.`ID` =  inventory_adjustment_items.`INVENTORY_ADJUSTMENT_ID` JOIN inventory_adjustment_type ON inventory_adjustment_type.`ID` =  inventory_adjustment.`ADJUSTMENT_TYPE_ID` WHERE inventory_adjustment_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND inventory_adjustment.`DATE` = item_inventory.`SOURCE_REF_DATE` AND inventory_adjustment.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND inventory_adjustment_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 7 THEN  (SELECT if(item_inventory.LOCATION_ID = stock_transfer.LOCATION_ID, (select CONCAT("Transfer To ",location.NAME) from location where location.ID = stock_transfer.TRANSFER_TO_ID),(select CONCAT("Transfer From " ,location.NAME) from location where location.ID = stock_transfer.LOCATION_ID) ) FROM stock_transfer_items JOIN stock_transfer ON stock_transfer.`ID` =  stock_transfer_items.`STOCK_TRANSFER_ID`   WHERE  stock_transfer_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND stock_transfer.`DATE` = item_inventory.`SOURCE_REF_DATE`  AND stock_transfer_items.`ITEM_ID` = item_inventory.`ITEM_ID`   )
		WHEN document_type_map.`ID` = 10 THEN  (SELECT contact.`PRINT_NAME_AS` FROM invoice_items  JOIN invoice ON invoice.`ID` =  invoice_items.`INVOICE_ID` JOIN contact ON contact.`ID` = invoice.`CUSTOMER_ID` WHERE invoice_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND invoice.`DATE` = item_inventory.`SOURCE_REF_DATE` AND invoice.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND invoice_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 12 THEN  (SELECT contact.`PRINT_NAME_AS` FROM credit_memo_items  JOIN credit_memo ON credit_memo.`ID` =  credit_memo_items.`CREDIT_MEMO_ID` JOIN contact ON contact.`ID` = credit_memo.`CUSTOMER_ID` WHERE credit_memo_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND credit_memo.`DATE` = item_inventory.`SOURCE_REF_DATE` AND credit_memo.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND credit_memo_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 13 THEN  (SELECT contact.`PRINT_NAME_AS` FROM sales_receipt_items  JOIN sales_receipt ON sales_receipt.`ID` =  sales_receipt_items.`SALES_RECEIPT_ID` JOIN contact ON contact.`ID` = sales_receipt.`CUSTOMER_ID` WHERE sales_receipt_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND sales_receipt.`DATE` = item_inventory.`SOURCE_REF_DATE` AND sales_receipt.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND sales_receipt_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )

        WHEN document_type_map.`ID` = 19 THEN  (SELECT item.`DESCRIPTION` FROM build_assembly_items  JOIN build_assembly ON build_assembly.`ID` =  build_assembly_items.`BUILD_ASSEMBLY_ID` JOIN item AS item ON item.`ID` =  build_assembly.`ASSEMBLY_ITEM_ID` WHERE (build_assembly.`ID` =  item_inventory.`SOURCE_REF_ID` or build_assembly_items.`ID` =  item_inventory.`SOURCE_REF_ID`) AND build_assembly.`DATE` = item_inventory.`SOURCE_REF_DATE` AND build_assembly.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND (build_assembly_items.`ITEM_ID` = item_inventory.`ITEM_ID` or build_assembly.`ASSEMBLY_ITEM_ID` = item_inventory.`ITEM_ID`) )

        WHEN document_type_map.`ID` = 21 THEN  (SELECT contact.`PRINT_NAME_AS` FROM check_items  JOIN  `check` ON `check`.`ID` =  check_items.`CHECK_ID` JOIN contact ON contact.`ID` = `check`.`PAY_TO_ID` WHERE check_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `check`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `check`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND check_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 27 THEN  (SELECT contact.`PRINT_NAME_AS` FROM `hemodialysis_items`  JOIN hemodialysis ON hemodialysis.`ID` =  hemodialysis_items.`HEMO_ID` JOIN contact ON contact.`ID` = hemodialysis.`CUSTOMER_ID` WHERE hemodialysis_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND hemodialysis.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  hemodialysis.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND hemodialysis_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
		WHEN document_type_map.`ID` = 29 THEN  (SELECT contact.`PRINT_NAME_AS` FROM `service_charges_items`  JOIN service_charges ON service_charges.`ID` =  service_charges_items.`SERVICE_CHARGES_ID` JOIN contact ON contact.`ID` = service_charges.`PATIENT_ID` WHERE service_charges_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND service_charges.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  service_charges.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND service_charges_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
        WHEN document_type_map.`ID` = 31 THEN  (SELECT contact.`PRINT_NAME_AS` FROM pull_out_items  JOIN  pull_out ON pull_out.`ID` =  pull_out_items.`PULL_OUT_ID` JOIN contact ON contact.`ID` = pull_out.`PREPARED_BY_ID` WHERE pull_out_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `pull_out`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `pull_out`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND pull_out_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
	END AS CONTACT_NAME';

    private string $TX_NOTES = '
    CASE
		WHEN document_type_map.`ID` = 1 THEN  (SELECT bill.`NOTES` FROM bill_items  JOIN bill ON bill.`ID` =  bill_items.`BILL_ID` WHERE bill_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 3 THEN  (SELECT bill_credit.`NOTES` FROM bill_credit_items  JOIN bill_credit ON bill_credit.`ID` =  bill_credit_items.`BILL_CREDIT_ID`  WHERE bill_credit_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND bill_credit.`DATE` = item_inventory.`SOURCE_REF_DATE` AND bill_credit.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND bill_credit_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 6 THEN  (SELECT inventory_adjustment.`NOTES` FROM inventory_adjustment_items  JOIN inventory_adjustment ON inventory_adjustment.`ID` =  inventory_adjustment_items.`INVENTORY_ADJUSTMENT_ID` WHERE inventory_adjustment_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND inventory_adjustment.`DATE` = item_inventory.`SOURCE_REF_DATE` AND inventory_adjustment.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND inventory_adjustment_items.`ITEM_ID` = item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 7 THEN  (SELECT stock_transfer.`NOTES` FROM stock_transfer_items  JOIN stock_transfer ON stock_transfer.`ID` =  stock_transfer_items.`STOCK_TRANSFER_ID`  WHERE stock_transfer_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND stock_transfer.`DATE` = item_inventory.`SOURCE_REF_DATE`  AND stock_transfer_items.`ITEM_ID` = item_inventory.`ITEM_ID` AND ( stock_transfer.`LOCATION_ID` = item_inventory.`LOCATION_ID` or stock_transfer.`TRANSFER_TO_ID` = item_inventory.`LOCATION_ID` ))

		WHEN document_type_map.`ID` = 10 THEN  (SELECT invoice.`NOTES` FROM invoice_items  JOIN invoice ON invoice.`ID` =  invoice_items.`INVOICE_ID`  WHERE invoice_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND invoice.`DATE` = item_inventory.`SOURCE_REF_DATE` AND invoice.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND invoice_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 12 THEN  (SELECT credit_memo.`NOTES` FROM credit_memo_items  JOIN credit_memo ON credit_memo.`ID` =  credit_memo_items.`CREDIT_MEMO_ID` WHERE credit_memo_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND credit_memo.`DATE` = item_inventory.`SOURCE_REF_DATE` AND credit_memo.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND credit_memo_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 13 THEN  (SELECT sales_receipt.`NOTES`FROM sales_receipt_items  JOIN sales_receipt ON sales_receipt.`ID` =  sales_receipt_items.`SALES_RECEIPT_ID`  WHERE sales_receipt_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND sales_receipt.`DATE` = item_inventory.`SOURCE_REF_DATE` AND sales_receipt.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND sales_receipt_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 19 THEN  (SELECT build_assembly.`NOTES` FROM build_assembly_items  JOIN build_assembly ON build_assembly.`ID` =  build_assembly_items.`BUILD_ASSEMBLY_ID`  WHERE (  build_assembly.`ID` =  item_inventory.`SOURCE_REF_ID` or build_assembly_items.`ID` =  item_inventory.`SOURCE_REF_ID`) AND build_assembly.`DATE` = item_inventory.`SOURCE_REF_DATE` AND build_assembly.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND (build_assembly_items.`ITEM_ID` = item_inventory.`ITEM_ID` or build_assembly.`ASSEMBLY_ITEM_ID` = item_inventory.`ITEM_ID`) )
		WHEN document_type_map.`ID` = 21 THEN  (SELECT `check`.`NOTES` FROM check_items  JOIN  `check` ON `check`.`ID` =  check_items.`CHECK_ID` WHERE check_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `check`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `check`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND check_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
		WHEN document_type_map.`ID` = 27 THEN  (SELECT null as NOTES FROM `hemodialysis_items`  JOIN hemodialysis ON hemodialysis.`ID` =  hemodialysis_items.`HEMO_ID` WHERE hemodialysis_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND hemodialysis.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  hemodialysis.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND hemodialysis_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
		WHEN document_type_map.`ID` = 29 THEN  (SELECT service_charges.`NOTES` FROM `service_charges_items`  JOIN service_charges ON service_charges.`ID` =  service_charges_items.`SERVICE_CHARGES_ID` WHERE service_charges_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND service_charges.`DATE` =  item_inventory.`SOURCE_REF_DATE` AND  service_charges.`LOCATION_ID` = item_inventory.`LOCATION_ID`  AND service_charges_items.`ITEM_ID` = item_inventory.`ITEM_ID`  )
        WHEN document_type_map.`ID` = 31 THEN  (SELECT pull_out.`NOTES` FROM pull_out_items  JOIN  pull_out ON pull_out.`ID` =  pull_out_items.`PULL_OUT_ID`  WHERE pull_out_items.`ID` =  item_inventory.`SOURCE_REF_ID` AND `pull_out`.`DATE` = item_inventory.`SOURCE_REF_DATE` AND `pull_out`.`LOCATION_ID` = item_inventory.`LOCATION_ID` AND pull_out_items.`ITEM_ID` =  item_inventory.`ITEM_ID` )
	END AS TX_NOTES';
    private $object;
    private $itemServices;
    private $priceLevelLineServices;
    private $numberServices;
    private $dateServices;
    private $itemRecountServers;
    public function __construct(
        ObjectServices $objectService,
        ItemServices $itemServices,
        PriceLevelLineServices $priceLevelLineServices,
        NumberServices $numberServices,
        DateServices $dateServices,
        ItemRecountServers $itemRecountServers
    ) {
        $this->object                 = $objectService;
        $this->itemServices           = $itemServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->numberServices         = $numberServices;
        $this->dateServices           = $dateServices;
        $this->itemRecountServers     = $itemRecountServers;
    }

    private function Store(int $PREVIOUS_ID, int $SEQUENCE_NO, int $ITEM_ID, int $LOCATION_ID, int $BATCH_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $QUANTITY, float $COST = 0, float $ENDING_QUANTITY = 0, float $ENDING_UNIT_COST = 0, float $ENDING_COST = 0)
    {

        $ID = (int) $this->object->ObjectNextID('ITEM_INVENTORY');

        ItemInventory::create([
            'ID'               => $ID,
            'PREVIOUS_ID'      => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO'      => $SEQUENCE_NO,
            'ITEM_ID'          => $ITEM_ID,
            'LOCATION_ID'      => $LOCATION_ID,
            'BATCH_ID'         => $BATCH_ID > 0 ? $BATCH_ID : 0,
            'SOURCE_REF_TYPE'  => $SOURCE_REF_TYPE,
            'SOURCE_REF_ID'    => $SOURCE_REF_ID,
            'SOURCE_REF_DATE'  => $SOURCE_REF_DATE,
            'QUANTITY'         => $QUANTITY,
            'COST'             => $COST,
            'ENDING_QUANTITY'  => $ENDING_QUANTITY,
            'ENDING_UNIT_COST' => $this->numberServices->doubleNumber($ENDING_UNIT_COST),
            'ENDING_COST'      => $this->numberServices->doubleNumber($ENDING_COST),
        ]);

        $nextData = $this->getNextEndingStore($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);

        foreach ($nextData as $list) {
            $ENDING_QUANTITY = $ENDING_QUANTITY + (float) $list->QUANTITY ?? 0;
            $NEW_ENDING_COST = $ENDING_QUANTITY + (float) $list->ENDING_UNIT_COST ?? 0;

            $this->getNextUpdate(
                $list->ID,
                $ITEM_ID,
                $LOCATION_ID,
                $list->SOURCE_REF_TYPE,
                $list->SOURCE_REF_ID,
                $list->SOURCE_REF_DATE,
                $ENDING_QUANTITY,
                $NEW_ENDING_COST
            );
        }

    }
    private function Update(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $QUANTITY, float $COST = 0)
    {
        $data = ItemInventory::where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE);

        $result = $data->first();

        if ($result) {

            $ID = $result->ID;

            $preResult = $this->getPreviousEnding($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE, $ID);

            $PREV_END_QTY  = (float) $preResult['ENDING_QUANTITY'];
            $PREV_END_COST = (float) $preResult['ENDING_COST'];

            $ENDING_QUANTITY = $PREV_END_QTY + $QUANTITY;
            $ENDING_COST     = $PREV_END_COST * $ENDING_QUANTITY;
            if ($ENDING_COST > 100000000 || $ENDING_COST < 0) {
                $ENDING_COST = 0;

            }
            $data->update(['QUANTITY' => $QUANTITY,
                'ENDING_QUANTITY'         => $ENDING_QUANTITY,
                'ENDING_COST'             => $this->numberServices->doubleNumber($ENDING_COST)]);

            $this->itemRecountServers->Insert($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
        }
    }

    public function DeleteInv(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE)
    {
        $this->Update(
            $ITEM_ID,
            $LOCATION_ID,
            $SOURCE_REF_TYPE,
            $SOURCE_REF_ID,
            $SOURCE_REF_DATE,
            0
        );

        // make auto fix quatity

    }

    private function getNextEndingStore(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE)
    {

        $result = DB::table('item_inventory')
            ->select([
                'ID',
                'QUANTITY',
                'ENDING_UNIT_COST',
                'SOURCE_REF_TYPE',
                'SOURCE_REF_ID',
                'SOURCE_REF_DATE',
                'ENDING_COST',
            ])
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '>', $SOURCE_REF_DATE)
            ->orderBy('SOURCE_REF_DATE', 'asc')
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
    private function getNextUpdate(int $ID, int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $ENDING_QUANTITY, float $ENDING_COST)
    {
        $U_COST = 0;
        if ((float) $ENDING_COST > 0 && (float) $ENDING_QUANTITY > 0) {

            $U_COST = $ENDING_COST / $ENDING_QUANTITY;
        }

        ItemInventory::where('ID', $ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->update([
                'ENDING_QUANTITY'  => $ENDING_QUANTITY,
                'ENDING_UNIT_COST' => $this->numberServices->doubleNumber($U_COST),
                'ENDING_COST'      => $this->numberServices->doubleNumber($ENDING_COST),
            ]);
    }

    private function getPreviousEnding(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE, int $ID): array
    {
        try {
            $prevData = DB::table('item_inventory')
                ->select([
                    'ENDING_QUANTITY',
                    'ENDING_COST',
                ])
                ->where('ITEM_ID', '=', $ITEM_ID)
                ->where('LOCATION_ID', '=', $LOCATION_ID)
                ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
                ->where('ID', '<>', $ID)
                ->orderBy('SOURCE_REF_DATE', 'desc')
                ->orderBy('ID', 'desc')
                ->limit(1)
                ->first();

            if ($prevData) {

                return [
                    'ENDING_QUANTITY' => $prevData->ENDING_QUANTITY ?? 0,
                    'ENDING_COST'     => $this->numberServices->doubleNumber($prevData->ENDING_COST ?? 0),
                ];
            }
            return [
                'ENDING_QUANTITY' => 0,
                'ENDING_COST'     => 0,
            ];
        } catch (\Throwable $th) {

            return [
                'ENDING_QUANTITY' => 0,
                'ENDING_COST'     => 0,
            ];
        }
    }
    public function getPreviousID(int $LOCATION_ID, int $ITEM_ID): int
    {
        $result = DB::table('item_inventory')
            ->select(['ID'])
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }
        return 0;
    }

    public function getEndingLastOutPut(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE): array
    {
        $data = DB::table('item_inventory')
            ->select([
                'ID',
                'SEQUENCE_NO',
                'ENDING_QUANTITY',
                'ENDING_UNIT_COST',
                'ENDING_COST',
            ])
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
            ->orderBy('SOURCE_REF_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($data) {
            return [
                'ID'               => $data->ID,
                'SEQUENCE_NO'      => $data->SEQUENCE_NO,
                'ENDING_QUANTITY'  => $data->ENDING_QUANTITY,
                'ENDING_UNIT_COST' => $data->ENDING_UNIT_COST,
                'ENDING_COST'      => $this->numberServices->doubleNumber($data->ENDING_COST),
            ];
        }

        return [
            'ID'               => 0,
            'SEQUENCE_NO'      => -1,
            'ENDING_QUANTITY'  => 0,
            'ENDING_UNIT_COST' => 0,
            'ENDING_COST'      => 0,
        ];
    }
    public function getEndingLastOutPutAdjustment(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE, int $REF_ID): array
    {

        $isExists = (bool) DB::table('item_inventory')
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', '=', 6)
            ->where('SOURCE_REF_DATE', '=', $SOURCE_REF_DATE)
            ->where('SOURCE_REF_ID', '=', $REF_ID)
            ->exists();

        $data = DB::table('item_inventory')
            ->select([
                'ID',
                'SEQUENCE_NO',
                'ENDING_QUANTITY',
                'ENDING_UNIT_COST',
                'ENDING_COST',
                'SOURCE_REF_TYPE',
                'SOURCE_REF_ID',
            ])
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
            ->orderBy('SOURCE_REF_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->get();

        $foundIt = $isExists == true ? false : true;

        foreach ($data as $list) {
            if ($foundIt == false) {
                if ($list->SOURCE_REF_TYPE == 6 && $list->SOURCE_REF_ID == $REF_ID) {
                    $foundIt = true;
                }
            } else {

                return [
                    'ID'               => $list->ID,
                    'SEQUENCE_NO'      => $list->SEQUENCE_NO,
                    'ENDING_QUANTITY'  => $list->ENDING_QUANTITY,
                    'ENDING_UNIT_COST' => $list->ENDING_UNIT_COST,
                    'ENDING_COST'      => $this->numberServices->doubleNumber($list->ENDING_COST),
                ];

            }
        }

        return [
            'ID'               => 0,
            'SEQUENCE_NO'      => -1,
            'ENDING_QUANTITY'  => 0,
            'ENDING_UNIT_COST' => 0,
            'ENDING_COST'      => 0,
        ];
    }
    private function InvItemExists(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE): bool
    {
        return (bool) ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->exists();
    }
    private function getInvItem(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE)
    {
        $result = ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->first();

        return $result;
    }

    public function InventoryModify(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE, int $BATCH_ID, float $QTY, float $COST)
    {

        $isInventoryExists = (bool) DB::table('item')
            ->where('ID', $ITEM_ID)
            ->whereIn('TYPE', [0, 1])
            ->exists();

        if (! $isInventoryExists) {
            return;
        }

        // if have a latest inventory adjustment
        $itsHave = (bool) ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', '=', 6)
            ->where('SOURCE_REF_DATE', '>', $SOURCE_REF_DATE)
            ->exists();

        if ($itsHave) {
            // stop to procceed.

            // make it auto compute
            return;
        }

        $dataExist = $this->getInvItem($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE);

        if (! $dataExist) {
                                                                              // new store
            $PREVIOUS_ID      = $this->getPreviousID($LOCATION_ID, $ITEM_ID); // FIXED
            $ENDING_ARRAY     = $this->getEndingLastOutPut($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
            $SEQUENCE_NO      = (int) $ENDING_ARRAY['SEQUENCE_NO'];
            $ENDING_QUANTITY  = (float) $ENDING_ARRAY['ENDING_QUANTITY'] + $QTY;
            $ENDING_UNIT_COST = (float) $this->numberServices->doubleNumber($COST ?? 0);
            $ENDING_COST      = $ENDING_UNIT_COST * $ENDING_QUANTITY;

            $this->Store(
                $PREVIOUS_ID,
                $SEQUENCE_NO + 1,
                $ITEM_ID,
                $LOCATION_ID,
                $BATCH_ID,
                $SOURCE_REF_TYPE,
                $SOURCE_REF_ID,
                $SOURCE_REF_DATE,
                $QTY,
                $COST,
                $ENDING_QUANTITY,
                $ENDING_UNIT_COST,
                $ENDING_COST
            );

            return;
        }
        // update

        $this->Update(
            $ITEM_ID,
            $LOCATION_ID,
            $SOURCE_REF_TYPE,
            $SOURCE_REF_ID,
            $SOURCE_REF_DATE,
            $QTY
        );
    }

    public function InventoryExecute($data, int $LOCATION_ID, int $SOURCE_REF_TYPE, $SOURCE_REF_DATE, bool $Is_Added)
    {

        foreach ($data as $list) {
            $COST               = 0;
            $SOURCE_REF_ID      = (int) $list->ID;
            $ITEM_ID            = (int) $list->ITEM_ID;
            $QUANTITY           = (float) $list->QUANTITY ?? 1;
            $BATCH_ID           = $list->BATCH_ID ?? 0;
            $UNIT_BASE_QUANTITY = (float) $list->UNIT_BASE_QUANTITY ?? 1;

            $QTY = (float) $QUANTITY * $UNIT_BASE_QUANTITY;

            if (! $Is_Added) {
                $QTY = $QTY * -1;
            }

            if (isset($list->COST)) {
                $COST = (float) $list->COST ?? 0;
            }

            if ($COST == 0) {
                $COST = (float) $this->priceLevelLineServices->GetCostByLocation($LOCATION_ID, $ITEM_ID);
                if (100000000 < $COST || 0 > $COST) {
                    $COST = 0;
                }
            }

            $this->InventoryModify(
                $ITEM_ID,
                $LOCATION_ID,
                $SOURCE_REF_ID,
                $SOURCE_REF_TYPE,
                $SOURCE_REF_DATE,
                $BATCH_ID,
                $QTY,
                $COST
            );

            if ($SOURCE_REF_TYPE == 27 && $SOURCE_REF_DATE == $this->dateServices->NowDate()) {
                // do nothing
            } else {
                // make it auto compute
                $this->itemRecountServers->Insert($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
            }

        }
    }

    public function InventoryExecuteAdjustment($data, int $LOCATION_ID, int $SOURCE_REF_TYPE, $SOURCE_REF_DATE): bool
    {

        try {
            foreach ($data as $list) {
                $SOURCE_REF_ID      = (int) $list->ID;
                $ITEM_ID            = (int) $list->ITEM_ID;
                $QUANTITY           = (float) $list->QUANTITY ?? 1;
                $BATCH_ID           = $list->BATCH_ID ?? 0;
                $UNIT_BASE_QUANTITY = (float) $list->UNIT_BASE_QUANTITY ?? 1;
                $ENDING_QUANTITY    = (float) $QUANTITY * $UNIT_BASE_QUANTITY;
                $QTY_DIFFERENCE     = (float) $list->QTY_DIFFERENCE ?? 0;

                $gotCOST = false;

                if (isset($list->COST)) {
                    $COST    = (float) $list->COST ?? 0;
                    $gotCOST = true;
                } else {
                    $COST    = 0;
                    $gotCOST = false;
                }

                $isExists = (bool) $this->InvItemExists(
                    $ITEM_ID,
                    $LOCATION_ID,
                    $SOURCE_REF_ID,
                    $SOURCE_REF_TYPE,
                    $SOURCE_REF_DATE
                );

                if (! $isExists) {
                    $PREVIOUS_ID      = $this->getPreviousID($LOCATION_ID, $ITEM_ID);
                    $endingData       = $this->getEndingLastOutPutAdjustment($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE, $SOURCE_REF_ID);
                    $SEQUENCE_NO      = (int) $endingData['SEQUENCE_NO'];
                    $ENDING_UNIT_COST = (float) $COST;

                    if ($gotCOST) {
                        $ENDING_COST = (float) $COST * $ENDING_QUANTITY;

                    } else {
                        $ENDING_COST = (float) $ENDING_UNIT_COST * $ENDING_QUANTITY;
                    }

                    if ($ENDING_COST < 0 || $ENDING_COST > 100000000) {
                        $ENDING_COST = 0;
                    }
                    if ($ENDING_QUANTITY == 0 && $ENDING_COST == 0) {
                        $ENDING_UNIT_COST = 0;
                        $gotCOST          = false;
                    }

                    $this->Store(
                        $PREVIOUS_ID,
                        $SEQUENCE_NO + 1,
                        $ITEM_ID,
                        $LOCATION_ID,
                        $BATCH_ID,
                        $SOURCE_REF_TYPE,
                        $SOURCE_REF_ID,
                        $SOURCE_REF_DATE,
                        $QTY_DIFFERENCE,
                        $COST,
                        $ENDING_QUANTITY,
                        $gotCOST == false ? $ENDING_UNIT_COST : $ENDING_COST / $ENDING_QUANTITY,
                        $ENDING_COST
                    );

                    $this->itemRecountServers->Insert($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);

                } else {

                    $ENDING_UNIT_COST = (float) $COST;

                    // set update
                    if ($gotCOST) {
                        $ENDING_COST = (float) $COST * $ENDING_QUANTITY;
                    } else {
                        $ENDING_COST = (float) $ENDING_UNIT_COST * $ENDING_QUANTITY;
                    }

                    if ($ENDING_COST < 0 || $ENDING_COST > 100000000) {
                        $ENDING_COST = 0;
                    }

                    $data = ItemInventory::where('ITEM_ID', '=', $ITEM_ID)
                        ->where('LOCATION_ID', '=', $LOCATION_ID)
                        ->where('SOURCE_REF_TYPE', '=', $SOURCE_REF_TYPE)
                        ->where('SOURCE_REF_ID', '=', $SOURCE_REF_ID)
                        ->where('SOURCE_REF_DATE', '=', $SOURCE_REF_DATE);

                    $PK_ID = $data->first()->ID;

                    $data->update([
                        'QUANTITY'         => $QTY_DIFFERENCE,
                        'ENDING_QUANTITY'  => $ENDING_QUANTITY,
                        'COST'             => $COST,
                        'ENDING_UNIT_COST' => $gotCOST == false ? $ENDING_UNIT_COST : $ENDING_COST / $ENDING_QUANTITY,
                        'ENDING_COST'      => $this->numberServices->doubleNumber($ENDING_COST),
                    ]);

                    $this->itemRecountServers->Insert($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
                }

            }

            return true;
        } catch (\Throwable $th) {

            dd($th->getMessage() . ' something wrong please contact system administrator.');
            return false;
        }

    }

    // data

    public function getDetails(int $ITEM_ID, int $LOCATION_ID, string $DATE)
    {
        $result = ItemInventory::query()
            ->select([
                'item_inventory.ID',
                'item_inventory.SOURCE_REF_ID',
                'item_inventory.SOURCE_REF_TYPE',
                'item_inventory.LOCATION_ID',
                DB::raw($this->TX_ID),
                'document_type_map.DESCRIPTION as TYPE',
                'item_inventory.SOURCE_REF_DATE',
                DB::raw($this->TX_CODE),
                'item_inventory.QUANTITY',
                'item_inventory.ENDING_QUANTITY',
                DB::raw($this->TX_CONTACT_NAME),
                DB::raw($this->TX_NOTES),
            ])
            ->join('document_type_map', 'document_type_map.ID', '=', 'item_inventory.SOURCE_REF_TYPE')
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('item_inventory.SOURCE_REF_DATE', '<=', $DATE)
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->get();
        // display the data
        return $result;
    }
    public function getDetails2(int $ITEM_ID, int $LOCATION_ID, string $DATE)
    {
        $result = ItemInventory::query()
            ->select([
                'item_inventory.ID',
                'item_inventory.SOURCE_REF_ID',
                'item_inventory.SOURCE_REF_TYPE',
                'item_inventory.LOCATION_ID',
                DB::raw($this->TX_ID),
                'document_type_map.DESCRIPTION as TYPE',
                'item_inventory.SOURCE_REF_DATE',
                DB::raw($this->TX_CODE),
                'item_inventory.QUANTITY',
                'item_inventory.ENDING_QUANTITY',
                DB::raw($this->TX_CONTACT_NAME),
                DB::raw($this->TX_NOTES),
            ])
            ->join('document_type_map', 'document_type_map.ID', '=', 'item_inventory.SOURCE_REF_TYPE')
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('item_inventory.SOURCE_REF_DATE', '<=', $DATE)
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'desc')
            ->orderBy('item_inventory.ID', 'desc')
            ->paginate(100);
        // display the data
        return $result;
    }
    public function isHaveInventoryAdjustmet(int $ITEM_ID, int $LOCATION_ID, string $DATE_FROM_ADJUSTMENT_START): bool
    {
        return ItemInventory::where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->where('item_inventory.SOURCE_REF_DATE', '=', $DATE_FROM_ADJUSTMENT_START)
            ->where('item_inventory.SOURCE_REF_TYPE', '=', 6)
            ->exists();
    }
    public function getAdjustmentDate(int $ITEM_ID, int $LOCATION_ID, string $DATE_FROM_ADJUSTMENT_START)
    {
        $result = ItemInventory::where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->where('item_inventory.SOURCE_REF_DATE', '<=', $DATE_FROM_ADJUSTMENT_START)
            ->where('item_inventory.SOURCE_REF_TYPE', '=', 6)
            ->orderby('item_inventory.SOURCE_REF_DATE', 'desc')
            ->first();

        if ($result) {
            return $result->SOURCE_REF_DATE;
        }

        $result = ItemInventory::where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->orderby('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->SOURCE_REF_DATE;
        }
        return null;
    }
    public function RecomputedOnhand(int $ITEM_ID, int $LOCATION_ID, string $DATE_START = '', string $DATE_BY = '')
    {
        $isInventory = $this->itemServices->isInventoryItem($ITEM_ID);

        if ($isInventory == false) {
            return;
        }

        if ($DATE_START == '') {
            $gotDATE = $this->getAdjustmentDate($ITEM_ID, $LOCATION_ID, $DATE_START);
            if ($gotDATE == null) {
                $DATE_FROM = $this->dateServices->NowDate();
            } else {
                $DATE_FROM = $gotDATE;
            }
        } else {
            $DATE_FROM = $DATE_START;
        }
        $DATE_FROM = $DATE_START;
        $DATE_TO   = $DATE_BY == '' ? $this->dateServices->NowDate() : $DATE_BY;

        $dataList = ItemInventory::query()
            ->select([
                'item_inventory.ID',
                'item_inventory.SOURCE_REF_TYPE',
                'item_inventory.SOURCE_REF_DATE',
                'item_inventory.QUANTITY',
                'item_inventory.ENDING_QUANTITY',
            ])
            ->where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->whereBetween('item_inventory.SOURCE_REF_DATE', [$DATE_FROM, $DATE_TO])
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->get();

        $IS_FIRST        = true;
        $RUNNING_BALANCE = 0.0;
        foreach ($dataList as $list) {
            if ($list->SOURCE_REF_TYPE == 6) {
                $IS_FIRST        = false;
                $RUNNING_BALANCE = (float) $list->ENDING_QUANTITY;
            } else {
                if ($IS_FIRST) {
                    $RUNNING_BALANCE = (float) $list->ENDING_QUANTITY;
                    $IS_FIRST        = false;
                } else {
                    $RUNNING_BALANCE = (float) $RUNNING_BALANCE + $list->QUANTITY;
                    $this->reFixEndQuantity($list->ID, $ITEM_ID, $LOCATION_ID, $RUNNING_BALANCE);
                }
            }
        }
    }
    private function GetIfPreviousDate(int $ITEM_ID, int $LOCATION_ID, string $DATE_FROM): string
    {
        // Get the previous date of the item inventory
        $result = ItemInventory::query()
            ->select([
                'item_inventory.SOURCE_REF_DATE',
            ])
            ->where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->where('item_inventory.SOURCE_REF_DATE', '<', $DATE_FROM)
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->first();

        if ($result) {
            return $result->SOURCE_REF_DATE;
        }

        return "";
    }
    public function RecomputedEndingOnhand(int $SOURCE_ID, int $SOURCE_TYPE, int $LOCATION_ID)
    {
        $itemInventory = ItemInventory::where('SOURCE_REF_TYPE', '=', $SOURCE_TYPE)
            ->where('SOURCE_REF_ID', '=', $SOURCE_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->first();

        if (! $itemInventory) {
            // If there is no item inventory, we will not do anything
            return;
        }

        $PK      = $itemInventory->ID;
        $ITEM_ID = $itemInventory->ITEM_ID;
        // Get the previous date of the item inventory
        $PREV_DATE = $this->GetIfPreviousDate($ITEM_ID, $LOCATION_ID, $itemInventory->SOURCE_REF_DATE);
        if ($PREV_DATE != "") {
            // If there is a previous date, we will use it as the starting point
            $DATE_FROM = $PREV_DATE;
        } else {
            // If there is no previous date, we will use the date of the current item inventory
            $DATE_FROM = $itemInventory->SOURCE_REF_DATE;
        }

        $DATE_TO = $this->dateServices->NowDate();

        // Get all item inventory data between the date range
        $dataList = ItemInventory::query()
            ->select([
                'item_inventory.ID',
                'item_inventory.SOURCE_REF_TYPE',
                'item_inventory.SOURCE_REF_DATE',
                'item_inventory.QUANTITY',
                'item_inventory.ENDING_QUANTITY',
            ])
            ->where('item_inventory.ITEM_ID', '=', $ITEM_ID)
            ->where('item_inventory.LOCATION_ID', '=', $LOCATION_ID)
            ->whereBetween('item_inventory.SOURCE_REF_DATE', [$DATE_FROM, $DATE_TO])
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->get();

        $IS_ACTIVE = false;
        $END_QTY   = 0.0;
        $I         = 0;
        foreach ($dataList as $list) {
            $I++;
            if ($list->ID == $PK) {
                // If the current item is the one we are updating, we will set it to active
                $IS_ACTIVE = true;
            }

            if ($IS_ACTIVE == true) {
                // If we are in the active state, we will update the ending quantity
                if ($list->SOURCE_REF_TYPE == 6) {
                    $END_QTY = (float) $list->ENDING_QUANTITY;
                } else {
                    if ($I == 1) {
                        // If this is the first item, we will set the ending quantity to the current quantity
                        $END_QTY = (float) $list->ENDING_QUANTITY;
                    } else {
                        // If this is not the first item, we will add the current quantity to the ending quantity
                        $END_QTY = (float) $END_QTY + $list->QUANTITY;
                    }

                }
                // Update the ending quantity in the database
                $this->reFixEndQuantity($list->ID, $ITEM_ID, $LOCATION_ID, $END_QTY);
            } else {
                // If we are not in the active state, we will just set the ending quantity to the current ending quantity
                // This is to ensure that the ending quantity is correct even if the item is not active
                // at the moment
                $END_QTY = (float) $list->ENDING_QUANTITY;
            }
        }
    }
    private function reFixEndQuantity(int $ID, int $ITEM_ID, int $LOCATION_ID, float $ENDING_QUANTITY)
    {

        ItemInventory::where('ID', '=', $ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->update([
                'ENDING_QUANTITY' => $ENDING_QUANTITY,
            ]);

    }
    public function ChangeDate($data, int $LOCATION_ID, int $SOURCE_REF_TYPE, string $NEW_DATE): bool
    {
        try {

            foreach ($data as $list) {
                $data = ItemInventory::where('ITEM_ID', '=', $list->ITEM_ID)
                    ->where('LOCATION_ID', '=', $LOCATION_ID)
                    ->where("SOURCE_REF_TYPE", '=', $SOURCE_REF_TYPE)
                    ->where('SOURCE_REF_ID', '=', $list->ID);

                if ($data->exists()) {
                    $data->update([
                        'SOURCE_REF_DATE' => $NEW_DATE,
                    ]);
                }

            }

            return true;
        } catch (\Throwable $th) {
            //throw $th;

            return false;
        }

    }
    public function Unposted()
    {

    }
}
