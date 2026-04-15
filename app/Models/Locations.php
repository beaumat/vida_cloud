<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    use HasFactory;
    protected $table = 'location';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'NAME',
        'INACTIVE',
        'PRICE_LEVEL_ID',
        'GROUP_ID',
        'HCI_MANAGER_ID',
        'PHIC_INCHARGE_ID',
        'PRIMARY_LOGO',
        'SECONDARY_LOGO',
        'NAME_OF_BUSINESS',
        'ACCREDITATION_NO',
        'BLDG_NAME_LOT_BLOCK',
        'STREET_SUB_VALL',
        'BRGY_CITY_MUNI',
        'PROVINCE',
        'ZIP_CODE',
        'REPORT_HEADER_1',
        'REPORT_HEADER_2',
        'REPORT_HEADER_3',
        'PHIC_SOA_FORMAT',
        'PHIC_FORM_MODIFY',
        'IS_DAILY',
        'LOGO_FILE',
        'USED_DRY_WEIGHT',
        'DOCTOR_ORDER_DEFAULT',
        'OTHER_SIGN',
        'PREPARED_BY_ID',
        'HD_FACILITY_REP_ID',
        'ITEMIZED_BASE',
        'PHIC_INCHARGE2_ID',
        'LEAVE_BLANK_AG_ADMIN_OFFICE_FEE',
        'PF_TAX_ID',
        'HCI_MANAGER_TREATMENT_ID',
        'MEDICAL_DIRECTOR'
    ];
}
