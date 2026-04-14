<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('customersstatement') }}"> Statement of Account </a></h5>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pb-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-3 row">
                                                <div class="col-2 text-right">NAME :</div>
                                                <div class="col-10 font-weight-bold">{{ $NAME }}</div>
                                                <div class="col-2 text-right">TYPE :</div>
                                                <div class="col-10 font-weight-bold">{{ $CONTACT_TYPE }}</div>

                                            </div>
                                            <div class="col-5 row ">
                                                <div class="col-2 text-right">Prev.Balance :</div>
                                                <div class="col-10">{{ number_format($PREV_BALANCE, 2) }}</div>
                                                <div class="col-2 text-right"> Debit :</div>
                                                <div class="col-10">{{ number_format($TOTAL_DEBIT, 2) }}</div>
                                                <div class="col-2 text-right"> Credit :</div>
                                                <div class="col-10">{{ number_format($TOTAL_CREDIT, 2) }}</div>
                                                <div class="col-2 text-right">Balance Due :</div>
                                                <div class="col-10">{{ number_format($BALANCE_DUE, 2) }}</div>
                                            </div>
                                            <div class="col-4 row" wire:loading.class='loading-form'>
                                                <div class="col-6 row">
                                                    <div class="col-12">
                                                        <livewire:checkbox-input name="USE_AS_OF"
                                                            titleName="Use As of Date" wire:model.live='AS_OF_DATE'
                                                            :isDisabled="false" />
                                                    </div>

                                                    @if ($AS_OF_DATE)
                                                        <div class="col-12">
                                                            <livewire:date-input name="DATE" titleName="As of "
                                                                wire:model.live='dateFrom' :isDisabled="false"
                                                                :vertical="true" />
                                                        </div>
                                                    @else
                                                        <div class="col-12">
                                                            <livewire:date-input name="DATE_FROM" titleName="From"
                                                                wire:model.live='dateFrom' :isDisabled="false"
                                                                :vertical="true" />
                                                            <livewire:date-input name="DATE_TO" titleName=" To"
                                                                wire:model.live='dateTo' :isDisabled="false"
                                                                :vertical="true" />
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    <a target="_blank" class="btn btn-sm btn-info "
                                                        href="{{ route('customersstatement_print', ['id' => $CUSTOMER_ID, 'datefrom' => $dateFrom, 'dateto' => $dateTo]) }}">Print</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1 text-left">Date</th>
                                        <th class="col-1 text-left">Type</th>
                                        <th class="col-1 text-left">Ref#</th>
                                        <th class="col-1 text-left">Location</th>
                                        <th>Description</th>
                                        <th class="col-1">Debit</th>
                                        <th class="col-1">Credit</th>
                                        <th class="col-1">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs" wire:loading.attr='hidden'>
                                    @php
                                        $BALANCE = $PREV_BALANCE;
                                    @endphp
                                    @if ($BALANCE > 0)
                                        <tr>
                                            <td> </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Previous Balance</td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">{{ number_format($BALANCE, 2) }}</td>
                                        </tr>
                                    @endif
                                    @foreach ($dataList as $list)
                                        @php
                                            $BALANCE = $BALANCE + $list->AMT;
                                        @endphp


                                        <tr>
                                            <td> {{ date('M d, Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td>{{ $list->CODE }}</td>
                                            <td>{{ $list->LOCATION }}</td>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td class="text-right">
                                                @if ($list->ENTRY_TYPE == 0)
                                                    {{ number_format($list->AMOUNT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($list->ENTRY_TYPE != 0)
                                                    {{ number_format($list->AMOUNT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($BALANCE, 2) }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div wire:loading.delay>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
