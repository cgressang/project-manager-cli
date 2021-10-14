<?php declare(strict_types=1);

namespace Pmc\Commands\PHP;

/**
 * Laravel constants
 */
class Laravel
{
    const PACKAGE = 'laravel/laravel';

    const VERION_SIX = '6';
    const VERSION_EIGHT = '8';

    const ACTIVE_VERSIONS = [
        6 => '6.*',
        8 => '8.*'
    ];
}