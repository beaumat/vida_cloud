<?php
namespace App\Services;

use App\Models\PostingLog;

class PostingLogServices
{
    public function logPosting($date)
    {
        // Logic to log the posting date
        // This is a placeholder implementation
        if ($this->positngExist($date)) {
            return;
        }

        PostingLog::create(['DATE' => $date]);
    }
    private function positngExist($date): bool
    {
        return (bool) PostingLog::where('DATE', $date)->exists();
    }
}
