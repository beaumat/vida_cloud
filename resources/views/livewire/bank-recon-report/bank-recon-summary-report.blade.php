<div>
    <!-- SUMMARY SECTION -->
    <h4>RECONCILIATION SUMMARY</h4>

    <table class="summary-table">
        <tr>
            <td>Beginning Balance per Book</td>
            <td class="text-right">{{ number_format($BEGINNING_BALANCE, 2) }}</td>
        </tr>
        <tr>
            <td>Add: Deposits in Transit</td>
            <td class="text-right">{{ number_format($DEPOSIT_IN_TRANSIT, 2) }}</td>
        </tr>
        <tr>
            <td>Add: Interest Earned</td>
            <td class="text-right">{{ number_format($INTEREST_EARNED, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Adjusted Book Balance</strong></td>
            <td class="text-right"><strong>{{ number_format($BOOK_BALANCE, 2) }}</strong></td>
        </tr>
        <tr>
            <td>Less: Outstanding Checks</td>
            <td class="text-right">{{ number_format($OUTSTANDING_CHECK, 2) }}</td>
        </tr>
        <tr>
            <td>Less: Bank Service Charges</td>
            <td class="text-right">{{ number_format($SERVICE_CHARGES,2) }}</td>
        </tr>
        <tr>
            <td><strong>Adjusted Bank Balance</strong></td>
            <td class="text-right"><strong>{{ number_format($BANK_BALANCE, 2) }}</strong></td>
        </tr>
        <tr>
            <td>Ending Balance per Bank Statement</td>
            <td class="text-right">{{ number_format($ENDING_BALANCE, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Difference</strong></td>
            <td class="text-right"><strong>  {{ number_format($DIFFERENCE,2)  }}</strong></td>
        </tr>
        <tr>
            <td><strong>Status</strong></td>
            <td class="text-right status-balanced">{{ $STATUS }}</td>
        </tr>
    </table>
</div>