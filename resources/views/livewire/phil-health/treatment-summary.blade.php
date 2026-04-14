<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs">
                <tr class="text-center bg-sky text-white">
                    <th class="text-center">No.</th>
                    <th class="col-1 text-center">Reference</th>
                    <th class="col-1 text-center">Date</th>
                    <th class="col-1 text-center">Time Start</th>
                    <th class="col-1 text-center">Time End</th>
                    <th class="col-7">Doctor Order</th>
                    <th class="text-center col-1">Action</th>
                </tr>
            </thead>
            <tbody class="text-dark text-xs">
                @foreach ($hemoList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">
                            <a target="_BLANK"
                                href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                        </td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                        <td class="text-center">
                            {{ $list->TIME_START ? \Carbon\Carbon::parse($list->TIME_START)->format('g:i a') : '' }}
                        </td>
                        <td class="text-center">
                            {{ $list->TIME_START ? \Carbon\Carbon::parse($list->TIME_END)->format('g:i a') : '' }}</td>
                        <td>
                            @if ($list->ID == $editId)
                                <input type="text" name="dataName" class="form-control form-control-sm"
                                    wire:model='editDoctorOrder' />
                            @else
                                {{ $list->DOCTOR_ORDER }}
                            @endif

                        </td>
                        <td class="text-center">
                            @if ($list->ID == $editId)
                                <button class="btn btn-xs btn-success" wire:click='clickSave'>
                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-xs btn-danger" wire:click='clickCancel'>
                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button class="btn btn-xs btn-info" wire:click='clickEdit({{ $list->ID }})'>
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-xs btn-primary" wire:click='OpenModify({{ $list->ID }})'>
                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                </button>
                            @endif

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @livewire('PhilHealth.DoctorOrder')
</div>
