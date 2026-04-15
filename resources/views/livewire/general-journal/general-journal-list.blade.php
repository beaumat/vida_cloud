<?php
use App\Services\UserServices;
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('companygeneral_journal') }}"> General Journal </a></h5>
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
                                        <div class="col-md-9">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                        <th>Ref No.</th>
                                        <th>Date</th>
                                        <th class="col-2">Contact Name</th>
                                        <th class="col-3">Notes</th>
                                        <th class="col-1 text-center">Adjustment Entry</th>
                                        <th class="col-1">Location</th>
                                        <th class="col-1">Status</th>
                                        <th class="text-center bg-success col-1">
                                            @can('company.general-journal.create')
                                                <a href="{{ route('companygeneral_journal_create') }}"
                                                    class="text-white btn btn-xs btn-success w-100">
                                                    <i class="fas fa-plus"></i></a>
                                            @endcan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a
                                                    href="{{ route('companygeneral_journal_edit', ['id' => $list->ID]) }}">
                                                    {{ $list->CODE }}
                                                </a>
                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->CONTACT_NAME }}</td>

                                            <td> {{ $list->NOTES }}</td>
                                            <td class="text-center">
                                                @if ($list->ADJUSTING_ENTRY)
                                                    Yes
                                                @endif
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td> {{ $list->STATUS }}</td>
                                            <td class="text-center">

                                                <a title="View"
                                                    href="{{ route('companygeneral_journal_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>

                                                @if (UserServices::GetUserRightAccess('company.general-journal.delete'))
                                                    <button title="delete" wire:click='delete({{ $list->ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-danger btn-xs">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button title="delete" class="btn btn-secondary btn-xs"> <i
                                                            class="fas fa-trash" aria-hidden="true"></i> </button>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
