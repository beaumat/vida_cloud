<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsaccountingtransaction_details_report') }}">
                            Account Transaction Report
                        </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                               <button class="btn btn-success btn-xs w-100" wire:click='export()'
                                wire:loading.attr='disabled'>Export</button>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="bg-sky h1">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th class="col-4">Name/Details - Particular</th>
                                <th>Reference </th>
                                <th>Location</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                                <th class="text-right">Running Balance</th>
                                <th class="text-right">Gross</th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @php
                                $BALANCE = 0;
                                $TEMP_ACCOUNT = '';
                                $TEMP_DEBIT = 0;
                                $TEMP_CREDIT = 0;
                                $TOTAL_CREDIT = 0;
                                $TOTAL_DEBIT = 0;
                            @endphp
                            @foreach ($dataList as $list)
                                @if ($TEMP_ACCOUNT == '')
                                    @php
                                        $BALANCE = $list->BALANCE ?? 0;
                                        $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                                        $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                                        $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="text-primary font-weight-bold text-md">{{ $TEMP_ACCOUNT }}</td>
                                    </tr>
                                @else
                                    @if ($TEMP_ACCOUNT != $list->ACCOUNT_TITLE)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right text-info ">
                                                <div class="border-top border-secondary">
                                                    {{ $TEMP_DEBIT > 0 ? number_format($TEMP_DEBIT, 2) : '0.00' }}
                                                </div>
                                            </td>
                                            <td class="text-right text-info top-line">
                                                <div class="border-top border-secondary">
                                                    {{ $TEMP_CREDIT > 0 ? number_format($TEMP_CREDIT, 2) : '0.00' }}
                                                </div>
                                            </td>
                                            <td class="text-right text-danger top-line">
                                                <div class="border-top border-secondary">
                                                    {{ $BALANCE > 0 ? number_format($BALANCE, 2) : '(' . number_format(str_replace('-', '', $BALANCE), 2) . ')' }}
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $BALANCE = $list->BALANCE ?? 0;
                                            $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                                            $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                                            $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-primary font-weight-bold text-md">{{ $TEMP_ACCOUNT }}</td>
                                        </tr>
                                    @else
                                        @php
                                            $TEMP_DEBIT += (float) $list->DEBIT ?? 0;
                                            $TEMP_CREDIT += (float) $list->CREDIT ?? 0;
                                        @endphp
                                    @endif
                                @endif

                                @php
                                    if ($list->DEBIT > 0) {
                                        $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT;
                                    }

                                    if ($list->CREDIT > 0) {
                                        $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT;
                                    }

                                    if ($list->DEBIT > 0) {
                                        $BALANCE = $BALANCE + $list->DEBIT ?? 0;
                                    } else {
                                        $BALANCE = $BALANCE - $list->CREDIT ?? 0;
                                    }
                                @endphp

                                <tr>
                                    <td>
                                        @if ($list->JOURNAL_NO != 'F')
                                            {{ date('m/d/Y', strtotime($list->DATE)) }}
                                        @endif
                                    </td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>{{ $list->TX_NAME }} - <i class="text-primary">{{ $list->TX_NOTES }}</i></td>
                                    <td>
                                        <span class="text-primary" type="button"
                                            wire:click='openDetails({{ $list->JOURNAL_NO }})'>
                                            {{ $list->TX_CODE }}
                                        </span>
                                    </td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td class="text-right">{{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }} </td>
                                    <td
                                        class="font-weight-bold text-right   @if ($list->JOURNAL_NO == 'F') text-info @endif">
                                        {{ $BALANCE > 0 ? number_format($BALANCE, 2) : '(' . number_format(str_replace('-', '', $BALANCE), 2) . ')' }}
                                    </td>
                                    <td class="text-right text-primary">
                                        @if ($list->JOURNAL_NO != 'F')
                                            {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '(' . number_format($list->CREDIT, 2) . ')' }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right text-info">
                                    <div class="border-top border-secondary">
                                        {{ $TEMP_DEBIT > 0 ? number_format($TEMP_DEBIT, 2) : '0.00' }} </div>
                                </td>
                                <td class="text-right text-info">
                                    <div class="border-top border-secondary">
                                        {{ $TEMP_CREDIT > 0 ? number_format($TEMP_CREDIT, 2) : '0.00' }} </div>
                                </td>
                                <td class="text-right text-danger">
                                    <div class="border-top border-secondary">
                                        {{ $BALANCE > 0 ? number_format($BALANCE, 2) : '(' . number_format(str_replace('-', '', $BALANCE), 2) . ')' }}

                                    </div>
                                </td>
                                <td>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
