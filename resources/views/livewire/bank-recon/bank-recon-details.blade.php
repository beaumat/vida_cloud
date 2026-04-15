<div class="container-fluid">
    <div class="row mt-2">
        <div class="col-6 text-right">
            Service Charge :
        </div>
        <div class="col-6 text-right">
            <label class="text-danger">{{ number_format($SC_RATE * -1, 2) }}</label>
        </div>
        <div class="col-6 text-right">
            Interest Earning :
        </div>
        <div class="col-6 text-right">
            <label class="text-success">{{ number_format($IE_RATE, 2) }}</label>
        </div>
        <div class="col-6 text-right">
            Ending Balance :
        </div>
        <div class="col-6 text-right">
            <label class="text-info">{{ number_format($ENDING_BALANCE, 2) }}</label>
        </div>
        <div class="col-6 text-right">
            Cleared Balance :
        </div>
        <div class="col-6 text-right">
            <label class="text-orange">{{ number_format($CLEARED_BALANCE, 2) }}</label>
        </div>
        <div class="col-6 text-right">
            Difference :
        </div>
        <div class="col-6 text-right">
            <label class="text-danger">{{ number_format($DIFFERENCE_BALANCE, 2) }}</label>
        </div>
    </div>
</div>
