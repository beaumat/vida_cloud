<?php

namespace App\Livewire\Hemodialysis;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class PrintListModal extends Component
{
    use WithPagination;
    #[Reactive]
    public int $LOCATION_ID;
    public $hemoSelected = [];
    public $dataList = [];
    public $hemoList = [];
    public bool $showModal;
    public string $search = '';
    public string $id;
    private $hemoServices;

    public bool $SelectAll = false;
    public $shiftList = [];
    private $shiftServices;
    public int $SHIFT_ID = 0;
    public string $DATE;
    private $dateServices;
    public function boot(HemoServices $hemoServices, ShiftServices $shiftServices, DateServices $dateServices)
    {
        $this->hemoServices = $hemoServices;
        $this->shiftServices = $shiftServices;
        $this->dateServices = $dateServices;
    }
    public function print()
    {
        $gotSelect = false;
        $this->id = "";
        foreach ($this->hemoSelected as $hemoId => $isSelected) {

            if ($isSelected) {
                try {
                    $gotSelect = true;
                    if ($this->id == "") {
                        $this->id = $hemoId;
                    } else {
                        $this->id = $this->id . "," . $hemoId;
                    }
                } catch (\Throwable $th) {

                    return;
                }
            }
        }

        if (!$gotSelect) {
            return;
        }
        
        $url = route('patientshemo_print', ['id' => $this->id]);
        $this->dispatch('openNewTab', data: $url);
        $this->closeModal();
    }

    public function printback()
    {
        $gotSelect = false;
        $this->id = "";
        foreach ($this->hemoSelected as $hemoId => $isSelected) {
            if ($isSelected) {
                try {
                    $gotSelect = true;
                    if ($this->id == "") {
                        $this->id = $hemoId;
                    } else {
                        $this->id = $this->id . "," . $hemoId;
                    }
                } catch (\Throwable $th) {
                    return;
                }
            }
        }

        if (!$gotSelect) {
            return;
        }



        $url = route('patientshemo_print_back', ['id' => $this->id]);
        $this->dispatch('openNewTab', data: $url);
        $this->closeModal();
    }


    public function printfrontback()
    {
        $gotSelect = false;
        $this->id = "";
        foreach ($this->hemoSelected as $hemoId => $isSelected) {
            if ($isSelected) {
                try {
                    $gotSelect = true;
                    if ($this->id == "") {
                        $this->id = $hemoId;
                    } else {
                        $this->id = $this->id . "," . $hemoId;
                    }
                } catch (\Throwable $th) {
                    return;
                }
            }
        }

        if (!$gotSelect) {
            return;
        }



        $url = route('patientshemo_print_front_back', ['id' => $this->id]);
        $this->dispatch('openNewTab', data: $url);
        $this->closeModal();
    }


    public function updatedShiftId()
    {
        $this->SelectAll = false;
        $this->reset('hemoSelected');
    }

    public function updatedDate()
    {
        $this->SelectAll = false;
        $this->reset('hemoSelected');
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->hemoList as $list) {
                $this->hemoSelected[$list->ID] = true;
            }
        } else {

            $this->reset('hemoSelected');
        }
    }
    public function mount()
    {
        $this->DATE = $this->dateServices->NowDate();
    }
    public function openModal()
    {
        $this->showModal = true;
        $this->SelectAll = false;
        $this->reset('hemoSelected');
    }
    #[On('print-list-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {

        $this->shiftList = $this->shiftServices->List();
        $this->hemoList = $this->hemoServices->SearchListbyShift($this->search, $this->LOCATION_ID, $this->SHIFT_ID, $this->DATE);

        return view('livewire.hemodialysis.print-list-modal');
    }
}
