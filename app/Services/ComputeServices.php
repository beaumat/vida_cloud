<?php

namespace App\Services;

class ComputeServices
{
    private function computeTax(float $initialAmount, float $taxRate, bool $vatInclusive = true)
    {
        // Calculate tax amount
        $taxAmount = floatval($initialAmount) * floatval($taxRate);

        if ($vatInclusive) {
            // VAT Inclusive: Calculate taxable amount
            $taxableAmount = $initialAmount / (1 + $taxRate);
            // Subtract taxable amount from initial amount to get tax amount
            $taxAmount = $initialAmount - $taxableAmount;
        } else {
            // VAT Exclusive: Tax amount is already calculated
            $taxableAmount = $initialAmount;
        }

        // Return results
        return array(
            'TAX_AMOUNT' => $taxAmount,
            'TAXABLE_AMOUNT' => $taxableAmount
        );
    }
    public function ItemComputeTax(float $amount, bool $isTax, int $taxId, float $taxRate): array
    {
        $result = [];
        if ($isTax) {
            $rate = $taxRate / 100;
            switch ($taxId) {
                case 12:
                    $result = $this->computeTax($amount,  $rate, true);
                    break;
                case 13:
                    $result = $this->computeTax($amount,  $rate, false);
                    break;
                case 14:
                    $result = $this->computeTax($amount,  $rate, true);
                    break;
                default:
                    $result = $this->computeTax($amount,  0, true);
                    break;
            }
        } else {
            $result = $this->computeTax($amount,  0, true);
        }

        return $result;
    }
    public function taxCompute($itemResult, int $taxID): array
    {
        $amount = 0;
        $taxAmount = 0;
        $taxableAmount = 0;
        $nonTaxableAmount = 0;

        foreach ($itemResult as $item) {
            if ($item->TAXABLE) {
                switch ($taxID) {
                    case  12:
                        $amount +=  ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 13:
                        $amount += ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 14:
                        $amount += $item->AMOUNT;
                        $taxAmount = 0;
                        $nonTaxableAmount += $item->AMOUNT;
                        break;
                    default:
                        break;
                }
            } else {
                $amount += $item->AMOUNT;
                $nonTaxableAmount  += $item->AMOUNT;
            }
        }
        $getResult = array(
            [
                'AMOUNT'            => $amount,
                'TAX_AMOUNT'        => $taxAmount,
                'TAXABLE_AMOUNT'    => $taxableAmount,
                'NONTAXABLE_AMOUNT' => $nonTaxableAmount
            ]
        );



        return $getResult;
    }

    public function taxComputeWithExpenses($itemResult, $expensesResult, int $taxID, float $pay): array
    {
        $amount = 0;
        $taxAmount = 0;
        $taxableAmount = 0;
        $nonTaxableAmount = 0;

        $item_amount = 0;
        $expenses_amount = 0;

        foreach ($itemResult as $item) {
            if ($item->TAXABLE) {
                switch ($taxID) {
                    case  12:
                        $amount +=  ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $item_amount +=  ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);

                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 13:
                        $amount += ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $item_amount += ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);

                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 14:
                        $amount += $item->AMOUNT;
                        $item_amount += $item->AMOUNT;

                        $taxAmount += 0;
                        $nonTaxableAmount += $item->AMOUNT;
                        break;
                    default:
                        break;
                }
            } else {
                $amount += $item->AMOUNT;
                $item_amount += $item->AMOUNT;
                
                $nonTaxableAmount  += $item->AMOUNT;
            }
        }

        foreach ($expensesResult as $item) {
            if ($item->TAXABLE) {
                switch ($taxID) {
                    case  12:
                        $amount +=  ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $expenses_amount +=  ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 13:
                        $amount += ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $expenses_amount += ($item->TAX_AMOUNT +  $item->TAXABLE_AMOUNT);
                        $taxAmount += $item->TAX_AMOUNT;
                        $taxableAmount += $item->TAXABLE_AMOUNT;
                        break;
                    case 14:
                        $amount += $item->AMOUNT;
                        $expenses_amount += $item->AMOUNT;
                        $taxAmount += 0;
                        $nonTaxableAmount += $item->AMOUNT;
                        break;
                    default:
                        break;
                }
            } else {
                $amount += $item->AMOUNT;
                $expenses_amount += $item->AMOUNT;
                $nonTaxableAmount  += $item->AMOUNT;
            }
        }

        $getResult = array(
            [
                'AMOUNT'                => $amount,
                'TAX_AMOUNT'            => $taxAmount,
                'TAXABLE_AMOUNT'        => $taxableAmount,
                'NONTAXABLE_AMOUNT'     => $nonTaxableAmount,
                'ITEM_AMOUNT'           => $item_amount,
                'EXPENSES_AMOUNT'       => $expenses_amount,
                'BALANCE_DUE'           => $amount - $pay
            ]
        );



        return $getResult;
    }
}
