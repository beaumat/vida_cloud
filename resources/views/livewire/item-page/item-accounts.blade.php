<div>
    <div class="form-group row">
        <div class="col-md-6">
            <div class="card">
                <div class='card-header'>Selected: <input class="text-xs" placeholder="Search Account"
                        wire:model.lazy='searchSelected' /></div>
                <div class="card-body">
                    <table class='table table-sm table-bordered table-hover'>
                        <thead class="bg-sky text-xs">
                            <tr>
                                <td> Account Name </td>
                                <td class='col-4'>Type</td>
                                <td class="col-1"> Action </td>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($selectedList as $list)
                                <tr>
                                    <td>{{ $list->NAME }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td><button wire:click='Delete({{ $list->ID }})'
                                            class="btn btn-xs btn-danger w-100"><i class="fa fa-times"
                                                aria-hidden="true"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="card">
                <div class='card-header'>Available: <input class="text-xs" placeholder="Search Account"
                        wire:model.lazy='searchAvailable' /></div>
                <div class="card-body">
                    <table class='table table-sm table-bordered table-hover'>
                        <thead class="bg-sky text-xs">
                            <tr>
                                <td> Account Name </td>
                                <td class='col-4'>Type</td>
                                <td class="col-1"> Action </td>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($availableList as $list)
                                <tr>
                                    <td>{{ $list->NAME }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td><button class="btn btn-xs btn-success w-100"
                                            wire:click='Add({{ $list->ID }})'><i class="fa fa-plus"
                                                aria-hidden="true"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
