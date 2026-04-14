<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('customersstatement') }}"> Statement of Account </a></h5>
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
                                <div class="col-md-12 row pb-1">
                                    <div class="col-8">
                                        <input type="text" wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm" placeholder="Search" />
                                    </div>
                                    <div class="col-4" wire:loading.class='loading-form'>
                                        <livewire:checkbox-input name="ShowBalance" titleName="Show Balance Only"
                                            wire:model.live='ShowBalanceOnly' :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>

                                        <th class="col-6">Name</th>
                                        <th class="col-1">Type</th>
                                        <th class="col-1">Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs" wire:loading.attr='hidden'>
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->NAME }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                            <td><a target="_blank"
                                                    href="{{ route('customersstatement_view', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-primary">View</a></td>
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
                <div class="col-12">
                    <div class="col-md-6">
                        {{ $dataList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
