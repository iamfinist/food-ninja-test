<?php

namespace App\Services;

use App\Models\Click;

class ClickService
{
    public function record(int $linkId, ?string $ipAddress): void
    {
        Click::create([
            'link_id' => $linkId,
            'ip_address' => $ipAddress,
        ]);
    }
}
