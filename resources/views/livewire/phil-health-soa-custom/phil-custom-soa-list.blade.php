<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancesettingslocation') }}"> Location : Custom Soa </a>
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
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="w-100 form-control form-control-sm" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>

                                        <th>Description</th>
                                        <th class='col-1'>Drug & Med</th>
                                        <th class="col-2">Laboratory & Diagnois</th>
                                        <th class='col-1'>Operating Room Fee</th>
                                        <th class="col-1">Supplies</th>
                                        <th class="col-1">Admin & Other Fee</th>
                                        <th class="col-1">Actual Fee</th>
                                        <th class="col-1">Hide Fee</th>
                                        <th class="col-1 text-center">Inactive</th>
                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('maintenancesettingslocation_custom_soa_create', ['id' => $LOCATION_ID]) }}"
                                                class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td class="text-right">
                                                {{ number_format($list->DRUG_MED, 2) }}
                                            </td>
                                            <td class="text-right">{{ number_format($list->LAB_DIAG, 2) }}</td>
                                            <td class="text-right">{{ number_format($list->OPERATING_ROOM_FEE, 2) }}
                                            </td>
                                            <td class="text-right">{{ number_format($list->SUPPLIES, 2) }}</td>
                                            <td class="text-right">{{ number_format($list->ADMIN_OTHER_FEE, 2) }}</td>
                                            <td class="text-right">{{ number_format($list->ACTUAL_FEE, 2) }}</td>
                                            <td class="text-right">{{ number_format($list->HIDE_FEE, 2) }}</td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    <span>Yes</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenancesettingslocation_custom_soa_edit', ['id' => $LOCATION_ID, 'custom' => $list->ID]) }}"
                                                    class='btn btn-sm btn-info'>Edit</a>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="delete({{ $list->ID }})">Delete</button>
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
    </section>
</div>
