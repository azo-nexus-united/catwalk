<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

class RouteService
{
    private $cacheDirectory;

    public function __construct(string $cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\Route[]
     */
    public function getRoutes(): array
    {
        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        return [];
    }

    public function storeRoutes(array $routes): void
    {
        file_put_contents(
            $this->getCacheFile(),
            '<?php return ' . var_export($routes, true) . ';'
        );

        // @HACK There seems not to be a sane way to rebuild just the route
        // cache so that we force rebuild by removing the old cache files
        foreach (glob($this->cacheDirectory . '/*Url*') as $routerCacheFile) {
            unlink($routerCacheFile);
        }
    }

    protected function getCacheFile(): string
    {
        return $this->cacheDirectory . '/frontastic_frontent_routes.php';
    }

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\Node[] $nodes
     */
    public function rebuildRoutes(array $nodes): void
    {
        $this->storeRoutes(array_values($this->generateRoutes($nodes)));
    }

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\Node[] $nodes
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\Route[]
     */
    public function generateRoutes(array $nodes): array
    {
        $routes = [];

        foreach ($nodes as $node) {
            if (empty($node->configuration['path'])) {
                // Ignore routes where no path is defined. Those are probably
                // not supposed to be rendered.
                continue;
            }

            $parents = array_filter(explode('/', $node->path));
            $route = '/' . trim($node->configuration['path'] ?? '', '/');

            if (!count($parents)) {
                $routes[$node->nodeId] = new Route([
                    'nodeId' => $node->nodeId,
                    'route' => $route,
                ]);
                continue;
            }

            $parent = end($parents);
            if (!isset($routes[$parent])) {
                // Just ignore routes without parents – this just might happen
                // from time to time because of temporary inconsistencies
                continue;
            }

            $routes[$node->nodeId] = new Route([
                'nodeId' => $node->nodeId,
                'route' => rtrim($routes[$parent]->route, '/') . $route,
            ]);
        }
        return $routes;
    }
}
