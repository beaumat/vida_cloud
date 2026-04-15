<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancefinancialcoa') }}"> Chart Of Accounts
                        </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <h6 class="text-info"> {{ $ACCOUNT_NAME }} : From {{ $LOCATION_NAME }}</h6>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-3">

                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Date</th>
                                        <th>Journal No.</th>
                                        <th>Source</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th wire:click='balanceUpdate()' wire:confirm='Are you use?'>

                                            Ending Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->JOURNAL_NO }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td class="text-right">
                                                @if ($list->DEBIT > 0)
                                                    {{ number_format($list->DEBIT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($list->CREDIT > 0)
                                                    {{ number_format($list->CREDIT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right"> {{ number_format($list->ENDING_BALANCE, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
