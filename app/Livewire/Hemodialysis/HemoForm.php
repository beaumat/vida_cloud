<?php
namespace App\Livewire\Hemodialysis;

use App\Services\AccountJournalServices;
use App\Services\ContactServices;
use App\Services\DocumentTypeServices;
use App\Services\HemoServices;
use App\Services\ItemInventoryServices;
use App\Services\ItemTreatmentServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\ScheduleServices;
use App\Services\ServiceChargeServices;
use App\Services\UnitOfMeasureServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Hemodialysis Treatment')]
class HemoForm extends Component
{

    use WithFileUploads;
    public string $FILE_NAME;
    public string $FILE_PATH;
    public $IMAGE = null;
    public string $RECORDED_ON;
    public $data;
    public int $ID;
    public bool $PIN_ALLOWED = true;
    public bool $Modify;
    public int $STATUS;
    public int $openStatus = 1; // draft default
    public string $STATUS_DESCRIPTION;

    public $patientList  = [];
    public $locationList = [];

    public int $CUSTOMER_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public string $PRE_WEIGHT;
    public string $PRE_BLOOD_PRESSURE;
    public string $PRE_BLOOD_PRESSURE2;
    public string $PRE_HEART_RATE;
    public string $PRE_O2_SATURATION;
    public string $PRE_TEMPERATURE;
    public string $POST_WEIGHT;
    public string $POST_BLOOD_PRESSURE;
    public string $POST_BLOOD_PRESSURE2;
    public string $POST_HEART_RATE;
    public string $POST_O2_SATURATION;
    public string $POST_TEMPERATURE;
    public string $TIME_START;
    public string $TIME_END;

    // old

    public string $OLD_PRE_WEIGHT;
    public string $OLD_PRE_BLOOD_PRESSURE;
    public string $OLD_PRE_BLOOD_PRESSURE2;
    public string $OLD_PRE_HEART_RATE;
    public string $OLD_PRE_O2_SATURATION;
    public string $OLD_PRE_TEMPERATURE;
    public string $OLD_POST_WEIGHT;
    public string $OLD_POST_BLOOD_PRESSURE;
    public string $OLD_POST_BLOOD_PRESSURE2;
    public string $OLD_POST_HEART_RATE;
    public string $OLD_POST_O2_SATURATION;
    public string $OLD_POST_TEMPERATURE;
    public bool $USE_OTHER_DETAILS = true;
    public bool $IS_INCOMPLETE;

    public bool $DOCTOR_DES_PROMP = false;
    public int $EMPLOYEE_ID;
    public string $EMPLOYEE_NAME;

    public bool $IsPostedButton;
    public bool $ActiveRequired;
    public bool $IsDocmentUploaded;

