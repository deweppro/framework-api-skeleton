<?php declare(strict_types=1);

namespace App;

use Dewep\Http\Response;

/**
 * Class HttpCodes
 *
 * @package App
 */
class HttpCodes
{
    /**
     * @param array $error
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public static function httpCode404(array $error): string
    {
        return DI::twig()->render(
            '404.html.twig',
            [
                'text' => $error['errorMessage'] ?? 'Error 404',
            ]
        );
    }
}
