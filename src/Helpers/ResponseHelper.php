<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Helpers\MessagesHelper;

class ResponseHelper extends MessagesHelper
{
    public function mount(int $status, string $code, array $options = []): array
    {
        return [
            'status' => $status,
            'payload' => [
                'message' => $this->getMessage($code),
                'details' => $options
            ]
        ];
    }
}
