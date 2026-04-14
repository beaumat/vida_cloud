<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsaccountingtrial_balance_report') }}">
                            Trial Balance Report
                        </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light">
                          <button class="btn btn-success btn-xs" wire:click='export()'>Export</button>
                    </div>
                </div>

                <div class="col-md-6  " style="max-height: 80vh; overflow-y: auto;">

                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <table class="table table-xl table-bordered table-hover">
                        <thead class="bg-sky">
                            <tr>
                                <th class="col-6 text-left">Account Title</th>
                                <th class="col-3 text-right">Debit</th>
                                <th class="col-3 text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @php
                                $TOTAL_DEBIT = 0.0;
                                $TOTAL_CREDIT = 0.0;
                            @endphp
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->ACCOUNT_TITLE }}</td>
                                    @php
                                        $TOTAL_DEBIT = $TOTAL_DEBIT + $list->TX_DEBIT;
                                        $TOTAL_CREDIT = $TOTAL_CREDIT + $list->TX_CREDIT;
                                    @endphp
                                    <td class="text-right">
                                        {{ $list->TX_DEBIT != 0 ? number_format($list->TX_DEBIT, 2) : '' }}</td>
                                    <td class="text-right">
                                        {{ $list->TX_CREDIT != 0 ? number_format($list->TX_CREDIT, 2) : '' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td> </td>
                                <td class="text-right font-weight-bold text-primary">
                                    {{ number_format($TOTAL_DEBIT, 2) }}
                                </td>
                                <td class="text-right font-weight-bold text-primary">
                                    {{ number_format($TOTAL_CREDIT, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
