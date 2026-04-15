<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\InventoryAdjustmentType;

class InventoryAdjustmentTypeServices
{
    private $objectService;
    private $usersLogServices;

    public function __construct(ObjectServices $objectService, UsersLogServices $usersLogServices)
    {
        $this->objectService    = $objectService;
        $this->usersLogServices = $usersLogServices;
    }
    public function getList()
    {
        return InventoryAdjustmentType::query()->select(['ID', 'DESCRIPTION'])->get();
    }
    public function getAccountId(int $ID): int
    {
        $data = InventoryAdjustmentType::where('ID', $ID)->first();
        if ($data) {
            return (int) $data->ACCOUNT_ID;
        }
        return 0;
    }
    public function Store(string $CODE, string $DESCRIPTION, int $ACCOUNT_ID): int
    {
        $ID = $this->objectService->ObjectNextID('INVENTORY_ADJUSTMENT_TYPE');

        InventoryAdjustmentType::create([
            'ID'          => $ID,
            'CODE'        => $CODE,
            'DESCRIPTION' => $DESCRIPTION,
            'ACCOUNT_ID'  => $ACCOUNT_ID,
        ]);
        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::INVENTORY_ADJUSTMENT_TYPE, $ID);
        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $ACCOUNT_ID): void
    {
        InventoryAdjustmentType::where('ID', $ID)->update([
            'CODE'        => $CODE,
            'DESCRIPTION' => $DESCRIPTION,
            'ACCOUNT_ID'  => $ACCOUNT_ID,
        ]);

        $this->usersLogServices->AddLogs(TransType::UPDATE, LogEntity::INVENTORY_ADJUSTMENT_TYPE, $ID);
    }

    public function Delete(int $ID): void
    {
        InventoryAdjustmentType::where('ID', $ID)->delete();
        $this->usersLogServices->AddLogs(TransType::DELETE, LogEntity::INVENTORY_ADJUSTMENT_TYPE, $ID);
    }
    public function Search($search)
    {
        return InventoryAdjustmentType::query()
            ->select([
                'inventory_adjustment_type.ID',
                'inventory_adjustment_type.CODE',
                'inventory_adjustment_type.DESCRIPTION',
                'account.NAME as ACCOUNT',
            ])
            ->join('account', 'account.ID', '=', 'inventory_adjustment_type.ACCOUNT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('inventory_adjustment_type.CODE', 'like', '%' . $search . '%')
                    ->orWhere('inventory_adjustment_type.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('account.NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->get();
    }
}
