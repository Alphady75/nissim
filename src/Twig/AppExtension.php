<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use App\Repository\PostRepository;
use App\Repository\ProjetRepository;
use App\Repository\FinancementRepository;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $postRepository;

    private $projetRepository;

    private $financementRepository;

    public function __construct(PostRepository $postRepository, ProjetRepository $projetRepository, FinancementRepository $financementRepository){
        $this->postRepository = $postRepository;
        $this->projetRepository = $projetRepository;
        $this->financementRepository = $financementRepository;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('lastposts', [$this, 'getLastPost']),
            new TwigFunction('lastprojets', [$this, 'getLastProjets']),
            new TwigFunction('userfinancements', [$this, 'getUserFinancements']),
        ];
    }

    public function getUserFinancements($user, $limit): array
    {
        return $this->financementRepository->findByUser($user, 5);
    }

    public function getLastPost(): array
    {
        return $this->postRepository->findBy([
            'online'    =>  1
        ], ['created'    =>  'DESC'], 4);
    }

    public function getLastProjets(): array
    {
        return $this->projetRepository->findBy([
            'visible'    =>  1,
            'fStatut'  => 0,
        ], ['created'    =>  'DESC'], 4);
    }
}
