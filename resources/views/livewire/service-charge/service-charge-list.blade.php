<?php
use App\Services\UserServices;
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsservice_charges') }}"> Service Charges </a></h5>
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
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mt-0">
                                                <label class="text-sm"> <a href="#"
                                                        wire:click='refreshList()'>Search:</a> </label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class ="col-md-2">
                                            <label class="text-sm">Date:</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model.live='DATE_FROM' />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="text-sm">Use Nurse Remarks:</label>
                                            <input type="checkbox" wire:model.live='nurseMark' name="nurseMark" />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
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
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1">Ref No.</th>
                                        <th class="col-1">Date</th>
                                        <th class="col-4">Patients</th>
                                        <th class="col-1">Location</th>
                                        <th class="col-1">Amount</th>
                                        <th class="col-1">Balance</th>
                                        @if ($nurseMark)
                                            <th class="text-center">C</th>
                                            <th class="text-center">S</th>
                                            <th class="text-center">T</th>
                                        @else
                                            <th class="text-center">Status</th>
                                        @endif

                                        @can('patient.service-charges.create')
                                            <th class="text-center col-2 bg-success">
                                                <a href="{{ route('patientsservice_charges_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="text-xs" wire:loading.attr='hidden'>
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary">
                                                    {{ $list->CODE }}
                                                </a>
                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->BALANCE_DUE, 2) }}</td>
                                            @if ($nurseMark)
                                                <td
                                                    class="text-center @if ($list->got_charge) bg-success @endif">
                                                    @if ($list->got_charge)
                                                        <i class="fa fa-check" aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td
                                                    class="text-center @if ($list->STATUS_ID == 0) bg-warning @elseif ($list->STATUS_ID == 2) bg-primary  @elseif ($list->STATUS_ID == 11) bg-success @else bg-secondary @endif ">
                                                    {{ substr($list->STATUS, 0, 1) }}</td>
                                                <td
                                                    class="text-center @if ($list->TR_STATUS == 'Draft') bg-warning  @elseif ($list->TR_STATUS == 'Posted') bg-success  @elseif ($list->TR_STATUS == 'Unposted') bg-secondary @else bg-danger @endif ">
                                                    {{ substr($list->TR_STATUS, 0, 1) }}</td>
                                            @else
                                                <td
                                                    class="text-center @if ($list->STATUS_ID == 0) bg-warning @elseif ($list->STATUS_ID == 2) bg-primary  @elseif ($list->STATUS_ID == 11) bg-success @else bg-secondary @endif ">
                                                    {{ substr($list->STATUS, 0, 1) }}</td>
                                            @endif
                                            @can('patient.service-charges.create')
                                                <td class="text-center">
                                                    <a type="button" title="View details"
                                                        href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                                        class="btn btn-xs btn-info">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </a>
                                                    @if ($list->AMOUNT == 0 || ($list->STATUS_ID == 0 && UserServices::GetUserRightAccess('patient.service-charges.delete')))
                                                        <button type="button" title="Delete active"
                                                            wire:click='delete({{ $list->ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" title="Delete disabled"
                                                            class="btn btn-xs btn-secondary">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div wire:loading.delay>
                                <span class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true"></span>
                                Loading...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6"  wire:loading.attr='hidden'>
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
