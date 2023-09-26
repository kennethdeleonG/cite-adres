<?php

return [

    'resource' => [
        'filament-resource' => AlexJustesen\FilamentSpatieLaravelActivitylog\Resources\ActivityResource::class,
        'group' => 'System',
        'sort'  => 4,
    ],

    'paginate' => [5, 10, 25, 50],

];
