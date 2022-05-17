<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @method static static Lan()
 * @method static static Wan()
 */
final class RouterIPType extends Enum implements LocalizedEnum
{
    const Lan =   'lan';
    const Wan =   'wan';
}
