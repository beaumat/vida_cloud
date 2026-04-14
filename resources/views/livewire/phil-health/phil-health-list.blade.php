<?php
use App\Services\UserServices;
?>

<div class="content-wrapper">
    @php
        use Carbon\Carbon;
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsphic') }}"> PhilHealth </a></h5>
                </div>
                <div class="col-sm-6 text-right">
                    @livewire('PhilHealth.QuickCreate', ['LOCATION_ID' => $locationid])
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
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="mt-0">
                                                        <label class="text-sm">From :</label>
                                                        <input type="date" wire:model.live.debounce.150ms='ADMITTED'
                                                            class="w-100 form-control form-control-sm" />
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mt-0">
                                                        <label class="text-sm">To :</label>
                                                        <input type="date" wire:model.live.debounce='DISCHARGED'
                                                            class="w-100 form-control form-control-sm" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm" wire:click='locationClick()'
                                                    wire:confirm='Are you use update all paid'>Location:</label>
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
                                        <th>SOA No.</th>
                                        <th>Created On</th>
                                        <th>Elapsed </th>
                                        <th class="text-left">Claim No.</th>
                                        <th clsss="bg-success active">LHIO Date</th>
                                        <th clsss="bg-success active">LHIO No.</th>
                                        <th class="col-2">Patients</th>
                                        <th class="text-center">Admitted</th>
                                        <th class="text-center">Discharges</th>
                                        <th class="text-center">#Day<br />Transmitted</th>
                                        <th class="text-center">#Trmt. </th>
                                        <th class='text-right'>FC Amt.</th>
                                        <th class="text-right">Paid Amt.</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th class="text-center col-2">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs" wire:loading.attr='hidden'>
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary">
                                                    {{ $list->CODE }}
                                                </a>
                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ Carbon::parse($list->RECORDED_ON)->diffForHumans() }} </td>
                                            <td class="text-left">
                                                {{-- @if ($list->CLAIM_NO) --}}
                                                    {{ $list->CLAIM_NO }}
                                                {{-- @else
                                                    @if ($editID == $list->ID)
                                                        <input type="input" wire:model='editClaimNo'
                                                            class="text-xs w-50" maxlength="10" />
                                                        <button title="Save Claim No." type="button"
                                                            wire:click='updateCM' class="btn btn-xs btn-success">
                                                            <i class="fa fa-save" aria-hidden="true"></i>
                                                        </button>
                                                        <button title="Cancel Edit" type="button"
                                                            wire:click='cancelCM()' class="btn btn-xs btn-secondary">
                                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        <button title="Edit Claim No." type="button"
                                                            wire:click='editCM({{ $list->ID }})'
                                                            class="btn btn-xs btn-warning">
                                                            <i class="fa fa-wrench" aria-hidden="true"></i>
                                                        </button>
                                                    @endif
                                                @endif --}}

                                            </td>
                                            <td class="text-left">
                                                @if ($list->AR_DATE)
                                                    {{ date('m/d/Y', strtotime($list->AR_DATE)) }}
                                                @endif
                                            </td>
                                            <td class="text-left">
                                                {{ $list->AR_NO }}
                                            </td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td
                                                class="text-center  @if ($list->AR_DATE) text-success @else text-danger @endif">
                                                {{ Carbon::parse($list->DATE_ADMITTED)->diffInDays($list->AR_DATE ? $list->AR_DATE : Carbon::now()) }}
                                            </td>
                                            <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                            <td class="text-right"> {{ number_format($list->P1_TOTAL, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->PAYMENT_AMOUNT, 2) }}</td>
                                            <td
                                                class="@if ($list->STATUS == 'Paid') text-success @else text-danger @endif ">
                                                {{ $list->STATUS }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center">
                                                <a title="View Details"
                                                    href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                @if (UserServices::GetUserRightAccess('patient.philhealth.print'))
                                                    <span class="btn btn-xs btn-primary" type="button"
                                                        title="Active Print" wire:click='print({{ $list->ID }})'>
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </span>
                                                @else
                                                    <span class="btn btn-xs btn-secondary" type="button"
                                                        title="Active Print">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </span>
                                                @endif
                                                @can('patient.philhealth.delete')
                                                    @if ($list->PAYMENT_AMOUNT == 0 && $list->IN_PROGRESS == false)
                                                        <span title="Active delete button" type="button"
                                                            wire:click='delete({{ $list->ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn btn-xs btn-danger">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </span>
                                                    @else
                                                        <span title="Disabled delete button" type="button"
                                                            class="btn btn-xs btn-secondary">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </span>
                                                    @endif
                                                @endcan
                                                <button type="button" title="LHIO Form"
                                                    class="btn btn-success active btn-xs"
                                                    wire:click='getARForm({{ $list->ID }})'>
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </button>

                                            </td>
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
                <div class="col-6 col-md-6" wire:loading.attr='hidden'>
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
    @livewire('PhilHealth.ArForm')
    @livewire('PhilHealth.PrintModal')
</div>
