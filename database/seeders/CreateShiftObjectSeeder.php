<?php

namespace Database\Seeders;

use App\Models\ObjectTypeMap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateShiftObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $got = ObjectTypeMap::where('TABLE_NAME', 'SHIFT')->first();
        if ($got) {
            return;
        }
        
        $MAXID = (int) ObjectTypeMap::max('ID') + 1;
        
        ObjectTypeMap::create([
            'ID' => $MAXID,
            'NAME' => 'Shift',
            'TABLE_NAME' => 'SHIFT',
            'IS_DOCUMENT' => 0,
            'DOCUMENT_TYPE' => null,
            'NEXT_ID' => 1,
            'INCREMENT' => 1
        ]);
    }
}
