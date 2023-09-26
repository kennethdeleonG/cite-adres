<?php

declare(strict_types=1);

namespace App\Support\Concerns;

trait FileActions
{
    public function getFileActions(): array
    {
        return [
            [
                'action' => 'open',
                'label' => 'Open',
            ],
            [
                'action' => 'download',
                'label' => 'Download',
            ],
            [
                'action' => 'delete',
                'label' => 'Delete',
            ],
            [
                'action' => 'edit',
                'label' => 'Edit',
            ],
            [
                'action' => 'move-to',
                'label' => 'Move to',
            ],
            [
                'action' => 'view-details',
                'label' => 'View Details',
            ],
        ];
    }
}
