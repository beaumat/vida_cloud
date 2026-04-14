<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceinventoryprice_level') }}"> Price Level </a>
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
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">

                                        <div class="col-md-2">
                                            <livewire:text-input name="CODE" titleName="Code" wire:model='CODE' :isDisabled="false" />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="DESCRIPTION" titleName="Description" :isDisabled="false"
                                                wire:model='DESCRIPTION'>
                                        </div>
                                        <div class="col-md-4"
                                            @if ($ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <livewire:select-option name="TYPE" :options="$priceLevelType" :zero="false" :isDisabled="false"
                                                titleName="Type" wire:model.live='TYPE' :key="$priceLevelType->pluck('ID')->join('_')">
                                        </div>
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive" :isDisabled="false"
                                                wire:model='INACTIVE'>
                                        </div>


                                    </div>
                                </div>
                                <div class="form-group" style="display: {{ $TYPE === 0 ? 'block' : 'none' }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:number-input name="RATE" titleName="Rate" :isDisabled="false"
                                                        wire:model='RATE'>
                                                </div>
                                                <div class="col-md-8">
                                                    <livewire:select-option name="ITEM_GROUP_ID" :options="$itemGroup" :isDisabled="false"
                                                        :zero="true" titleName="Item Group"
                                                        wire:model='ITEM_GROUP_ID' :key="$itemGroup->pluck('ID')->join('_')">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit"
                                            class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('maintenanceinventoryprice_level_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-12 " style="display: {{ $TYPE === 1 ? 'block' : 'none' }}">
                    <div class="row"
                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form wire:submit.prevent='saveItem'>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <livewire:select-option name="ITEM_ID" :options="$itemList" :isDisabled="false"
                                                    :zero="true" titleName="Item" wire:model='ITEM_ID'
                                                    :key="$itemList->pluck('ID')->join('_')">

                                            </div>
                                            <div class="col-md-2">

                                                <livewire:number-input name="CUSTOM_PRICE" titleName="Custom Price" :isDisabled="false"
                                                    wire:model='CUSTOM_PRICE'>


                                            </div>
                                            <div class="text-right col-md-2">
                                                <label class="mt-2"> <br /></label>
                                                <button class="text-white btn bg-sky btn-sm w-100">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="mt-3 mb-1 col-md-12">
                                            <input type="text" wire:model.live.debounce.150ms='search'
                                                class="w-100 form-control form-control-sm bg-light"
                                                placeholder="Search" />
                                        </div>
                                    </div>
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="text-sm bg-blue-sky">
                                            <tr>
                                                <th>CODE</th>
                                                <th>DESCRIPTION</th>
                                                <th class="text-right col-1">Unit Price</th>
                                                <th class="text-right col-2">Custom Price</th>
                                                <th class="text-center col-2">
                                                    ACTION
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm">
                                            @foreach ($priceLevelLines as $list)
                                                <tr>
                                                    <td> {{ $list->CODE }}</td>
                                                    <td> {{ $list->DESCRIPTION }}</td>
                                                    <td class="text-right"> {{ number_format($list->RATE, 2) }}</td>
                                                    <td class="text-right">

                                                        @if ($editItemId === $list->ID)
                                                            <input type="number" wire:model="newCustomPrice"
                                                                class="form-control form-control-sm">
                                                        @else
                                                            {{ number_format($list->CUSTOM_PRICE, 2) }}
                                                        @endif
                                                    </td>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($editItemId === $list->ID)
                                                            <button title="Update" id="updatebtn"
                                                                wire:click="updateItem({{ $list->ID }})"
                                                                class="text-success btn btn-sm btn-link">
                                                                <i class="fas fa-check" aria-hidden="true"></i>
                                                            </button>
                                                            <button title="Cancel" id="cancelbtn" href="#"
                                                                wire:click="cancelItem()"
                                                                class="text-warning btn btn-sm btn-link">
                                                                <i class="fas fa-ban" aria-hidden="true"></i>
                                                            </button>
                                                        @else
                                                            <button title="Edit" id="editbtn"
                                                                wire:click='editItem({{ $list->ID . ',' . $list->CUSTOM_PRICE }})'
                                                                class="text-info btn btn-sm btn-link">
                                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                                            </button>
                                                            <button title="Delete" id="deletebtn"
                                                                wire:click='deleteItem({{ $list->ID }})'
                                                                wire:confirm="Are you sure you want to delete this?"
                                                                class="text-danger btn btn-sm btn-link">
                                                                <i class="fas fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
