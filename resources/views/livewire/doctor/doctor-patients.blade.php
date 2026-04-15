
<div class="bg-light container-fluid">
    @livewire('TableView', [
    'headers' => [
        ['label' => 'Account No.', 'key' => 'ACCOUNT_NO'],
        ['label' => 'Name', 'key' => 'NAME'],
        ['label' => 'Total Transmittal', 'key' => 'PHILHEALTH', 'td_class' => 'text-center col-1','class' => 'text-center col-1'],
        ['label' => 'PIN', 'key' => 'PIN'],
        ['label' => 'Location', 'key' => 'LOCATION_NAME'],
        ['label' => 'Action', 'key' => 'action_html', 'class' => 'text-center col-1'],
           
    ],
    'rows' => collect($dataList)->map(function($item) {
        return [
            'ACCOUNT_NO' => '<a target="_blank" href="' . route('maintenancecontactpatients_edit', ['id' => $item->ID]) . '">' . e($item->ACCOUNT_NO) . '</a>',
            'NAME' => $item->NAME,
            'PIN' => $item->PIN,
            'PHILHEALTH' => number_format($item->COUNT, 0),
            'LOCATION_NAME' => $item->LOCATION_NAME,
            'action_html' => '<a target="_blank" class="btn btn-xs btn-primary w-100" href="' . route('maintenancecontactpatients_edit', ['id' => $item->ID]) . '"><i class="fas fa-eye"></i> View Profile</a>'
        ];
    })->toArray()
])
</div>