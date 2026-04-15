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

    #[Reactive]
    public int $FILE_TYPE;

    #[Reactive]
    public $BANK_STATEMENT_ID;

    use WithFileUploads;

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
        $this->rows    = [];
        $this->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240', // 10MB limit
        ]);

        $path   = $this->file->getRealPath();
        $handle = fopen($path, 'r');

        if ($handle) {
            // Read the first row as headers
            $this->headers = fgetcsv($handle, 1000, ',');

            // Read the rest of the file
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                // Combine headers and row data into an associative array for easier processing
                if (count($this->headers) === count($row)) {
                    $this->rows[] = array_combine($this->headers, $row);
                }
            }

            fclose($handle);
            // Optional: Store the file permanently or process it in a queued job
            // $this->file->store('csv-files');
        }
    }

    public function importData()
    {
        DB::beginTransaction();
        try {
            //code...

            // Process the data, e.g., save to the database, dispatch jobs, etc.
            foreach ($this->rows as $row) {

                $DATE_TRANSACTION = $row["\u{FEFF}Transaction Date"];
                $REFERENCE        = $row["Reference"];
                $DESCRIPTION      = $row["Description"];
                $CHECK_NUMBER     = $row["Check Number"];
                $DEBIT            = (float)$row["Debit"] ?? 0;
                $CREDIT           = (float) $row["Credit"] ?? 0;
                $BALANCE          = (float) $row["Running Balance"] ?? 0;

                $this->bankStatementServices->storeDetails($this->BANK_STATEMENT_ID,
                 $DATE_TRANSACTION,
                 $REFERENCE,
                  $DESCRIPTION,
                  $CHECK_NUMBER,
                   $DEBIT,
                    $CREDIT,
                     $BALANCE);
            }
            $this->bankStatementServices->UpdateField($this->BANK_STATEMENT_ID);
            DB::commit();
            $this->rows    = [];
            $this->headers = [];
            $this->file    = null;

            return Redirect::route('bankingbank_statement_edit', ['id' => $this->BANK_STATEMENT_ID])->with('message', 'CSV imported successfully.');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $this->dispatch('promp', result: ['key'=> 'error', 'message' => $th->getMessage()]);
        }

    }
    public function render()
    {
        return view('livewire.bank-statement.csv-import');
    }
}
