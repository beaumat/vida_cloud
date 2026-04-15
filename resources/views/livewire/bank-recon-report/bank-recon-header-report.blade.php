<div>
    <div class="header">
        <div class="company-name">{{ $COMPANY_NAME }}</div>
        <div>{{ $COMPANY_ADDRESS }}</div>
        <div class="report-title">BANK RECONCILIATION REPORT</div>
    </div>
    <!-- RECONCILIATION INFO -->
    <table class="info-table">
        <tr>
            <td><strong>Bank Name:</strong> {{ $BANK_NAME }}</td>
            <td><strong>Account No:</strong>{{ $ACCOUNT_NO }}</td>
        </tr>
        <tr>
            <td><strong>Statement Date:</strong> {{ date('m/d/Y', strtotime($BANK_STATEMENT_DATE))  }}</td>
            <td><strong>Reconciliation No:</strong> {{ $CODE }}</td>
        </tr>
        <tr>
            <td><strong>Location:</strong> {{ $LOCATION_NAME }}</td>
            <td><strong>Prepared By:</strong> {{ $PREPARED_BY_NAME }}</td>
        </tr>
    </table>
</div>