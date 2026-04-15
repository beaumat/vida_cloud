<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\ObjectCodeSequence;
use App\Models\ObjectTypeMap;

class ObjectServices
{
    public function ObjectTypeID(string $TABLE_NAME): int
    {

        try {

            return ObjectTypeMap::where('TABLE_NAME', '=', $TABLE_NAME)
                ->first()
                ->ID;
        } catch (\Exception $e) {
            //throw $th;
            dd("$TABLE_NAME :" . $e->getMessage());
        }
    }
    public function ObjectTypeIdByName(string $NAME): int
    {
        try {
            return ObjectTypeMap::where('NAME', '=', $NAME)
                ->first()->ID;
        } catch (\Exception $e) {

            dd("$NAME :" . $e->getMessage());
        }
    }
    public function Store(int $ID, string $NAME, string $TABLE_NAME, bool $IS_DOCUMENT, $DOCUMENT_TYPE)
    {
        ObjectTypeMap::create([
            'ID'            => $ID,
            'NAME'          => $NAME,
            'TABLE_NAME'    => $TABLE_NAME,
            'IS_DOCUMENT'   => $IS_DOCUMENT,
            'DOCUMENT_TYPE' => $DOCUMENT_TYPE,
            'NEXT_ID'       => 1,
            'INCREMENT'     => 1
        ]);
    }
    public function UpdateObject(string $TABLE_NAME, int $NEXT_ID_MUST)
    {
        ObjectTypeMap::where('TABLE_NAME', '=', $TABLE_NAME)
            ->update([
                'NEXT_ID' => $NEXT_ID_MUST
            ]);
    }
    public function ObjectNextID(string $TABLE_NAME): int
    {
        $Nxt_ID = 0;
        $result = ObjectTypeMap::where('TABLE_NAME', '=', $TABLE_NAME)->first();

        if ($result) {
            $Nxt_ID = $result->NEXT_ID;

            $this->UpdateObject($TABLE_NAME, $Nxt_ID + 1);

            return $Nxt_ID;
        } else {

            dd("$TABLE_NAME table not found . please try again");
            // Auto Create
            return 1;
        }
    }
    public function ObjectNextIdByName(string $NAME): int
    {
        $Nxt_ID = 0;
        $result = ObjectTypeMap::where('TABLE_NAME', $NAME)->first();
        if ($result) {
            $Nxt_ID = $result->NEXT_ID;
        } else {
            dd("$NAME name not found");
        }
        ObjectTypeMap::where('NAME', '=', $NAME)
            ->update([
                'NEXT_ID' => ($Nxt_ID + 1)
            ]);

        return $Nxt_ID;
    }
    public function GetSequence(int $Type, $LocationId): string
    {
        $data = ObjectCodeSequence::where('OBJECT_TYPE', '=', $Type)
            ->where('LOCATION_ID', '=', $LocationId)
            ->first();

        if ($data) {
            $this->SetSequence(
                $data->ID,
                $data->NEXT_SEQUENCE,
                $data->INCREMENT
            );

            return $this->codeFormat(
                $LocationId,
                $data->NEXT_SEQUENCE,
                $data->WIDTH,
                $data->POSTFIX,
                $data->PREFIX
            );
        }

        $this->NewSequence(1, $Type, (int) $LocationId, 1, null, null, 4);

        return $this->GetSequence($Type, $LocationId);
    }
    public function SetSequence(int $ID, int $NEXT_SEQUENCE, int $INCREMENT)
    {
        ObjectCodeSequence::where('ID', $ID)
            ->where('NEXT_SEQUENCE', $NEXT_SEQUENCE)
            ->update(['NEXT_SEQUENCE' => $NEXT_SEQUENCE + $INCREMENT]);
    }
    public function NewSequence(
        int $NEXT_SEQUENCE,
        int $OBJECT_TYPE,
        int $LOCATION_ID,
        int $INCREMENT,
        $PREFIX,
        $POSTFIX,
        int $WIDTH
    ) {

        ObjectCodeSequence::create([
            'NEXT_SEQUENCE'     => $NEXT_SEQUENCE,
            'OBJECT_TYPE'       => $OBJECT_TYPE,
            'LOCATION_ID'       => $LOCATION_ID > 0 ? $LOCATION_ID : null,
            'INCREMENT'         => $INCREMENT,
            'PREFIX'            => $PREFIX,
            'POSTFIX'           => $POSTFIX,
            'WIDTH'             => $WIDTH
        ]);
    }
    public function codeFormat($LOCATION_ID, int $SEQUENCE, int $WIDTH, $POSTFIX, $PREFIX): string
    {
        if ($LOCATION_ID) {
            $loc = Str::padLeft($LOCATION_ID, 3, '0');
            $data = "$loc-$POSTFIX" . Str::padLeft($SEQUENCE, $WIDTH, '0') . "$PREFIX";
            return trim($data);
        }

        return $POSTFIX ?? '' . Str::padLeft($SEQUENCE, $WIDTH, '0') . $PREFIX ?? '';
    }
}
