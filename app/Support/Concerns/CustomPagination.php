<?php

declare(strict_types=1);

namespace App\Support\Concerns;

trait CustomPagination
{
    //folders
    public bool $loadMoreFoldersReached = false;
    public mixed $folderList;
    public int $folderCount = 0;
    public int $folderPage = 1;
}
