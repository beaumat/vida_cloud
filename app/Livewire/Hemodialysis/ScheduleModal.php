<?php

namespace App\Livewire\Hemodialysis;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ScheduleModal extends Component
{
    public bool $showModal;
    public bool $withBack = false;
    #[Reactive]
    public $LOCATION_ID;
    public $DATE;
    private $scheduleServices;
    private $hemoServices;
    public $dataList = [];
    public $scheduleSelected = [];
    public bool $SelectAll = false;
    public string $ids;
    public $shiftList = [];
    public int $SHIFT_ID = 0;
    private $shiftServices;
    private $dateServices;


    public function boot(
        ScheduleServices $scheduleServices,
        HemoServices $hemoServices,
        ShiftServices $shiftServices,
        DateServices $dateServices
    ) {
        $this->scheduleServices = $scheduleServices;
        $this->hemoServices = $hemoServices;
        $this->shiftServices = $shiftServices;
        $this->dateServices = $dateServices;
    }

    public function create()
    {
        $isDone = false;
        $this->ids = "";
        foreach ($this->scheduleSelected as $scheId => $isSelect) {
            if ($isSelect) {
                $data = $this->scheduleServices->getInfo($scheId);
                if ($data) {

                    try {

                        if (!$this->hemoServices->CheckingExistsThatDay($this->DATE, $data->CONTACT_ID, $this->LOCATION_ID)) {
                            DB::beginTransaction();
                            $HEMO_ID = (int) $this->hemoServices->PreSave(
                                $this->DATE,
                                "",
                                $data->CONTACT_ID,
                                $this->LOCATION_ID
                            );
                            $this->hemoServices->GetOtherDetailsDefault(
                                $HEMO_ID,
                                $data->CONTACT_ID,
                                $this->DATE,
                                $this->LOCATION_ID
                            );

                            $NO = (int) $this->hemoServices->GetNoTreatment(
                                $data->CONTACT_ID,
                                $this->LOCATION_ID,
                                $this->DATE
                            );
                            $this->hemoServices->AutoDefaultItem(
                                $NO,
                                $HEMO_ID,
                                $this->LOCATION_ID
                            );

                            if ($this->ids == "") {
                                $this->ids = $HEMO_ID;
                            } else {
                                $this->ids = $this->ids . "," . $HEMO_ID;
                            }
                            DB::commit();
                            $isDone = true;
                        }
                    } catch (\Throwable $th) {
                        session()->flash('error', $th->getMessage());
                        DB::rollBack();
                    }
                }
            }
        }

        if ($isDone == false) {
            return;
        }

        if ($this->withBack) {
            $url = route('patientshemo_print_front_back', ['id' => $this->ids]);
        } else {
            $url = route('patientshemo_print', ['id' => $this->ids]);
        }
        $this->dispatch('schedOpenNewTab', data: $url);
        $this->dispatch('refresh-list');
        $this->closeModal();
    }
    public function updatedSelectAll($value)
    {

        if ($value) {
            foreach ($this->dataList as $list) {
                $this->scheduleSelected[$list->ID] = true;
            }
        } else {

            $this->reset('scheduleSelected');
        }
    }

    public function updatedShiftId()
    {
        $this->SelectAll = false;
        $this->reset('scheduleSelected');
    }
    public function openModal()
    {
        $this->DATE = $this->dateServices->NowDate();
        $this->showModal = true;
        $this->SelectAll = false;
        $this->reset('scheduleSelected');
    }
    #[On('schedule-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        $this->shiftList = $this->shiftServices->List();
        $this->dataList = $this->scheduleServices->GetScheduleList($this->DATE, $this->LOCATION_ID, $this->SHIFT_ID);
        return view('livewire.hemodialysis.schedule-modal');
    }
}
