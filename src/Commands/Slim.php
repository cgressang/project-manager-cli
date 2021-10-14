<?php declare(strict_types=1);

namespace Pmc\Commands;

/**
 * Slim constants
 */
class Slim
{
    const PACKAGE = 'slim/slim:4.*';

    const SLIM = 'slim';
    const NYHOLM = 'nyholm';
    const GUZZLE = 'guzzle';
    const LAMINAS = 'laminas';

    const PSR_PACKAGES = [
        self::SLIM => ['slim/psr7'],
        self::NYHOLM => ['nyholm/psr7', 'nyholm/psr7-server'],
        self::GUZZLE => ['guzzlehttp/psr7', 'http-interop/http-factory-guzzle'],
        self::LAMINAS => ['laminas/laminas-diactoros'],
    ];
}