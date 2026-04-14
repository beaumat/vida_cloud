<div>
    @php
        use Carbon\Carbon;
    @endphp
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Philheatlh Form Finder
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

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
                                                                    <input type="text"
                                                                        wire:model.live.debounce='search'
                                                                        class="w-100 form-control form-control-sm"
                                                                        placeholder="Search" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="mt-0">
                                                                            <label class="text-sm">From :</label>
                                                                            <input type="date"
                                                                                wire:model.live.debounce.150ms='ADMITTED'
                                                                                class="w-100 form-control form-control-sm" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <div class="mt-0">
                                                                            <label class="text-sm">To :</label>
                                                                            <input type="date"
                                                                                wire:model.live.debounce='DISCHARGED'
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
                                                                            <option value="{{ $item->ID }}">
                                                                                {{ $item->NAME }}
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
                                                            <th>Date Created</th>
                                                            <th>Elapsed </th>
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

                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-xs">
                                                        @foreach ($dataList as $list)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                                        class="text-primary">
                                                                        {{ $list->CODE }}
                                                                    </a>
                                                                </td>
                                                                <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                                                <td> {{ Carbon::parse($list->RECORDED_ON)->diffForHumans() }}
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
                                                                    {{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}
                                                                </td>
                                                                <td
                                                                    class="text-center  @if ($list->AR_DATE) text-success @else text-danger @endif">
                                                                    {{ Carbon::parse($list->DATE_ADMITTED)->diffInDays($list->AR_DATE ? $list->AR_DATE : Carbon::now()) }}
                                                                </td>
                                                                <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                                                <td class="text-right">
                                                                    {{ number_format($list->P1_TOTAL, 2) }}</td>
                                                                <td class="text-right">
                                                                    {{ number_format($list->PAYMENT_AMOUNT, 2) }}</td>
                                                                <td
                                                                    class="@if ($list->STATUS == 'Paid') text-success @else text-danger @endif ">
                                                                    {{ $list->STATUS }}
                                                                </td>
                                                                <td> {{ $list->LOCATION_NAME }}</td>

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
                </div>
            </div>
        </div>
    @endif
</div>
