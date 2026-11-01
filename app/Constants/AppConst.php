<?php

namespace App\Constants;

class AppConst
{
    const ALLOW_HOST = [
        '127.0.0.1:8000',
        'demo-integrasi-ai.ganadev.com',
        'integrasi-ai.rudaya.id',
        'govaihei.rudaya.id',
'aimetrik.my.id',
    ];

    const LEVEL_MAP = [
        '0' => 'Level 0',
        '1' => 'Level 1',
        '2' => 'Level 2',
        '3' => 'Level 3',
        '4' => 'Level 4',
        '5' => 'Level 5',
    ];

    const LEVEL_ARR = [0, 1, 2, 3, 4, 5];

    const CODE_NAME = [
        'domain' => 'D',
        'bidang_kapabilitas' => 'BK',
        'bidang_praktik' => 'BP',
    ];

    const NPLF_ARR = [
        'N' => 'Not Achieved',
        'P' => 'Partially Achieved',
        'L' => 'Large Achieved',
        'F' => 'Fully Achieved',
    ];

    const NPLF_VAL = [
        'N' => 0,
        'P' => 1,
        'L' => 2,
        'F' => 3,
    ];

    const NPLF_MAKS_MIN = [
        self::NPLF_VAL['N'] => [
            'min' => 0,
            'max' => 14,
        ],
        self::NPLF_VAL['P'] => [
            'min' => 15,
            'max' => 49,
        ],
        self::NPLF_VAL['L'] => [
            'min' => 50,
            'max' => 99,
        ],
        self::NPLF_VAL['F'] => [
            'min' => 100,
            'max' => 100,
        ],
    ];
}
