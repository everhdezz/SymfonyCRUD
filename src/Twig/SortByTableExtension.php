<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SortByTableExtension extends AbstractExtension
{
    private $router;
    private $request;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;

        $this->request = Request::createFromGlobals();
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sort_by_path', [$this, 'sort_by_path']),
            new TwigFunction('sort_by_arrows', [$this, 'sort_by_arrows']),
        ];
    }

    public function sort_by_path($route, $column)
    {
        $sort_type = $this->request->query->get('sort_by') == $column && $this->request->query->get('sort_type') == 'asc' ? 'desc' : 'asc';

        return $this->router->generate($route, [ 'sort_by' => $column, 'sort_type' => $sort_type ]);
    }

    public function sort_by_arrows($column)
    {
        if($this->request->query->get('sort_by') != $column)
            return '';

        return $this->request->query->get('sort_type') == 'asc' ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>';
    }
}
