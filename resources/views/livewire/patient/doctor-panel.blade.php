<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-2">Accreditation No</th>
                <th class="col-9">Doctor Name</th>
                <th class="col-1 text-center">Action </th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td><label>{{ $list->PIN }}</label></td>
                    <td><label>{{ $list->NAME }}</label> </td>
                    <td>
                        <button type="button"
                            @if ($ID > 0) @cannot('contact.patient.update')  disabled  @endcan @endif
                            wire:click='delete({{ $list->ID }})' wire:confirm="Are you sure you want to delete this?"
                            class="btn btn-sm text-xs btn-danger w-100">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            @can('contact.patient.update')
                <tr>
                    <td></td>
                    <td>
                        @if ($saveSuccess)
                            <livewire:select-option name="DOCTOR_ID1" titleName="" :options="$contactList" :zero="true"
                                wire:model.live='DOCTOR_ID' :vertical="false" :withLabel="false"
                                isDisabled="{{ false }}" />
                        @else
                            <livewire:select-option name="DOCTOR_ID2" titleName="" :options="$contactList" :zero="true"
                                wire:model.live='DOCTOR_ID' :vertical="false" :withLabel="false"
                                isDisabled="{{ false }}" />
                        @endif
                    </td>
                    <td><button wire:click='save' type="button" class="btn btn-sm btn-success w-100"> <i class="fa fa-plus"
                                aria-hidden="true"></i> </button></td>
                </tr>
            @endcan

        </tbody>
    </table>
</div>
