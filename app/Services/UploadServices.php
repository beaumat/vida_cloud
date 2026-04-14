<?php

namespace App\Services;

use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadServices
{
    use WithFileUploads;
    public function RemoveIfExists($FILE_PATH)
    {
        if ($FILE_PATH) {
            if (Storage::disk('custom_local')->exists($FILE_PATH)) {
                Storage::disk('custom_local')->delete($FILE_PATH);
            }
        }
    }
    public function Payment($PDF)
    {
        $path = $PDF->store('payments', 'custom_local');
        $extension = $PDF->extension();
        $dataReturn = [
            'new_path' => $path,
            'extension' => $extension,
            'filename' => basename($path)
        ];
        return $dataReturn;
    }
    public function BillFile($PDF): array
    {
        $path = $PDF->store('bills', 'custom_local');
        $extension = $PDF->extension();
        $dataReturn = [
            'new_path' => $path,
            'extension' => $extension,
            'filename' => basename($path)
        ];
        return $dataReturn;
    }

    public function RequirementFile($PDF): array
    {
        $path = $PDF->store('requirement', 'custom_local');
        $extension = $PDF->extension();
        $dataReturn = [
            'new_path' => $path,
            'extension' => $extension,
            'filename' => basename($path)
        ];
        return $dataReturn;
    }
    public function Availment($PDF): array
    {
        $path = $PDF->store('Availement', 'custom_local');
        $extension = $PDF->extension();
        $dataReturn = [
            'new_path' => $path,
            'extension' => $extension,
            'filename' => basename($path)
        ];
        return $dataReturn;
    }
    public function Treatment($Image)
    {

        try {

            $path = $Image->store('images', 'custom_local');
            $extension = $Image->extension();

            $dataReturn = [
                'new_path' =>  $path,
                'extension' => $extension,
                'filename' => basename($path)
            ];

            return $dataReturn;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            return [];
        }
    }
}
