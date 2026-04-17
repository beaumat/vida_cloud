<?php

namespace App\Livewire\BankStatement;

use App\Services\BankStatementServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithFileUploads;

class CsvImport extends Component
{
    use WithFileUploads;

    #[Reactive]
    public int $FILE_TYPE;

    #[Reactive]
    public $BANK_STATEMENT_ID;

    public $file;
    public $rows = [];
    public $headers = [];

    private $bankStatementServices;

    public function boot(BankStatementServices $bankStatementServices)
    {
        $this->bankStatementServices = $bankStatementServices;
    }

    public function updatedFile()
    {
        $this->headers = [];
        $this->rows = [];

        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $this->file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return;
        }

        $headerFound = false;

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $normalizedRow = array_map([$this, 'normalizeHeader'], $row);

            // Detect either raw EastWest export or your simplified template
            if (
                in_array('transaction date', $normalizedRow) &&
                (
                    in_array('reference', $normalizedRow) ||
                    in_array('sequence number', $normalizedRow)
                )
            ) {
                $this->headers = $row;
                $headerFound = true;
                continue;
            }

            if (!$headerFound) {
                continue;
            }

            $this->rows[] = $row;
        }

        fclose($handle);
    }

    public function importData()
    {
        DB::beginTransaction();

        try {
            foreach ($this->rows as $row) {
                $data = $this->mapRow($row);

                $this->bankStatementServices->storeDetails(
                    $this->BANK_STATEMENT_ID,
                    $data['DATE_TRANSACTION'],
                    $data['REFERENCE'],
                    $data['DESCRIPTION'],
                    $data['CHECK_NUMBER'],
                    $data['DEBIT'],
                    $data['CREDIT'],
                    $data['BALANCE']
                );
            }

            $this->bankStatementServices->UpdateField($this->BANK_STATEMENT_ID);

            DB::commit();

            $this->rows = [];
            $this->headers = [];
            $this->file = null;

            return Redirect::route('bankingbank_statement_edit', [
                'id' => $this->BANK_STATEMENT_ID
            ])->with('message', 'CSV imported successfully.');

        } catch (\Throwable $th) {
            DB::rollBack();

            $this->dispatch('promp', result: [
                'key' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    private function mapRow(array $row): array
    {
        $headerMap = $this->getHeaderMap();

        $transactionDate = $this->getValue($row, $headerMap, [
            'transaction date'
        ]);

        $time = $this->getValue($row, $headerMap, [
            'time'
        ]) ?? '00:00:00';

        $reference = $this->getValue($row, $headerMap, [
            'reference',
            'sequence number'
        ]);

        $description = $this->getValue($row, $headerMap, [
            'description',
            'transaction description'
        ]);

        $checkNumber = $this->getValue($row, $headerMap, [
            'check number'
        ]);

        $debitRaw = $this->getValue($row, $headerMap, [
            'debit',
            'debit amount'
        ]) ?? 0;

        $creditRaw = $this->getValue($row, $headerMap, [
            'credit',
            'credit amount'
        ]) ?? 0;

        $balanceRaw = $this->getValue($row, $headerMap, [
            'running balance',
            'balance'
        ]) ?? 0;

        return [
            'DATE_TRANSACTION' => $this->parseDateTime($transactionDate, $time),
            'REFERENCE' => $reference,
            'DESCRIPTION' => $description,
            'CHECK_NUMBER' => $checkNumber,
            'DEBIT' => $this->cleanAmount($debitRaw),
            'CREDIT' => $this->cleanAmount($creditRaw),
            'BALANCE' => $this->cleanAmount($balanceRaw),
        ];
    }

    private function getHeaderMap(): array
    {
        $map = [];

        foreach ($this->headers as $index => $header) {
            $normalized = $this->normalizeHeader($header);

            if ($normalized !== '') {
                $map[$normalized] = $index;
            }
        }

        return $map;
    }

    private function getValue(array $row, array $headerMap, array $possibleHeaders)
    {
        foreach ($possibleHeaders as $header) {
            if (array_key_exists($header, $headerMap)) {
                $index = $headerMap[$header];
                return $row[$index] ?? null;
            }
        }

        return null;
    }

    private function normalizeHeader($value): string
    {
        $value = trim((string) $value);
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value); // remove BOM
        $value = strtolower($value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }

    private function parseDateTime($date, $time)
    {
        if (!$date) {
            return null;
        }

        $dateTime = trim($date . ' ' . ($time ?: '00:00:00'));

        try {
            return date('Y-m-d H:i:s', strtotime($dateTime));
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function cleanAmount($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = str_replace(',', '', (string) $value);
        return (float) $value;
    }

    public function render()
    {
        return view('livewire.bank-statement.csv-import');
    }
}