<?php

namespace App\Livewire\Patient;

use App\Services\ContactRequirementServices;
use App\Services\UploadServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class RequirementUpload extends Component
{

    #[Reactive()]
    public int $ID;
    #[Reactive]
    public $FILE_PATH;
    #[Reactive]
    public $FILE_NAME;
    #[Reactive]
    public $FILE_CONFIRM_DATE;
    use WithFileUploads;
    public $PDF = null;
    public bool $showFileName = true;
    private $contactRequirementServices;
    private $uploadServices;
    public function boot(ContactRequirementServices $contactRequirementServices, UploadServices $uploadServices)
    {

        $this->contactRequirementServices = $contactRequirementServices;
        $this->uploadServices = $uploadServices;
    }
    public function getDocumentProccess()
    {
        $returnData = $this->uploadServices->RequirementFile($this->PDF);
        $this->contactRequirementServices->UpdateFile(
            $this->ID,
            $returnData['filename'] . '.' . $returnData['extension'],
            $returnData['new_path']
        );
        $this->PDF;
    }
    public function updatedPDF()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf,jpeg,jpg,png|max:10240', // PDF or image file, max 10MB
        ], [], [
            'PDF' => 'Files'
        ]);
    }
    public function uploading()
    {
        $this->validate([
            'PDF' => 'required|file|mimes:pdf,jpeg,jpg,png|max:10240', // PDF or image file, max 10MB
        ], [], [
            'PDF' => 'Files'
        ]);

        $this->getDocumentProccess();
        $this->dispatch('refresh-requirements');

    }
    public function confirm() {
        $this->contactRequirementServices->FileConfirmDate($this->ID);
        $this->dispatch('refresh-requirements');
    }
    public function removeFile()
    {
        try {
            $this->uploadServices->RemoveIfExists($this->FILE_PATH);   
            $this->contactRequirementServices->UpdateRemoveFile(
                $this->ID,
            );
            $this->dispatch('refresh-requirements');
        } catch (\Exception $e) {
            session()->flash('error', 'Error removing file: ' . $e->getMessage());
            return;
        }
  

    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.patient.requirement-upload');
    }
}
