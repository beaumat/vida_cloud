<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h5 class="m-0">
                        <a href="{{ route('maintenanceothersitem-active-list') }}">Item Inventory </a> |
                        {{ $ITEM_NAME }}
                        <div wire:loading.delay>
                            <span class='spinner'></span>
                        </div>
                        <button type="button" wire:click='exportData()' wire:loading.attr='disabled'
                            class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                        </button>
                    </h5>
                </div><!-- /.col -->
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <input type="date" class="text-sm" wire:model='DATE_ON'
                                wire:loading.attr='disabled'>
                            <button type="button" wire:confirm='Are you sure'
                                wire:click='reCountItem()'
                                wire:loading.attr='disabled' class="btn btn-primary btn-sm">
                                <i class="fa fa-refresh" aria-hidden="true"></i> Recount
                            </button>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="tableContainer" style="max-height:70vh; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky sticky-header">
                                        <tr>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Ref No.</th>
                                            <th class="col-2">Name/Details</th>
                                            <th class="col-4">Notes</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Ending Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($dataList as $list)
                                            <tr>
                                                <td>{{ $list->TYPE }}</td>
                                                <td wire:confirm='Are you sure'
                                                    wire:click="refreshOnHand('{{ $list->SOURCE_REF_ID }}','{{ $list->SOURCE_REF_TYPE }}', '{{ $list->LOCATION_ID }}')">
                                                    {{ date('m/d/Y', strtotime($list->SOURCE_REF_DATE)) }}</td>
                                                <td>{{ $list->TX_CODE }}</td>
                                                <td>{{ $list->CONTACT_NAME }}</td>
                                                <td>{{ $list->TX_NOTES }}</td>
                                                <td
                                                    class="text-right @if ($list->QUANTITY > 0) text-success @else text-danger @endif">
                                                    @if ($list->QUANTITY > 0)
                                                        +
                                                    @endif
                                                    {{ number_format($list->QUANTITY, 1) }}
                                                </td>
                                                <td class="text-right text-primary font-weight-bold ">
                                                    {{ number_format($list->ENDING_QUANTITY, 1) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 col-md-12">
                                        {{ $dataList->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- @script
        <script>
            $wire.on('scrollToBottom', (eventData) => {
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer) {
                    tableContainer.scrollTop = tableContainer.scrollHeight;
                }
            });
        </script>
    @endscript --}}

</div>
