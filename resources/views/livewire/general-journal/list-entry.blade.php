
<div class="bg-light container-fluid">
    @livewire('TableView', [
    'headers' => [
        ['label' => 'Reference No.', 'key' => 'CODE'],
        ['label' => 'Date', 'key' => 'DATE'],
        ['label' => 'Amount', 'key' => 'AMOUNT', 'td_class' => 'text-right'],
        ['label' => 'Location', 'key' => 'LOCATION_NAME'],
        ['label' => 'Status', 'key' => 'STATUS'],
        ['label' => 'Action', 'key' => 'action_html', 'class' => 'text-center col-1'],
    ],
    'rows' => collect($dataList)->map(function($item) {
        return [
            'CODE' => '<a target="_blank" href="' . route('companygeneral_journal_edit', ['id' => $item->ID]) . '">' . e($item->CODE) . '</a>',
            'DATE' => date('m/d/Y', strtotime($item->DATE)),
            'AMOUNT' => number_format($item->AMOUNT, 2),
            'LOCATION_NAME' => $item->LOCATION_NAME,
            'STATUS' => $item->STATUS,
            'action_html' => '<a target="_blank" class="btn btn-xs btn-primary w-100" href="' . route('companygeneral_journal_edit', ['id' => $item->ID]) . '"><i class="fas fa-eye"></i> View</a>'
        ];
    })->toArray()
])
</div>