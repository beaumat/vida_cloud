<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsaccountingtransaction_journal_report') }}">
                            Transaction Journal Unposted
                        </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    {{-- <button class="btn btn-sm btn-primary" wire:click='cleanData'>Clean</button> --}}
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <table class="table table-sm table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <td>Jrnl#</td>
                                <td>Date</td>
                                <td class="col-1">Type</td>
                                <td class="col-1">Ref #</td>
                                <td class="col-2">Name</td>
                                <td class="col-1">Location</td>
                                <td class="col-2">Account Title</td>
                                <td class="col-3">Particular</td>
                                <th class="col-1 text-right">Debit</th>
                                <th class="col-1 text-right">Credit</th>
                                <th class="col-1">Location</th>
                            </tr>
                        </thead>
                        @php
                            $TOTAL_DEBIT = 0;
                            $TOTAL_CREDIT = 0;
                        @endphp
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>

                                        {{ $list->JOURNAL_NO }}

                                    </td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>
                                        <span class="text-primary" type="button"
                                            wire:click='openDetails({{ $list->JOURNAL_NO }})'>
                                            {{ $list->TX_CODE }}</span>
                                    </td>
                                    <td>{{ $list->TX_NAME }}</td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td>{{ $list->ACCOUNT_TITLE }}</td>
                                    <td>{{ $list->TX_NOTES }}</td>
                                    <td class="text-right">
                                        {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                        @php
                                            if ($list->DEBIT > 0) {
                                                $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT ?? 0;
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-right">
                                        {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
                                        @php
                                            if ($list->CREDIT > 0) {
                                                $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT ?? 0;
                                            }
                                        @endphp
                                    </td>
                                    <td> {{ $list->LOCATION }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right font-weight-bold font-primary">
                                    @if ($TOTAL_DEBIT)
                                        {{ number_format($TOTAL_DEBIT, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td class="text-right font-weight-bold font-primary">
                                    @if ($TOTAL_CREDIT)
                                        {{ number_format($TOTAL_CREDIT, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
    {{-- <script>
        window.open("{{ $url }}", "_blank");
    </script> --}}
</div>
