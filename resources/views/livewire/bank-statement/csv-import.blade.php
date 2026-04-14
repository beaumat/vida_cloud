<div wire:loading.class='loading-form'>

    <input type="file" wire:model.live="file" accept=".csv">
    @error('file')
        <span class="error">{{ $message }}</span>
    @enderror

    @if (!empty($rows))
        <h2 class="mt-4">Preview Data ({{ count($rows) }} rows)</h2>
        <table class="table">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($row as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button wire:click="importData" class="btn btn-primary mt-4">Import Data</button>

    @endif

    <div wire:loading.delay>
        <span class="spinner"></span>
    </div>
</div>
