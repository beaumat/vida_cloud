<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                @livewire('PhilHealth.Others', ['PHILHEALTH_ID' => $PHILHEALTH_ID], 'other-cf4')
            </div>
        </div>
    </div>
    <div class="col-md-9">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

        <table class="table table-sm w-100 table-bordered">
            <thead class="text-xs bg-sky ">
                <tr>
                    <th class="col-2">GENERIC NAME</th>
                    <th>QTY</th>
                    <th>DOSAGE</th>
                    <th>ROUTE</th>
                    <th>FREQUENCY</th>
                    <th class="text-right">TOTAL</th>
                    <th class="bg-secondary col-2">GENERIC NAME</th>
                    <th class="bg-secondary">QTY</th>
                    <th class="bg-secondary">DOSAGE</th>
                    <th class="bg-secondary">ROUTE</th>
                    <th class="bg-secondary">FREQUENCY</th>
                    <th class="bg-secondary text-right">TOTAL</th>
                    <th class="col-1 text-center">ACTION</th>
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach ($dataList as $list)
                    <tr class="text-center">
                        <td class="text-left">
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_GENERIC_NAME' />
                            @else
                                {{ $list->GENERIC_NAME }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="number" class="text-sm w-100" wire:model='E_QUANTITY' />
                            @else
                                {{ number_format($list->QUANTITY, 0) }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_DOSSAGE' />
                            @else
                                {{ $list->DOSSAGE }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_ROUTE' />
                            @else
                                {{ $list->ROUTE }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_FREQUENCY' />
                            @else
                                {{ $list->FREQUENCY }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($list->ID === $ID)
                                <input type="number" class="text-sm w-100" wire:model='E_TOTAL_COST' />
                            @else
                                {{ number_format($list->TOTAL_COST, 2) }}
                            @endif

                        </td>
                        <td class="text-left">
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_CONT_GENERIC_NAME' />
                            @else
                                {{ $list->CONT_GENERIC_NAME }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="number" class="text-sm w-100" wire:model='E_CONT_QUANTITY' />
                            @else
                                {{ number_format($list->CONT_QUANTITY, 0) }}
                            @endif

                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_CONT_DOSSAGE' />
                            @else
                                {{ $list->CONT_DOSSAGE }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_CONT_ROUTE' />
                            @else
                                {{ $list->CONT_ROUTE }}
                            @endif
                        </td>
                        <td>
                            @if ($list->ID === $ID)
                                <input type="text" class="text-sm w-100" wire:model='E_CONT_FREQUENCY' />
                            @else
                                {{ $list->CONT_FREQUENCY }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($list->ID === $ID)
                                <input type="number" class="text-sm w-100" wire:model='E_CONT_TOTAL_COST' />
                            @else
                                {{ number_format($list->CONT_TOTAL_COST, 2) }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($list->ID === $ID)
                                <button type="button" class="btn btn-xs btn-success  " wire:click='update()'>
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-danger  " wire:click='canceled()'>
                                    <i class="fa fa-times" aria-hidden="true"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-xs btn-info  "
                                    wire:click='edit({{ $list->ID }})'>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-danger  "
                                    wire:click='delete({{ $list->ID }})'>
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            @endif

                        </td>
                    </tr>
                @endforeach

                <tr wire:loading.attr='disabled'>
                    <td> <input type="text" class="text-sm w-100" wire:model='GENERIC_NAME' /> </td>
                    <td> <input type="number" class="text-sm w-100" wire:model='QUANTITY' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='DOSSAGE' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='ROUTE' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='FREQUENCY' /> </td>
                    <td> <input type="number" class="text-sm w-100" wire:model='TOTAL_COST' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='CONT_GENERIC_NAME' /> </td>
                    <td> <input type="number" class="text-sm w-100" wire:model='CONT_QUANTITY' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='CONT_DOSSAGE' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='CONT_ROUTE' /> </td>
                    <td> <input type="text" class="text-sm w-100" wire:model='CONT_FREQUENCY' /> </td>
                    <td> <input type="number" class="text-sm w-100" wire:model='CONT_TOTAL_COST' /> </td>
                    <td>
                        <button type="button" wire:loading.class='loading-form' wire:click='save()'
                            class="btn btn-xs btn-success w-100">
                            <i class="fa fa-plus" aria-hidden="true"></i> </button>
                    </td>
                </tr>

                @if ($isItemized)
                    @if (!$exists)
                        <tr>
                            <td> <button class="btn btn-xs btn-info" wire:click='AutoFillUp()'
                                    wire:confirm='Are you sure?'>Auto Set</button></td>
                        </tr>
                    @else
                        <tr>

                            <td> <button class="btn btn-xs btn-danger" wire:click='DeleteAll()'
                                    wire:confirm='Are you sure to remove all?'>Remove All</button></td>
                        </tr>
                    @endif

                @endif
            </tbody>
        </table>
    </div>
</div>
