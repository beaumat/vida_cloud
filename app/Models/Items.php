<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;


    protected $table = 'item';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION',
        'PURCHASE_DESCRIPTION',
        'GROUP_ID',
        'SUB_CLASS_ID',
        'TYPE',
        'STOCK_TYPE',
        'GL_ACCOUNT_ID',
        'COGS_ACCOUNT_ID',
        'ASSET_ACCOUNT_ID',
        'TAXABLE',
        'PREFERRED_VENDOR_ID',
        'MANUFACTURER_ID',
        'RATE',
        'COST',
        'RATE_TYPE',
        'PAYMENT_METHOD_ID',
        'NOTES',
        'BASE_UNIT_ID',
        'PURCHASES_UNIT_ID',
        'SHIPPING_UNIT_ID',
        'SALES_UNIT_ID',
        'PRINT_INDIVIDUAL_ITEMS',
        'PICTURE',
        'INACTIVE',
        'CUSTOM_FIELD1',
        'CUSTOM_FIELD2',
        'CUSTOM_FIELD3',
        'CUSTOM_FIELD4',
        'CUSTOM_FIELD5',
        'NON_HEMO',
        'HEMO_NON_INVENTORY',
        'IS_KIT',
        'NON_PULL_OUT',
        'PHIC_AGREEMENT_FORM_TITLE_ID',
    ];
}
