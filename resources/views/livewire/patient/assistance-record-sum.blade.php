<div class="sticky-top " style="max-height: 60vh; overflow-y: auto;">
    <div class="card">
        <div class="card-header">
            <label class="card-title text-sm text-primary">{{ __('All Assistance Record') }}</label>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered table-hover">
                <thead class="bg-sky text-xs">
                    <tr>

                        <th>GL</th>
                        <th>GL Date</th>
                        <th>GL No.</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Usage</th>
                        <th class="text-right col-1 bg-danger">Bal. <i wire:click='reload()' type="button"
                                class="fa fa-refresh" aria-hidden="true"></th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($dataList as $list)
                        @php
                            $TOTAL = $TOTAL + $list->AMOUNT;
                            $PAY = $PAY + $list->AMOUNT_APPLIED;
                        @endphp
                        <tr>
                            <td>{{ $list->METHOD }}</td>
                            <td>{{ $list->TRANS_DATE ? date('m/d/Y', strtotime($list->TRANS_DATE)) : '' }}</td>
                            <td>{{ $list->TRANS_CODE }}</td>
                            <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                            <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                            <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right font-weight-bold">{{ number_format($TOTAL, 2) }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($PAY, 2) }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($TOTAL - $PAY, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
