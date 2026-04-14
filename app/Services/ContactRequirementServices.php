<?php

namespace App\Services;

use App\Models\ContactRequirements;
use App\Models\Requirements;
use Illuminate\Support\Carbon;

class ContactRequirementServices
{

    private $objectService;
    private $dateServices;
    public function __construct(ObjectServices $objectService, DateServices $dateServices)
    {
        $this->objectService = $objectService;
        $this->dateServices = $dateServices;
    }
    public function DeletePatient(int $id)
    {
        ContactRequirements::where('CONTACT_ID', '=', $id)->delete();
    }
    public function AutoCreateList(int $CONTACT_ID)
    {
        $data = Requirements::where('INACTIVE', 0)->get();
        foreach ($data as $list) {
            $this->Store($CONTACT_ID, $list->ID);
        }
    }
    public function Store(int $CONTACT_ID, int $REQUIREMENT_ID)
    {
        $ID = $this->objectService->ObjectNextID('CONTACT_REQUIREMENT');
        ContactRequirements::create([
            'ID' => $ID,
            'CONTACT_ID' => $CONTACT_ID,
            'REQUIREMENT_ID' => $REQUIREMENT_ID
        ]);
    }
    public function UpdateIsComplete(int $ID, bool $VALUE)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'IS_COMPLETE' => $VALUE,
                'DATE_COMPLETED' => $VALUE ? $this->dateServices->NowDate() : null,
                'NOT_APPLICABLE' => false
            ]);
    }
    public function UpdateNotApplicable(int $ID, bool $VALUE)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'IS_COMPLETE' => false,
                'DATE_COMPLETED' => null,
                'NOT_APPLICABLE' => $VALUE
            ]);
    }
    public function UpdateMarking(int $ID, bool $IS_COMPLETE, bool $NOT_APPLICABLE)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'IS_COMPLETE' => $IS_COMPLETE,
                'DATE_COMPLETED' => $IS_COMPLETE ? $this->dateServices->NowDate() : null,
                'NOT_APPLICABLE' => $NOT_APPLICABLE
            ]);
    }
    public function GetCountRequirement(int $CONTACT_ID): int
    {
        return (int) ContactRequirements::where('CONTACT_ID', $CONTACT_ID)
            ->where('IS_COMPLETE', 0)
            ->where('NOT_APPLICABLE', 0)
            ->count();
    }
    public function GetList(int $CONTACT_ID)
    {
        return ContactRequirements::query()
            ->select([
                'contact_requirement.ID',
                'r.DESCRIPTION',
                'contact_requirement.REQUIREMENT_ID',
                'contact_requirement.IS_COMPLETE',
                'contact_requirement.NOT_APPLICABLE',
                'contact_requirement.FILE_NAME',
                'contact_requirement.FILE_PATH',
                'contact_requirement.FILE_CONFIRM_DATE'

            ])
            ->leftJoin('requirement as r', 'r.ID', '=', 'contact_requirement.REQUIREMENT_ID')
            ->where('contact_requirement.CONTACT_ID', $CONTACT_ID)
            ->get();
    }

    public function UpdateFile(int $ID, string $FILE_NAME, string $FILE_PATH)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,

            ]);
    }
    public function UpdateRemoveFile(int $ID)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'FILE_NAME' => null,
                'FILE_PATH' => null,
                'FILE_CONFIRM_DATE' => null
            ]);
    }
    public function FileConfirmDate(int $ID)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'FILE_CONFIRM_DATE' => Carbon::now()
            ]);
    }
    public function pdpIsComplete(int $CONTACT_ID)
    {
        return ContactRequirements::where('IS_COMPLETE', 1)
            ->where('CONTACT_ID', $CONTACT_ID)
            ->where('REQUIREMENT_ID', 10)
            ->exists();

    }
    public function pdpIsUploaded($CONTACT_ID)
    {
        return ContactRequirements::where('IS_COMPLETE', 1)
            ->where('CONTACT_ID', $CONTACT_ID)
            ->where('REQUIREMENT_ID', 10)
            ->whereNotNull('FILE_NAME')
            ->whereNotNull('FILE_PATH')
            ->exists();
    }
} 
