<?php declare(strict_types=1);

namespace App\Controllers;

use App\DI;
use App\Models\Page;
use Dewep\Exception\HttpException;
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
                'title' => 'Home',
                'text'  => 'This is - Home',
            ]
        );
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return string
     * @throws HttpException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function page(Request $request, string $id): string
    {
        $query = <<<'SQL'
    SELECT * FROM `page` WHERE `id`=:id;
SQL;

        /** @var Page|false $page */
        $page = DI::db()->select($query, [':id' => (int)$id])
            ->asClass(Page::class)
            ->getOne();

        if (!$page instanceof Page) {
            throw new HttpException('Page not found', 404);
        }

        return DI::twig()->render(
            'page.html.twig',
            [
                'title' => $page->getTitle(),
                'text'  => $page->getText(),
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
