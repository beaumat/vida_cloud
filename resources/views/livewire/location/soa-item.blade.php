<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> {{ $LOCATION_NAME }} : Soa
                            Item </a></h5>
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
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" wire:model.live.debounce.150ms='search' class="w-100 text-xs"
                                        placeholder="Search" />
                                </div>
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-3">
                                    <label>Copy To Location</label>
                                    <select name="tolcation" wire:model='TO_LOCATION_ID'>
                                        <option value="0">&nbsp;</option>
                                        @foreach ($locationList as $list)
                                            <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-xs btn-warning" wire:click='CopyMode()'>Copy</button>
                                </div>
                            </div>
                            <table class="table table-xs table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="">Type</th>
                                        <th class="col-1">Line #</th>
                                        <th class="col-1">Item </th>
                                        <th class="col-1">Unit </th>
                                        <th class="col-1">Rate </th>
                                        <th class="">Brand</th>
                                        <th class="">Dosage</th>
                                        <th class="">Route</th>
                                        <th class="">Frequency</th>
                                        <th class=" text-center">Is Actual</th>
                                        <th class=" text-center">SC Base</th>
                                        <th class=" text-center">SOA Base</th>
                                        <th class="col-1 text-center">Group</th>
                                        <th class=" text-left">Generic Name</th>
                                        <th class="text-center">Fix Qty</th>
                                        <th class="text-center">Ctrl A</th>
                                        <th class="text-center">Ctrl B</th>
                                        <th class="text-center">Hide</th>
                                        <th class="col-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                @if ($list->ID == $editid)
                                                    <select name="editTYPE{{ $list->ID }}" class="w-100"
                                                        wire:model='editTYPE'>
                                                        @foreach ($typeList as $dataList)
                                                            <option value="{{ $dataList->ID }}">
                                                                {{ $dataList->DESCRIPTION }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    {{ $list->TYPE_NAME }}
                                                @endif
                                            </td>
                                            <td>

                                                @if ($list->ID == $editid)
                                                    <input name="editLINE" type="number" class="w-100"
                                                        wire:model='editLINE' />
                                                @else
                                                    {{ $list->LINE }}
                                                @endif

                                            </td>

                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editITEM_NAME{{ $list->ID }}" type="text"
                                                        class="w-100" wire:model='editITEM_NAME' />
                                                @else
                                                    {{ $list->ITEM_NAME }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editUNIT_NAME" type="text" class="w-100"
                                                        wire:model='editUNIT_NAME' />
                                                @else
                                                    {{ $list->UNIT_NAME }}
                                                @endif
                                            </td>

                                            <td class="text-right">
                                                @if ($list->ID == $editid)
                                                    <input name="editRATE" step="0.01" type="number" class="w-100"
                                                        wire:model='editRATE' />
                                                @else
                                                    {{ number_format($list->RATE, 2) }}
                                                @endif
                                            </td>


                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editBRAND" type="text" class="w-100"
                                                        wire:model='editBRAND' />
                                                @else
                                                    {{ $list->BRAND }}
                                                @endif
                                            </td>


                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editDOSAGE" type="text" class="w-100"
                                                        wire:model='editDOSAGE' />
                                                @else
                                                    {{ $list->DOSAGE }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editROUTE" type="text" class="w-100"
                                                        wire:model='editROUTE' />
                                                @else
                                                    {{ $list->ROUTE }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($list->ID == $editid)
                                                    <input name="editFREQUENCY" type="text" class="w-100"
                                                        wire:model='editFREQUENCY' />
                                                @else
                                                    {{ $list->FREQUENCY }}
                                                @endif
                                            </td>


                                            <td class="text-center col-1">
                                                @if ($list->ID == $editid)
                                                    <input type="checkbox" class="check-input mt-2"
                                                        wire:model='editACTUAL_BASE' />
                                                @else
                                                    @if ($list->ACTUAL_BASE)
                                                        <button class="btn btn-info btn-xs"
                                                            wire:click='OpenActualBase({{ $list->ID }})'><i
                                                                class="fa fa-list" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="text-center col-1">
                                                @if ($list->ID == $editid)
                                                    <input type="checkbox" class="check-input mt-2"
                                                        wire:model='editSC_BASE' />
                                                @else
                                                    @if ($list->SC_BASE)
                                                        <button class="btn btn-warning btn-xs"
                                                            wire:click='OpenActualBase({{ $list->ID }})'><i
                                                                class="fa fa-list" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center col-1">
                                                @if ($list->ID == $editid)
                                                    <input type="checkbox" class="check-input mt-2"
                                                        wire:model='editSOA_BASE' />
                                                @else
                                                    @if ($list->SOA_BASE)
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="col-1">
                                                @if ($list->ID == $editid)
                                                    <input name="editGROUP_ID" type="number" class="w-100"
                                                        wire:model='editGROUP_ID' />
                                                @else
                                                    {{ $list->GROUP_ID }}
                                                @endif
                                            </td>
                                            <td class="col-1">
                                                @if ($list->ID == $editid)
                                                    <input name="editGENERIC_NAME" type="text" class="w-100"
                                                        wire:model='editGENERIC_NAME' />
                                                @else
                                                    {{ $list->GENERIC_NAME }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->ID == $editid)
                                                    <input name="editFIX_QTY" type="number" class="w-100"
                                                        wire:model='editFIX_QTY' />
                                                @else
                                                    {{ $list->FIX_QTY }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->ID == $editid)
                                                    <input name="editITEM_CONTROL_A" type="checkbox" class="w-100"
                                                        wire:model='editITEM_CONTROL_A' />
                                                @else
                                                    @if ($list->ITEM_CONTROL_A)
                                                        <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->ID == $editid)
                                                    <input name="editITEM_CONTROL_B" type="checkbox" class="w-100"
                                                        wire:model='editITEM_CONTROL_B' />
                                                @else
                                                    @if ($list->ITEM_CONTROL_B)
                                                        <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->ID == $editid)
                                                    <input name="editITEM_HIDE" type="checkbox" class="w-100"
                                                        wire:model='editITEM_HIDE' />
                                                @else
                                                    @if ($list->ITEM_HIDE)
                                                        <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="row">

                                                    @if ($editid === $list->ID)
                                                        <div class="col-6">
                                                            <button name="btnUpdate" title="Update" type="button"
                                                                class="btn btn-xs btn-success w-100"
                                                                wire:click='Update()'>
                                                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-6">

                                                            <button name="btnCanceled" title="Cancel" type="button"
                                                                class="btn btn-xs btn-secondary w-100"
                                                                wire:confirm='Are you sure to cancel?'
                                                                wire:click='Canceled()'>
                                                                <i class="fa fa-ban" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <div class="col-4">
                                                            <button name="btnEdit" title="Edit" type="button"
                                                                class='btn btn-xs btn-primary w-100'
                                                                wire:click='Edit({{ $list->ID }})'><i
                                                                    class="fa fa-pencil-square-o"
                                                                    aria-hidden="true"></i></button>
                                                        </div>

                                                        <div class="col-4">
                                                            @if ($list->INACTIVE == false)
                                                                <button name="btnActive" title="Actve"
                                                                    type="button"
                                                                    class='btn btn-xs btn-success w-100'
                                                                    wire:confirm='Are you sure to Inactive?'
                                                                    wire:click='StatusActive({{ $list->ID }},{{ true }})'>
                                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button name="btnInactive" title="Inactive"
                                                                    type="button"
                                                                    class='btn btn-xs btn-secondary w-100'
                                                                    wire:confirm='Are you sure to Active?'
                                                                    wire:click='StatusActive({{ $list->ID }},{{ false }})'>
                                                                    <i class="fa fa-thumbs-down"
                                                                        aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <div class="col-4">
                                                            <button name="btnDelete" title="Delete" type="button"
                                                                class='btn btn-xs btn-danger w-100'
                                                                wire:confirm='Are you sure to Delete?'
                                                                wire:click='Delete({{ $list->ID }})'>
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <form id="quickForm" wire:submit.prevent='Add'>
                                            <td>
                                                <select class="w-100 text-sm" wire:model='TYPE'>
                                                    <option value="0"></option>
                                                    @foreach ($typeList as $list)
                                                        <option value="{{ $list->ID }}">
                                                            {{ $list->DESCRIPTION }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="w-100" wire:model='LINE' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='ITEM_NAME' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='UNIT_NAME' />
                                            </td>
                                            <td>
                                                <input step="0.01" type="number" class="w-100"
                                                    wire:model='RATE' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='BRAND' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='DOSAGE' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='ROUTE' />
                                            </td>
                                            <td>
                                                <input type="text" class="w-100" wire:model='FREQUENCY' />
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="check-input mt-2"
                                                    wire:model='ACTUAL_BASE' />
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="check-input mt-2"
                                                    wire:model='SC_BASE' />
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" class="check-input mt-2"
                                                    wire:model='SOA_BASE' />
                                            </td>
                                            <td>
                                                <input name="GROUP_ID" type="number" class="w-100"
                                                    wire:model='GROUP_ID' />
                                            </td>
                                            <td>
                                                <input name="GENERIC_NAME" type="text" class="w-100"
                                                    wire:model='GENERIC_NAME' />
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>
                                                <button type="submit" class="btn btn-xs btn-success w-100">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @livewire('Location.SoaItemModal');
</div>
