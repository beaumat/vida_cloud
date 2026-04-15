<?php

namespace App\Livewire\GeneralJournal;

use App\Services\GeneralJournalServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class GeneralJournalTempleteModal extends Component
{
    #[Reactive]
    public int $GENERAL_JOURNAL_ID;
    public bool $showModal = false;
    public $dataList = [];
    private $generalJournalServices;
    public function boot(GeneralJournalServices $generalJournalServices)
    {
        $this->generalJournalServices = $generalJournalServices;
    }
    #[On('open-templete')]
    public function openModal()
    {   

        $this->dataList = $this->generalJournalServices->getListTemplete();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.general-journal.general-journal-templete-modal');
    }
}
