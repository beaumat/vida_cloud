<?php
namespace App\Enums;

enum DocType: int {
    case Purchase_Order       = 0;
    case Bill                 = 1;
    case Bill_Payment         = 2;
    case Bill_Credit          = 3;
    case Item_Receive         = 4;
    case Item_Release         = 5;
    case Inventory_Adjustment = 6;
    case Stock_Transfer       = 7;
    case Estimates            = 8;
    case Sales_Order          = 9;
    case Invoice              = 10;
    case Payment              = 11;
    case Credit_Memo          = 12;
    case Sales_Receipt        = 13;
    case Journal_Voucher      = 14;
    case Petty_Cash           = 15;
    case Price_Adjustment     = 16;
    case Inventory_Conversion = 17;
    case Withholding_Tax      = 18;
    case Build_Assembly       = 19;
    case Tax_Credit           = 20;
    case Write_Check          = 21;
    case Deposit              = 22;
    case General_Journal      = 23;
    case POS_StartingCash     = 24;
    case POS_Log              = 25;
    case Fund_Transfer        = 26;
    case Hemo_Treatment       = 27;
    case Philhealth           = 28;
    case Service_Charges      = 29;
    case Patient_Payments     = 30;
    case Pull_Out             = 31;
    case Reconciliation       = 32;
    case Depreciation         = 33;
    case Bank_Transfer        = 34;
    case Spend_Money          = 35;
    case Receive_Money        = 36;
    case Fund_TransferReverse = 37;

}
