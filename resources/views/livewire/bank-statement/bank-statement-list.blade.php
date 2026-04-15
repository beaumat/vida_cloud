<?php
use App\Services\UserServices;
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('bankingbank_statement') }}"> Bank Statement </a></h5>
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

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1">DATE FROM</th>
                                        <th class="col-1">DATE TO</th>
                                        <th class="col-2">DESCRIPTION</th>
                                        <th class="col-2">BANK ACCOUNT</th>
                                        <th class="col-1">FILE TYPE</th>
                                        <th>NOTES</th>
                                        <th class="col-1">RECONCILE DATE</th>
                                        <th class="col-1">RECONCILE STATUS</th>
                                        <th class="text-center col-1 bg-success">
                                            @can('banking.bank-statement.create')
                                                <a href="{{ route('bankingbank_statement_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            @endcan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE_FROM)) }}</td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE_TO)) }}</td>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td>{{ $list->BANK_NAME }}</td>
                                            <td>{{ $list->FILE_TYPE }}</td>
                                            <td>{{ $list->NOTES }}</td>
                                            <td>{{ $list->RECON_DATE }}</td>
                                            <td>
                                                @if ($list->RECON_STATUS > 0)
                                                    <span class="badge text-sm bg-success w-100"> Cleared </span>
                                                @else
                                                    <span class="badge text-sm bg-secondary w-100"> Uncleared </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-6">

                                                        <a href="{{ route('bankingbank_statement_edit', ['id' => $list->ID]) }}"
                                                            class="btn btn-xs btn-info w-100">
                                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        @if (UserServices::GetUserRightAccess('banking.bank-statement.delete') && $list->STATUS_ID == 0)
                                                            <button wire:click='delete({{ $list->ID }})'
                                                                wire:confirm="Are you sure you want to delete this?"
                                                                class="btn btn-xs btn-danger w-100">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-xs btn-secondary w-100">
                                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>




                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
