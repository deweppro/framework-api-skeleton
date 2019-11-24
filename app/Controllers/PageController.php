<?php declare(strict_types=1);

namespace App\Controllers;

use App\DI;
use Dewep\Http\Request;

/**
 * Class PageController
 *
 * @package App\Controllers
 */
class PageController
{
    /**
     * @param Request $request
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(Request $request): string
    {
        return DI::twig()->render(
            'page.html.twig',
            [
                'name' => 'home',
            ]
        );
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function page(Request $request, string $id): string
    {
        return DI::twig()->render(
            'page.html.twig',
            [
                'name' => $id,
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function api(Request $request): array
    {
        return $request->getCookie()->all();
    }
}
