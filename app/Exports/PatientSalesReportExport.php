<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientSalesReportExport implements FromCollection, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $dataList;
    protected $preDataList;
    public function __construct(
        $data,
        $preData
    ) {
        $this->dataList    = $data;
        $this->preDataList = $preData;
    }
    private function empty()
    {

        return [
            'PN'          => '`',
            'CATEGORY'    => '`',
            'DESCRIPTION' => '`',
            'QTY'         => '`',
            'CODE'        => '`',
            'DATE'        => '`',
            'AMOUNT'      => '`',
            'CREDIT'      => '`',
            'BAL'         => '`',
            'DOCTOR'      => '`',
            'LOCATION'    => '`',
        ];

    }
    public function collection()
    {

        $PREV_SC_ITEM_REF_ID = 0;
        $TOTAL_CHARGE        = 0;
        $TOTAL_PAID          = 0;

        $CASH_AMOUNT       = 0;
        $PHILHEALTH_AMOUNT = 0;
        $DSWD_AMOUNT       = 0;
        $LINGAP_AMOUNT     = 0;
        $PCSO_AMOUNT       = 0;
        $OTHER_GL_AMOUNT   = 0;
        $PRE_CASH_AMOUNT   = 0;
        $PRE_COLLECTION    = 0;
        $OP_AMOUNT         = 0;
        $OVP_AMOUNT        = 0;

        $headers = [
            'PN'          => 'PATIENT NAME',
            'CATEGORY'    => 'CATEGORY',
            'DESCRIPTION' => 'DESCRIPTION',
            'QTY'         => 'QTY',
            'REFERENCE'   => 'REFERENCE',
            'DATE'        => 'DATE',
            'AMOUNT'      => 'CHARGES',
            'CREDIT'      => 'CREDIT',
            'BAL'         => 'BALANCE',
            'DOCTOR'      => 'DOCTOR',
            'LOCATION'    => 'LOCATION',
        ];

        $tempName = '';

        foreach ($this->preDataList as $data) {

            switch ($data->PAYMENT_METHOD_ID) {
                case 1:
                    $PRE_CASH_AMOUNT = $PRE_CASH_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 92:
                    $DSWD_AMOUNT = $DSWD_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 93:
                    $LINGAP_AMOUNT = $LINGAP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 94:
                    $PCSO_AMOUNT = $PCSO_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 96:
                    $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 98:
                    $OP_AMOUNT = $OP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                case 97:
                    $OVP_AMOUNT = $OVP_AMOUNT + $data->PP_PAID ?? 0;
                    break;
                default:
                    # code...
                    break;
            }

            if ($data->PAYMENT_METHOD_ID == 1) {
                // $PRE_CASH_AMOUNT = $PRE_CASH_AMOUNT + $data->PP_PAID ?? 0;
            } else {
                $PRE_COLLECTION = $PRE_COLLECTION + $data->PP_PAID ?? 0;
            }
        }

        $running_balance = 0;
        $NO_OF_PATIENT   = 0;
        $NO_OF_TREATMENT = 0;
        $sc_code         = '';
        $finalData       = [];
        $finalData[]     = array_values($headers); // Add headers as the first row

        // Loop through your data and format it for export
        foreach ($this->dataList as $list) {

            if ($list->LINE_NO != 999) {
                if ($sc_code == $list->CODE) {
                    $is_sc = false;
                } else {
                    $is_sc           = true;
                    $NO_OF_TREATMENT = $NO_OF_TREATMENT + 1;
                    $rowData         = $this->empty();
                    $finalData[]     = array_values($rowData);
                }
            }

            if ($PREV_SC_ITEM_REF_ID == $list->ITEM_REF_ID) {

                $not_to_charge = true;
            } else {
                $not_to_charge = false;
            }

            if ($list->LINE_NO != 999) {
                $TOTAL_CHARGE = $TOTAL_CHARGE + $list->AMOUNT ?? 0;
            }

            if ($tempName == $list->PATIENT_NAME) {
                $is_add = false;
                if ($not_to_charge == false) {

                }

                if ($list->LINE_NO == 999) {
                    $running_balance = $running_balance - $list->AMOUNT ?? 0;
                } else {
                    $running_balance = $running_balance + $list->AMOUNT ?? 0;
                }
            } else {
                $is_add          = true;
                $is_sc           = true;
                $running_balance = $list->AMOUNT ?? 0;
                $NO_OF_PATIENT   = $NO_OF_PATIENT + 1;
            }

            if ($list->LINE_NO != 999) {
                $tempName            = $list->PATIENT_NAME;
                $sc_code             = $list->CODE;
                $PREV_SC_ITEM_REF_ID = $list->ITEM_REF_ID ?? 0;
            }

            if ($is_add == true) {
                $rowData     = [];
                $finalData[] = array_values($rowData);
            }

            $running_balance = $running_balance - $list->PREVIOUS_CREDIT ?? 0;
            $TOTAL_CHARGE    = $TOTAL_CHARGE - $list->PREVIOUS_CREDIT ?? 0;
            if (substr($list->ITEM_NAME, 0, 6) == 'Cash :') {
                $CASH_AMOUNT = $CASH_AMOUNT + $list->AMOUNT ?? 0;
                $TOTAL_PAID  = $TOTAL_PAID + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 12) == 'Philhealth :') {
                $PHILHEALTH_AMOUNT = $PHILHEALTH_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 6) == 'DSWD :') {
                $DSWD_AMOUNT = $DSWD_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 8) == 'LINGAP :') {
                $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 6) == 'PCSO :') {
                $PCSO_AMOUNT = $PCSO_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 4) == 'OP :') {
                $OP_AMOUNT = $OP_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 5) == 'OVP :') {
                $OVP_AMOUNT = $OVP_AMOUNT + $list->AMOUNT ?? 0;
            }

            if (substr($list->ITEM_NAME, 0, 10) == 'Other GL :') {
                $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->AMOUNT ?? 0;
            }

            if ($list->LINE_NO == 999) {
                // $TOTAL_PAID = $TOTAL_PAID + $list->AMOUNT ?? 0;

                $not_to_charge = true;
            } else {
                if ($list->PREVIOUS_CREDIT ?? 0 > 0) {
                    $running_balance = $running_balance - $list->PREVIOUS_CREDIT ?? 0;
                    $TOTAL_CHARGE    = $TOTAL_CHARGE - $list->PREVIOUS_CREDIT ?? 0;

                }

            }

            $rowData = [
                'PN'          => $is_add ? $list->PATIENT_NAME : '',
                'CATEGORY'    => $list->CLASS_NAME ?? '',
                'DESCRIPTION' => trim($list->ITEM_NAME) ?? '',
                'QTY'         => $list->QUANTITY > 0 ? number_format($list->QUANTITY, 0) : '',
                'CODE'        => $is_sc ? $list->CODE : '',
                'DATE'        => $is_sc ? date('M/d/Y', strtotime($list->DATE)) : '',
                'AMOUNT'      => $not_to_charge ? 0 : $list->AMOUNT,
                'CREDIT'      => $list->LINE_NO == 999 ? $list->AMOUNT * -1 : ($list->PREVIOUS_CREDIT > 0 ? $list->PREVIOUS_CREDIT * -1 : ''),
                'BAL'         => $running_balance,
                'DOCTOR'      => $is_add ? $list->DOCTOR_NAME : '',
                'LOCATION'    => $is_add ? $list->LOCATION_NAME : '',
            ];

            $finalData[] = array_values($rowData);
        }
        // BLANK
        $rowData     = $this->empty();
        $finalData[] = array_values($rowData);
        // TOTAL_CHARGE
        $rowData = [
            'PN'          => 'No. of Patient: ' . $NO_OF_PATIENT,
            'CATEGORY'    => 'No. of Treatment:  ' . $NO_OF_TREATMENT,
            'DESCRIPTION' => '',
            'QTY'         => '',
            'CODE'        => '',
            'DATE'        => '',
            'AMOUNT'      => '',
            'CREDIT'      => 'Philhealth Paid: ' . $PHILHEALTH_AMOUNT,
            'BAL'         => 'Cash Paid: ' . $CASH_AMOUNT,
            'DOCTOR'      => 'Total Charges :' . $TOTAL_CHARGE,
            'LOCATION'    => '',
        ];
        $finalData[] = array_values($rowData);

        // TOTAL_PAID
        $rowData = [
            'PN'          => '',
            'CATEGORY'    => '',
            'DESCRIPTION' => '',
            'QTY'         => '',
            'CODE'        => '',
            'DATE'        => '',
            'AMOUNT'      => 'DSWD Paid: ' . $DSWD_AMOUNT,
            'CREDIT'      => 'OP Paid: ' . $OP_AMOUNT,
            'BAL'         => 'Previous Cash: ' . $PRE_CASH_AMOUNT,
            'DOCTOR'      => 'Total Credit :' . $TOTAL_PAID,
            'LOCATION'    => '',
        ];
        $finalData[] = array_values($rowData);

        // BALANCE
        $rowData = [
            'PN'          => '',
            'CATEGORY'    => '',
            'DESCRIPTION' => '',
            'QTY'         => '',
            'CODE'        => '',
            'DATE'        => '',
            'AMOUNT'      => 'LINGAP Paid: ' . $LINGAP_AMOUNT,
            'CREDIT'      => 'OVP Paid: ' . $OVP_AMOUNT,
            'BAL'         => 'Net Cash Sales: ' . $CASH_AMOUNT + $PRE_CASH_AMOUNT,
            'DOCTOR'      => 'Total Balance :' . $TOTAL_CHARGE - $TOTAL_PAID,
            'LOCATION'    => '',
        ];
        $finalData[] = array_values($rowData);

        // LAST
        $rowData = [
            'PN'          => '',
            'CATEGORY'    => '',
            'DESCRIPTION' => '',
            'QTY'         => '',
            'CODE'        => '',
            'DATE'        => '',
            'AMOUNT'      => 'PCSO Paid: ' . $PCSO_AMOUNT,
            'CREDIT'      => 'Other GL Paid: ' . $OTHER_GL_AMOUNT,
            'BAL'         => '',
            'DOCTOR'      => '',
            'LOCATION'    => '',
        ];
        $finalData[] = array_values($rowData);

        // BLANK
        $rowData     = $this->empty();
        $finalData[] = array_values($rowData);
        $rowData     = [
            'PN'          => 'Previous Credit Summary',
            'CATEGORY'    => '',
            'DESCRIPTION' => '',
            'QTY'         => '',
            'CODE'        => '',
            'DATE'        => '',
            'AMOUNT'      => '',
            'CREDIT'      => '',
            'BAL'         => '',
            'DOCTOR'      => '',
            'LOCATION'    => ''];
        $finalData[] = array_values($rowData);

        $rowData = [
            'PN'          => 'Payment Type',
            'CATEGORY'    => 'Patient Name',
            'DESCRIPTION' => 'Payment Date',
            'QTY'         => 'Item Credit',
            'CODE'        => 'Amount Credit',
            'DATE'        => '',
            'AMOUNT'      => '',
            'CREDIT'      => '',
            'BAL'         => '',
            'DOCTOR'      => '',
            'LOCATION'    => ''];
        $finalData[] = array_values($rowData);

        foreach ($this->preDataList as $list) {
            $rowData = [
                'PN'          => $list->PAYMENT_METHOD,
                'CATEGORY'    => $list->PATIENT_NAME,
                'DESCRIPTION' => date('m/d/Y', strtotime($list->PP_DATE)),
                'QTY'         => $list->ITEM_NAME,
                'CODE'        => $list->PP_PAID,
                'DATE'        => '',
                'AMOUNT'      => '',
                'CREDIT'      => '',
                'BAL'         => '',
                'DOCTOR'      => '',
                'LOCATION'    => '',
            ];
            $finalData[] = array_values($rowData);

        }

        return collect($finalData);
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->freezePane('A2'); // Freezes rows above A2 (i.e., the header)

        $highestRow         = $sheet->getHighestRow();    // e.g., 5
        $highestColumn      = $sheet->getHighestColumn(); // e.g., 'C'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        //  $headers = [
        //     'PN'          => 'PATIENT NAME',
        //     'DESCRIPTION' => 'DESCRIPTION',
        //     'REFERENCE'   => 'REFERENCE',
        //     'DATE'        => 'DATE',
        //     'AMOUNT'      => 'CHARGES',
        //     'CREDIT'      => 'CREDIT',
        //     'BAL'         => 'BALANCE',
        //     'DOCTOR'      => 'DOCTOR',
        //     'LOCATION'    => 'LOCATION',
        // ];

        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $cellValue      = $sheet->getCell($cellCoordinate)->getValue();
                if ($cellValue === 'PATIENT NAME' || $cellValue === 'CATEGORY' || $cellValue === 'QTY' || $cellValue === 'DESCRIPTION' || $cellValue === 'DOCTOR' || $cellValue === 'LOCATION') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('add8e6'); // BLUE
                }

                if ($cellValue === 'CREDIT') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ade6ad'); // GREEN
                }
                if ($cellValue === 'DATE' || $cellValue === 'REFERENCE' || $cellValue === 'CHARGES') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ade6e6'); // CRYAN
                }
                if ($cellValue === 'BALANCE') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('f58f8b'); // RED
                }
                if (substr($cellValue, 0, 1) === ' ') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('FFC300'); // YELLOW
                }
                if ($cellValue === '`') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('020802'); // black
                }
            }
        }

        return [];
    }
}
