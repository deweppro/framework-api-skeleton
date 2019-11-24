<?php declare(strict_types=1);

namespace App;

/**
 * Class HttpCodes
 *
 * @package App
 */
class HttpCodes
{
    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function httpCode404(): string
    {
        return DI::twig()->render('404.html.twig', []);
    }
}