    // end old
    private $hemoServices;
    private $locationServices;
    private $contactServices;
    private $userServices;
    private $uploadServices;
    private $itemInventoryServices;
    private $documentTypeServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    private $scheduleServices;
    private $patientDoctorServices;
    private $serviceChargeServices;
    private $accountJournalServices;
    public function boot(
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices,
        ItemInventoryServices $itemInventoryServices,
        DocumentTypeServices $documentTypeServices,
        ItemTreatmentServices $itemTreatmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ScheduleServices $scheduleServices,
        PatientDoctorServices $patientDoctorServices,
        ServiceChargeServices $serviceChargeServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->hemoServices           = $hemoServices;
        $this->locationServices       = $locationServices;
        $this->contactServices        = $contactServices;
        $this->userServices           = $userServices;
        $this->uploadServices         = $uploadServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->documentTypeServices   = $documentTypeServices;
        $this->itemTreatmentServices  = $itemTreatmentServices;
        $this->unitOfMeasureServices  = $unitOfMeasureServices;
        $this->scheduleServices       = $scheduleServices;
        $this->patientDoctorServices  = $patientDoctorServices;
        $this->serviceChargeServices  = $serviceChargeServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function getEmpName()
    {
        $data = $this->contactServices->get($this->EMPLOYEE_ID, 2);
        if ($data) {
            $this->EMPLOYEE_NAME = $data->NAME ?? '';
            return;
        }
        $this->EMPLOYEE_NAME = '';
    }
    public function reloadData($data)
    {
        $this->RECORDED_ON          = $data->RECORDED_ON ?? '';
        $this->ID                   = $data->ID;
        $this->DATE                 = $data->DATE;
        $this->LOCATION_ID          = $data->LOCATION_ID;
        $this->patientList          = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->CUSTOMER_ID          = $data->CUSTOMER_ID;
        $this->CODE                 = $data->CODE;
        $this->Modify               = false;
        $this->PRE_WEIGHT           = $data->PRE_WEIGHT ?? "";
        $this->PRE_BLOOD_PRESSURE   = $data->PRE_BLOOD_PRESSURE ?? "";
        $this->PRE_BLOOD_PRESSURE2  = $data->PRE_BLOOD_PRESSURE2 ?? "";
        $this->PRE_HEART_RATE       = $data->PRE_HEART_RATE ?? "";
        $this->PRE_O2_SATURATION    = $data->PRE_O2_SATURATION ?? "";
        $this->PRE_TEMPERATURE      = $data->PRE_TEMPERATURE ?? "";
        $this->POST_WEIGHT          = $data->POST_WEIGHT ?? "";
        $this->POST_BLOOD_PRESSURE  = $data->POST_BLOOD_PRESSURE ?? "";
        $this->POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
        $this->POST_HEART_RATE      = $data->POST_HEART_RATE ?? "";
        $this->POST_O2_SATURATION   = $data->POST_O2_SATURATION ?? "";
        $this->POST_TEMPERATURE     = $data->POST_TEMPERATURE ?? "";
        $this->TIME_START           = $data->TIME_START ?? "";
        $this->TIME_END             = $data->TIME_END ?? "";
        $this->FILE_NAME            = $data->FILE_NAME ?? "";
        $this->FILE_PATH            = $data->FILE_PATH ?? "";
        $this->STATUS               = $data->STATUS_ID ?? 1;
        $this->IsDocmentUploaded    = false;
        $this->IS_INCOMPLETE        = $data->IS_INCOMPLETE ?? false;
        $this->EMPLOYEE_ID          = $data->EMPLOYEE_ID ?? 0;
        $this->getEmpName();
        if ($this->TIME_START != "" && $this->TIME_END != "") {
            if ($this->STATUS != 3) {
                $this->IsDocmentUploaded = true;
            }
        }
        if ($this->TIME_END && $this->TIME_START) {
            $this->DOCTOR_DES_PROMP = true;
        }
        $this->getPreviousTreatment();

    }

    public function setNullEmployee()
    {
        if ($this->EMPLOYEE_ID > 0) {
            $this->hemoServices->NullEmployee($this->ID);
            return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully nurse remove');
        }
    }

    private function removeJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->hemoServices->object_type_hemo, $this->ID);
        if ($JOURNAL_NO > 0) {
            $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
        }

    }
    public function mount($id = null)
    {
        $this->ActiveRequired = true;
        $this->IsPostedButton = false;

        $this->Modify       = true;
        $this->locationList = $this->locationServices->getList();
        if (is_numeric($id)) {
            $data = $this->hemoServices->Get($id);
            if ($data) {
                // checking if
                if ($data->STATUS_ID != 4) {
                    $isRestrik = (bool) $this->hemoServices->IsRestrictedFromUnposted($data->DATE, $data->LOCATION_ID);
                    if ($isRestrik) {

                        if (! UserServices::GetUserRightAccess('allowed-view-treatment')) {
                            return Redirect::route('patientshemo')->with('error', 'Invalid action. Please complete the unposted(U) treatment before proceeding.');

                        }
                    }
                }
                $this->reloadData($data);
                $statusData = DB::table('hemo_status')->select('description')->where('ID', $data->STATUS_ID)->first();
                if ($statusData) {
                    $this->STATUS_DESCRIPTION = $statusData->description ?? '';
                }
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientshemo')->with('error', $errorMessage);
        }

        $this->ID                   = 0;
        $this->DATE                 = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID          = $this->userServices->getLocationDefault();
        $this->patientList          = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->CUSTOMER_ID          = 0;
        $this->CODE                 = '';
        $this->PRE_WEIGHT           = "";
        $this->PRE_BLOOD_PRESSURE   = "";
        $this->PRE_BLOOD_PRESSURE2  = "";
        $this->PRE_HEART_RATE       = "";
        $this->PRE_O2_SATURATION    = "";
        $this->PRE_TEMPERATURE      = "";
        $this->POST_WEIGHT          = "";
        $this->POST_BLOOD_PRESSURE  = "";
        $this->POST_BLOOD_PRESSURE2 = "";
        $this->POST_HEART_RATE      = "";
        $this->POST_O2_SATURATION   = "";
        $this->POST_TEMPERATURE     = "";

        $this->IMAGE              = null;
        $this->FILE_NAME          = '';
        $this->FILE_PATH          = '';
        $this->STATUS             = 0;
        $this->STATUS_DESCRIPTION = '';
        $this->IsDocmentUploaded  = false;

        $this->IS_INCOMPLETE = false;
        $this->EMPLOYEE_ID   = 0;
        $this->EMPLOYEE_NAME = '';
    }
    public function getPreviousTreatment()
    {
        $data = $this->hemoServices->ShowLastTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);
        if ($data) {
            $this->OLD_PRE_WEIGHT           = $data->PRE_WEIGHT ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE   = $data->PRE_BLOOD_PRESSURE ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE2  = $data->PRE_BLOOD_PRESSURE2 ?? "";
            $this->OLD_PRE_HEART_RATE       = $data->PRE_HEART_RATE ?? "";
            $this->OLD_PRE_O2_SATURATION    = $data->PRE_O2_SATURATION ?? "";
            $this->OLD_PRE_TEMPERATURE      = $data->PRE_TEMPERATURE ?? "";
            $this->OLD_POST_WEIGHT          = $data->POST_WEIGHT ?? "";
            $this->OLD_POST_BLOOD_PRESSURE  = $data->POST_BLOOD_PRESSURE ?? "";
            $this->OLD_POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
            $this->OLD_POST_HEART_RATE      = $data->POST_HEART_RATE ?? "";
            $this->OLD_POST_O2_SATURATION   = $data->POST_O2_SATURATION ?? "";
            $this->OLD_POST_TEMPERATURE     = $data->POST_TEMPERATURE ?? "";
            return;
        }

        $this->OLD_PRE_WEIGHT           = "";
        $this->OLD_PRE_BLOOD_PRESSURE   = "";
        $this->OLD_PRE_BLOOD_PRESSURE2  = "";
        $this->OLD_PRE_HEART_RATE       = "";
        $this->OLD_PRE_O2_SATURATION    = "";
        $this->OLD_PRE_TEMPERATURE      = "";
        $this->OLD_POST_WEIGHT          = "";
        $this->OLD_POST_BLOOD_PRESSURE  = "";
        $this->OLD_POST_BLOOD_PRESSURE2 = "";
        $this->OLD_POST_HEART_RATE      = "";
        $this->OLD_POST_O2_SATURATION   = "";
        $this->OLD_POST_TEMPERATURE     = "";
    }
    public function update_all()
    {

        $this->hemoServices->Update(
            $this->ID,
            $this->PRE_WEIGHT,
            $this->PRE_BLOOD_PRESSURE,
            $this->PRE_BLOOD_PRESSURE2,
            $this->PRE_HEART_RATE,
            $this->PRE_O2_SATURATION,
            $this->PRE_TEMPERATURE,
            $this->POST_WEIGHT,
            $this->POST_BLOOD_PRESSURE,
            $this->POST_BLOOD_PRESSURE2,
            $this->POST_HEART_RATE,
            $this->POST_O2_SATURATION,
            $this->POST_TEMPERATURE,
            $this->TIME_START,
            $this->TIME_END,
            $this->IS_INCOMPLETE
        );

        if ($this->STATUS == 4) {
            $this->getPosted();
        } else {
            session()->flash('message', 'Successfully save');
        }
    }
    public function uploaddoc()
    {
        $this->validate([
            'IMAGE.*' => 'image|max:1024', // 1MB Max per image
        ]);

        if ($this->IMAGE != null) {
            $this->uploadServices->RemoveIfExists($this->FILE_PATH);
            $returnData = $this->uploadServices->Treatment($this->IMAGE);
            $this->hemoServices->UpdateFile($this->ID, $returnData['filename'] . '.' . $returnData['extension'], $returnData['new_path']);
            return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully upload');
        }
    }
    public function getModify()
    {

        if ($this->ActiveRequired == true && $this->STATUS == 1 || $this->ActiveRequired == true && $this->STATUS == 4) {
            $isRequiredItemAdded = $this->itemTreatmentServices->getRequiredSuccess($this->LOCATION_ID, $this->ID);
            if (! $isRequiredItemAdded) {
                session()->flash('error', ' You must select either a CVC Kit or an AVF Kit before making modifications.');
                return;
            }
        }
        if ($this->PIN_ALLOWED) {
            if ($this->EMPLOYEE_ID == 0) {
                $this->dispatch('open-pin-code');
                return;
            }
        }

        $this->Modify = true;
    }
    #[On('pin-login-success')]
    public function getPinSuccess($result)
    {
        $this->EMPLOYEE_ID = $result['EMPLOYEE_ID'];
        $this->hemoServices->UpdateEmployee($this->ID, $this->EMPLOYEE_ID);
        $this->getEmpName();
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $this->clearAlert();
        $data = $this->hemoServices->Get($this->ID);
        if ($data) {
            $this->reloadData($data);
            $this->dispatch('cancel-other');
            $this->Modify = false;
            return;
        }
        $this->Modify = false;
    }
    public function save()
    {
        $this->validate(
            [
                'CUSTOMER_ID' => 'required|not_in:0',
                'CODE'        => 'unique:hemodialysis,code,' . $this->ID,
                'DATE'        => 'required',
                'LOCATION_ID' => 'required',
            ],
            [],
            [
                'CUSTOMER_ID' => 'Patient',
                'DATE'        => 'Date',
                'CODE'        => 'Reference No.',
                'LOCATION_ID' => 'Location',
            ]
        );
        if ($this->IS_INCOMPLETE == false) {
            if ($this->ID > 0) {
                //Make it restricted
                if (empty($this->PRE_WEIGHT) && empty($this->PRE_BLOOD_PRESSURE) && empty($this->PRE_BLOOD_PRESSURE2) && empty($this->PRE_HEART_RATE) && empty($this->PRE_O2_SATURATION) && empty($this->PRE_TEMPERATURE) && empty($this->TIME_START) && empty($this->POST_WEIGHT) && empty($this->POST_BLOOD_PRESSURE) && empty($this->POST_BLOOD_PRESSURE2) && empty($this->POST_HEART_RATE) && empty($this->POST_O2_SATURATION) && empty($this->POST_TEMPERATURE) && empty($this->TIME_END)) {

                } else {

                    $this->validate(
                        [
                            'PRE_WEIGHT'          => 'required|not_in:0',
                            'PRE_BLOOD_PRESSURE'  => 'required|not_in:0',
                            'PRE_BLOOD_PRESSURE2' => 'required|not_in:0',
                            'PRE_HEART_RATE'      => 'required|not_in:0',
                            'PRE_O2_SATURATION'   => 'required',
                            'PRE_TEMPERATURE'     => 'required',
                            'TIME_START'          => 'required',
                        ],
                        [],
                        [

                            'PRE_WEIGHT'          => 'Pre weight',
                            'PRE_BLOOD_PRESSURE'  => 'Pre blood pressure [1]',
                            'PRE_BLOOD_PRESSURE2' => 'Pre blood pressure [2]',
                            'PRE_HEART_RATE'      => 'Pre heart rate',
                            'PRE_O2_SATURATION'   => 'Pre 02 saturation',
                            'PRE_TEMPERATURE'     => 'Pre temperature',
                            'TIME_START'          => 'Time Start',
                        ]
                    );

                    // }

                    if (empty($this->TIME_START) == false) {
                        if (empty($this->POST_WEIGHT) == false || empty($this->POST_BLOOD_PRESSURE) == false || empty($this->POST_BLOOD_PRESSURE2) == false || empty($this->POST_HEART_RATE) == false || empty($this->POST_O2_SATURATION) == false || empty($this->POST_TEMPERATURE) == false || empty($this->TIME_END) == false) {
                            if ($this->IS_INCOMPLETE == false) {
                                if (empty($this->POST_WEIGHT) || empty($this->POST_BLOOD_PRESSURE) || empty($this->POST_BLOOD_PRESSURE2) || empty($this->POST_HEART_RATE) || empty($this->POST_O2_SATURATION) || empty($this->POST_TEMPERATURE) || empty($this->TIME_END)) {
                                    $this->validate(
                                        [
                                            'POST_WEIGHT'          => 'required',
                                            'POST_BLOOD_PRESSURE'  => 'required',
                                            'POST_BLOOD_PRESSURE2' => 'required',
                                            'POST_HEART_RATE'      => 'required',
                                            'POST_O2_SATURATION'   => 'required',
                                            'POST_TEMPERATURE'     => 'required',
                                            'TIME_END'             => 'required',
                                        ],
                                        [],
                                        [
                                            'POST_WEIGHT'          => 'Post weight',
                                            'POST_BLOOD_PRESSURE'  => 'Post blood pressure [1]',
                                            'POST_BLOOD_PRESSURE2' => 'Post blood pressure [2]',
                                            'POST_HEART_RATE'      => 'Post heart rate',
                                            'POST_O2_SATURATION'   => 'Post 02 saturation',
                                            'POST_TEMPERATURE'     => 'Post temperature',
                                            'TIME_END'             => 'Time End',
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        try {
            DB::beginTransaction();
            if ($this->ID == 0) {

                $this->ID = (int) $this->hemoServices->PreSave(
                    $this->DATE,
                    $this->CODE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID
                );
                $this->hemoServices->GetOtherDetailsDefault(
                    $this->ID,
                    $this->CUSTOMER_ID,
                    $this->DATE,
                    $this->LOCATION_ID
                );
                $NO = (int) $this->hemoServices->GetNoTreatment(
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->DATE
                );
                $this->hemoServices->AutoDefaultItem($NO, $this->ID, $this->LOCATION_ID);
                DB::commit();
                return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->update_all();
                DB::commit();
                $this->dispatch('save-other');
                $this->Modify = false;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updatedImage()
    {
        $this->validate([
            'IMAGE.*' => 'image|max:1024', // 1MB Max per image
        ]);
    }

    private function executePosted(): bool
    {
        try {

            $this->hemoServices->makeItemInventory($this->ID);
            $this->hemoServices->getMakeJournal($this->ID);
            return true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function getCanceled()
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->StatusUpdate($this->ID, 3);
            $this->scheduleServices->StatusUpdate($this->CUSTOMER_ID, $this->DATE, $this->LOCATION_ID, 3);
            $this->removeJournal();
            DB::commit();
            return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully void');
        } catch (\Throwable $th) {
            DB::rollBack();
        }

    }
    public function getUnposted()
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->StatusUpdate($this->ID, 4);
            $this->scheduleServices->StatusUpdate(
                $this->CUSTOMER_ID,
                $this->DATE,
                $this->LOCATION_ID,
                0
            );
            $this->removeJournal();
            DB::commit();
            return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
        }

    }
    public function getPosted()
    {
        $isHavePriming = (bool) $this->serviceChargeServices->isHavePriming($this->DATE, $this->LOCATION_ID, $this->CUSTOMER_ID);
        if ($isHavePriming) {
            $this->PostStatus();
            return;
        }

        if ($this->IS_INCOMPLETE == false) {
            $this->validate(
                [
                    'PRE_WEIGHT'           => 'required|not_in:0',
                    'PRE_BLOOD_PRESSURE'   => 'required|not_in:0',
                    'PRE_BLOOD_PRESSURE2'  => 'required|not_in:0',
                    'PRE_HEART_RATE'       => 'required|not_in:0',
                    'PRE_O2_SATURATION'    => 'required',
                    'PRE_TEMPERATURE'      => 'required',
                    'TIME_START'           => 'required',
                    'POST_WEIGHT'          => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'POST_BLOOD_PRESSURE'  => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'POST_BLOOD_PRESSURE2' => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'POST_HEART_RATE'      => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'POST_O2_SATURATION'   => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'POST_TEMPERATURE'     => $this->IS_INCOMPLETE ? 'nullable' : 'required',
                    'TIME_END'             => 'required',
                ],
                [],
                [
                    'PRE_WEIGHT'           => 'Pre weight',
                    'PRE_BLOOD_PRESSURE'   => 'Pre Blood Pressure[1]',
                    'PRE_BLOOD_PRESSURE2'  => 'Pre Blood Pressure[2]',
                    'PRE_HEART_RATE'       => 'Pre Heart Rate',
                    'PRE_O2_SATURATION'    => 'Pre 02 Saturation',
                    'PRE_TEMPERATURE'      => 'Pre Temperature',
                    'POST_WEIGHT'          => 'Post Weight',
                    'POST_BLOOD_PRESSURE'  => 'Post Blood Pressure[1]',
                    'POST_BLOOD_PRESSURE2' => 'Post Blood Pressure[2]',
                    'POST_HEART_RATE'      => 'Post Heart Rate',
                    'POST_O2_SATURATION'   => 'Post 02 Saturation',
                    'POST_TEMPERATURE'     => 'Post Temperature',
                    'TIME_START'           => 'Time Start',
                    'TIME_END'             => 'Time End',

                ]
            );
        }
        $this->PostStatus();
    }
    private function PostStatus()
    {

        DB::beginTransaction();

        try {

            $ITEM_COUNT = (int) $this->hemoServices->CountItems($this->ID);
            if ($ITEM_COUNT == 0) {
                DB::rollBack();
                session()->flash('error', 'Item not found.');
                return;
            }

            if (! $this->executePosted()) {
                DB::rollBack();
                return;
            }

            $this->hemoServices->StatusUpdate($this->ID, 2);
            $this->scheduleServices->StatusUpdate($this->CUSTOMER_ID, $this->DATE, $this->LOCATION_ID, 1); //PRESENT

            DB::commit();
            session()->flash("message", 'Successfully posted');
            if ($this->STATUS == 4) {
                return Redirect::route('patientshemo');
            }

            return Redirect::route('patientshemo_edit', ['id' => $this->ID]);
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = "Caught a Throwable: " . $th->getMessage();
            session()->flash("error", $message);
        }
    }
    public function showNotes()
    {
        $contact = $this->contactServices->get($this->CUSTOMER_ID, 3);
        if ($contact) {
            $data = ['HEMO_ID' => $this->ID, 'PATIENT_NAME' => $contact->NAME];
            $this->dispatch('open-nurse-notes', result: $data);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function openJournal()
    {
        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->hemoServices->object_type_hemo,
            $this->ID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }
    public function reJournal()
    {
        $this->hemoServices->getMakeJournal($this->ID);
        return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Re-create Journal');

    }
    public function openForm()
    {

        $data = [
            'HEMO_ID'     => $this->ID,
            'DATE'        => $this->DATE,
            'PATIENT_ID'  => $this->CUSTOMER_ID,
            'LOCATION_ID' => $this->LOCATION_ID,
        ];

        $this->dispatch('open-agreement-form', data: $data);
    }
    public function render()
    {
        return view('livewire.hemodialysis.hemo-form');
    }
}
