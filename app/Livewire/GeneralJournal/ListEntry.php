<?php

namespace App\Livewire\GeneralJournal;

use App\Services\GeneralJournalServices;
use Livewire\Component;

class ListEntry extends Component
{  public $dataList = [];
    public int $contact_id = 0;
    private $generalJournalServices;
    public function boot(GeneralJournalServices $generalJournalServices)
    {
        $this->generalJournalServices = $generalJournalServices;
    }   

    public function mount($id = 0)
    {
        // Initialize the contact_id with the provided id or default to 0
        $this->contact_id = $id;
        // Initialize any properties or services needed for the component
    }
    public function render()
    {   $this->dataList = $this->generalJournalServices->listViaContact($this->contact_id);
        // Fetch the list of general journal entries for the contact_idurn 
        return view('livewire.general-journal.list-entry');
    }
}
