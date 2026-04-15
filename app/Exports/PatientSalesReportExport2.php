<?php

namespace App\Exports;

use App\Services\PatientReportServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatientSalesReportExport2 implements FromCollection, ShouldAutoSize, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $patientReportServices;
    protected string $scFrom;
    protected string $scTo;
    protected string $pFrom;
    protected string $pTo;
    protected int $locationId;
    protected array $patientData;
    protected array $itemData;
    public function __construct(
        PatientReportServices $patientReportServices,
        string $scFrom,
        string $scTo,
        string $pFrom,
        string $pTo,
        int $locationId,
        array $patientData,
        array $itemData
    ) {
        $this->patientReportServices = $patientReportServices;
        $this->scFrom = $scFrom;
        $this->scTo = $scTo;
        $this->pFrom    = $pFrom;
        $this->pTo = $pTo;
        $this->locationId = $locationId;
        $this->patientData = $patientData;
        $this->itemData = $itemData;
    }
    private function empty()
    {


        return  ['PN'    => '`', 'IN'   => '`', 'SC_DATE'   => '`', 'SC_CODE'   => '`', 'SC_AMOUNT' => '`', 'P_DATE'    => '`', 'P_CODE'    => '`', 'P_METHOD'  => '`', 'P_DEPOSIT' => '`', 'P_PAID'    => '`', 'BAL'       => '`', 'DOCTOR'    => '`', 'LOCATION'  => '`',];
    }
    public function collection()
    {
        $data =  $this->patientReportServices->generateSalesReportData2(
            $this->scFrom,
            $this->scTo,
            $this->locationId,
            $this->patientData,
            $this->itemData
        )->toArray();


        $headers = [
            'PN'        => 'PATIENT NAME',
            'IN'        => 'ITEM NAME',
            'SC_DATE'   => '(SC)DATE',
            'SC_CODE'   => '(SC)CODE',
            'SC_AMOUNT' => '(SC)AMOUNT',
            'P_DATE'    => '(P)Date',
            'P_CODE'    => '(P)Code',
            'P_METHOD'  => '(P)METHOD',
            'P_DEPOSIT' => '(P)DEPOSIT',
            'P_PAID'    => '(P)PAID',
            'BAL'       => 'BALANCE',
            'DOCTOR'    => 'DOCTOR',
            'LOCATION'  => 'LOCATION',
        ];


        $tempName  = '';
        $PREV_SC_ITEM_REF_ID = 0;
        $TOTAL_CHARGE = 0;
        $TOTAL_PAID = 0;

        $CASH_AMOUNT = 0;
        $PHILHEALTH_AMOUNT = 0;
        $DSWD_AMOUNT = 0;
        $LINGAP_AMOUNT = 0;
        $PCSO_AMOUNT = 0;
        $OTHER_GL_AMOUNT = 0;
        $PRE_COLLECTION = 0;

        $OP_AMOUNT = 0;
        $OVP_AMOUNT = 0;



        $preData = $this->patientReportServices->generatePrevCollection2(
            $this->scFrom,
            $this->scTo,
            $this->locationId,
            $this->patientData,
            $this->itemData
        );

        foreach ($preData as $dataList) {
            $PRE_COLLECTION =  $PRE_COLLECTION +  (float) $dataList->PP_PAID  ?? 0;
        }

        $running_balance = 0;
        $NO_OF_PATIENT = 0;
        $NO_OF_TREATMENT = 0;
        $sc_code = '';
        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row

        // Loop through your data and format it for export
        foreach ($data as $list) {
            if ($sc_code == $list->SC_CODE) {
                $is_sc = false;
            } else {
                $NO_OF_TREATMENT = $NO_OF_TREATMENT  + 1;
                $is_sc = true;
            }

            if ($PREV_SC_ITEM_REF_ID == $list->SC_ITEM_REF_ID) {
                $not_to_charge = true;
            } else {
                $not_to_charge = false;
            }

            if ($tempName == $list->PATIENT_NAME) {
                $is_add = false;
                if ($not_to_charge == false) {
                    $running_balance = $running_balance + $list->SC_AMOUNT ?? 0;
                }
            } else {
                $is_add = true;
                $is_sc = true;
                $running_balance = $list->SC_AMOUNT ?? 0;
                $NO_OF_PATIENT = $NO_OF_PATIENT + 1;
            }

            $running_balance = $running_balance - $list->PP_PAID;

            $tempName = $list->PATIENT_NAME;
            $sc_code = $list->SC_CODE;
            $PREV_SC_ITEM_REF_ID = $list->SC_ITEM_REF_ID ?? 0;

            if ($not_to_charge == false) {
                $TOTAL_CHARGE = $TOTAL_CHARGE + $list->SC_AMOUNT;
            }

            if ($list->PP_PAID > 0) {
                $TOTAL_PAID = $TOTAL_PAID + $list->PP_PAID ?? 0;


                if ($list->PAYMENT_METHOD_ID == 1) {
                    //Cash
                    $CASH_AMOUNT = $CASH_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 91) {
                    //Philhealth
                    $PHILHEALTH_AMOUNT = $PHILHEALTH_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 92) {
                    //DSWD
                    $DSWD_AMOUNT = $DSWD_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 93) {
                    //LINGAP
                    $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 94) {
                    //PCSO
                    $PCSO_AMOUNT = $PCSO_AMOUNT + $list->PP_PAID ?? 0;
                }
                if ($list->PAYMENT_METHOD_ID == 96) {
                    //Other GL
                    $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 97) {
                    //OVP
                    $OVP_AMOUNT = $OVP_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 98) {
                    //VP
                    $OP_AMOUNT = $OP_AMOUNT + $list->PP_PAID ?? 0;
                }
            }

            if ($is_add) {
                $rowData = $this->empty();

                $finalData[] = array_values($rowData);
            }

            $rowData = [
                'PN'        => $is_add ? $list->PATIENT_NAME : '',
                'IN'        => $list->ITEM_NAME ?? '',
                'SC_DATE'   => $is_sc ? date('M/d/Y', strtotime($list->SC_DATE))  : '',
                'SC_CODE'   => $is_sc ? $list->SC_CODE : '',
                'SC_AMOUNT' => $not_to_charge ? 0 : number_format($list->SC_AMOUNT, 2),
                'P_DATE'    =>  $list->PP_DATE ? ' ' . date('M/d/Y', strtotime($list->PP_DATE))  : '',
                'P_CODE'    => $list->PP_CODE ? ' ' . $list->PP_CODE : '',
                'P_METHOD'  => $list->PAYMENT_METHOD ? ' ' . $list->PAYMENT_METHOD : '',
                'P_DEPOSIT' => $list->PP_DEPOSIT > 0 ? ' ' . number_format($list->PP_DEPOSIT, 2) : '',
                'P_PAID'    => $list->PP_PAID > 0 ? ' ' . number_format($list->PP_PAID, 2) : '',
                'BAL'       =>  number_format($running_balance, 2),
                'DOCTOR'    =>  $is_add ? $list->DOCTOR_NAME : '',
                'LOCATION'  => $is_add ? $list->LOCATION_NAME : '',
            ];

            $finalData[] = array_values($rowData);
        }
        // BLANK
        $rowData = $this->empty();
        $finalData[] = array_values($rowData);
        // No. of Patient - SC TOTAL
        $rowData = ['PN' => 'No. of Patient: ' . $NO_OF_PATIENT, 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => 'No. of Treatment: ' . $NO_OF_TREATMENT, 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' =>  'Philhealth Paid: ' . number_format($PHILHEALTH_AMOUNT, 2), 'P_DEPOSIT' => '', 'P_PAID' => 'Cash Paid: ' . number_format($CASH_AMOUNT, 2), 'BAL' => '', 'DOCTOR' => 'TOTAL CHARGE :' . number_format($TOTAL_CHARGE, 2), 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // PAID TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => 'DSWD Paid: ' . number_format($DSWD_AMOUNT, 2), 'P_DEPOSIT' => 'OP Paid: ' . number_format($OP_AMOUNT, 2), 'P_PAID' => 'Previous Collection: ' . number_format($PRE_COLLECTION, 2), 'BAL' => '', 'DOCTOR' => 'TOTAL PAID :' . number_format($TOTAL_PAID, 2), 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // BALANCE TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => 'LINGAP Paid: ' . number_format($LINGAP_AMOUNT, 2), 'P_DEPOSIT' => 'OVP Paid: ' . number_format($OVP_AMOUNT, 2), 'P_PAID' => 'Net Cash Sales: ' . number_format($CASH_AMOUNT + $PRE_COLLECTION, 2), 'BAL' => '', 'DOCTOR' => 'TOTAL BALANCE :' . number_format($TOTAL_CHARGE - $TOTAL_PAID, 2), 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => 'PCSO Paid: ' . number_format($PCSO_AMOUNT, 2), 'P_DEPOSIT' => 'Other GL Paid: ' . number_format($OTHER_GL_AMOUNT, 2), 'P_PAID' => 0, 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);


        // BLANK
        $rowData = $this->empty();
        $finalData[] = array_values($rowData);

        $rowData = ['PN' => 'Previous Cash Collection :', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        foreach ($preData as $list) {
            $rowData = ['PN' => ' ' . $list->PATIENT_NAME, 'IN' => ' ' .  $list->ITEM_NAME, 'SC_DATE' => ' ' . $list->PAYMENT_METHOD . ' : ' . number_format($list->PP_PAID, 2), 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
            $finalData[] = array_values($rowData);
        }



        return collect($finalData);
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->freezePane('A2'); // Freezes rows above A2 (i.e., the header)


        $highestRow = $sheet->getHighestRow();    // e.g., 5
        $highestColumn = $sheet->getHighestColumn(); // e.g., 'C'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);


        // $headers = [
        //     'PN'        => 'PATIENT NAME',
        //     'IN'        => 'ITEM NAME',
        //     'SC_DATE'   => '(SC)DATE',
        //     'SC_CODE'   => '(SC)CODE',
        //     'SC_AMOUNT' => '(SC)AMOUNT',
        //     'P_DATE'    => '(P)Date',
        //     'P_CODE'    => '(P)Code',
        //     'P_METHOD'  => '(P)METHOD',
        //     'P_DEPOSIT' => '(P)DEPOSIT',
        //     'P_PAID'    => '(P)PAID',
        //     'BAL'       => 'BALANCE',
        //     'DOCTOR'    => 'DOCTOR',
        //     'LOCATION'  => 'LOCATION',
        // ];


        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . $row;
                $cellValue = $sheet->getCell($cellCoordinate)->getValue();
                if ($cellValue === 'PATIENT NAME' || $cellValue === 'ITEM NAME' || $cellValue === 'DOCTOR' || $cellValue === 'LOCATION') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('add8e6'); // BLUE
                }

                if ($cellValue === '(P)Date' || $cellValue === '(P)Code' || $cellValue === '(P)METHOD' || $cellValue === '(P)DEPOSIT' || $cellValue === '(P)PAID') {
                    $sheet->getStyle($cellCoordinate)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ade6ad'); // GREEN
                }
                if ($cellValue === '(SC)DATE' || $cellValue === '(SC)CODE' || $cellValue === '(SC)AMOUNT') {
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
                if (substr($cellValue, 0, 1)  === ' ') {
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
