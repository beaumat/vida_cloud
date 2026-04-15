<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancefinancialcoa') }}"> Chart Of Accounts </a>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mt-0">
                                        <label class="text-sm">Search:</label>
                                        <input type="text" wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm" placeholder="Search" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-sm">Display Inactive : <input type="checkbox"
                                            wire:model.live='showAll' name="showAll" /></div>
                                    <div class="text-sm">Show Ending Balance : <input type="checkbox"
                                            wire:model.live='showBalance' name="showBalance" /></div>

                                </div>
                                <div class="col-md-3">
                                    <div class="mt-0">
                                        <label class="text-sm" wire:click='RecalculateAllAccount()'
                                            wire:confirm='Just Calculate Account base in this location?'>Location:</label>
                                        <select
                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                            name="location" wire:model.live='locationid'
                                            class="form-control form-control-sm">
                                            <option value="0"> All Location</option>
                                            @foreach ($locationList as $item)
                                                <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class='col-1'>Code</th>
                                        <th>Name</th>
                                        <th class="col-1">Type</th>
                                        <th>Group of Account</th>
                                        <th>Back Account No.</th>
                                        <th class="col-1 text-center">Inactive</th>
                                        @if ($showBalance)
                                            <th class="text-right">Ending Balance</th>
                                        @endif
                                        <th class="col-2 text-center">
                                            <a href="{{ route('maintenancefinancialcoa_create') }}"
                                                class="text-white btn btn-xs btn-success w-100">
                                                <i class="fas fa-plus"></i> New
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs" wire:loading.attr='hidden'>
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> <a
                                                    href="{{ route('maintenancefinancialcoa_edit', ['id' => $list->ID]) }}">{{ $list->TAG }}
                                                </a> </td>
                                            <td> {{ $list->NAME }} </td>
                                            <td> {{ $list->ACCOUNT_TYPE }} </td>
                                            <td> {{ $list->GROUP_ACCOUNT }} </td>
                                            <td> {{ $list->BANK_ACCOUNT_NO }} </td>

                                            <td class="text-center">

                                                @if ($list->INACTIVE)
                                                    <strong type='button'
                                                        wire:click='accountInactive({{ $list->ID }},0)'
                                                        class="text-danger"> Yes </strong>
                                                @else
                                                    <strong type='button'
                                                        wire:click='accountInactive({{ $list->ID }},1)'
                                                        class="text-primary">No</strong>
                                                @endif
                                            </td>
                                            @if ($showBalance)
                                                <td class="text-info text-right">
                                                    <a target="_blank"
                                                        href="{{ route('maintenancefinancialcoa_balance', ['id' => $list->ID, 'locationid' => $locationid]) }}">
                                                        {{ number_format($list->ENDING_BALANCE, 2) }}</a>
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                <a href="{{ route('maintenancefinancialcoa_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <button wire:click='delete({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="btn btn-xs btn-danger">
                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
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
                <div class="col-md-6" wire:loading.attr='hidden'>
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
