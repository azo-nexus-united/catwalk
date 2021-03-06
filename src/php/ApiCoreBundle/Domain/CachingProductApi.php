<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductApiBundle\Domain\ProductType;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\SimpleCache\CacheInterface;

class CachingProductApi implements ProductApi
{
    /**
     * @var ProductApi
     */
    private $aggregate;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @bool
     */
    private $debug;

    public function __construct(ProductApi $aggregate, CacheInterface $cache, $debug = false)
    {
        $this->aggregate = $aggregate;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function getAggregate(): ProductApi
    {
        return $this->aggregate;
    }

    /**
     * @param CategoryQuery $query
     * @return Category[]
     */
    public function getCategories(CategoryQuery $query): array
    {
        $cacheKey = 'frontastic.categories.' . md5(json_encode($query));
        if ($this->debug || !($result = $this->cache->get($cacheKey, false))) {
            $result = $this->aggregate->getCategories($query);
            $this->cache->set($cacheKey, $result, 600);
        }

        return $result;
    }

    public function queryCategories(CategoryQuery $query): Result
    {
        $categories = $this->getCategories($query);

        return new Result([
            'count' => count($categories),
            'items' => $categories,
            'query' => clone($query),
        ]);
    }

    /**
     * @param ProductTypeQuery $query
     * @return ProductType[]
     */
    public function getProductTypes(ProductTypeQuery $query): array
    {
        $cacheKey = 'frontastic.types.' . md5(json_encode($query));
        if ($this->debug || !($result = $this->cache->get($cacheKey, false))) {
            $result = $this->aggregate->getProductTypes($query);
            $this->cache->set($cacheKey, $result, 600);
        }

        return $result;
    }

    public function getProduct($query, string $mode = self::QUERY_SYNC): ?object
    {
        // Do NOT cache product detail information, since it usually is crucial
        // to present the user with up-to-date information on the product
        // detail page.
        return $this->aggregate->getProduct($query, $mode);
    }

    /**
     * @param ProductQuery $query
     * @param string $mode One of the QUERY_* connstants. Execute the query synchronously or asynchronously?
     * @return Result|PromiseInterface A result when the mode is sync and a promise if the mode is async.
     */
    public function query(ProductQuery $query, string $mode = self::QUERY_SYNC): object
    {
        // We don't need to cache the result here since they will be cached inside the `ProductSearchApi`.
        return $this->aggregate->query($query, $mode);
    }

    /**
     * Get *dangerous* inner client
     *
     * This method exists to enable you to use features which are not yet part
     * of the abstraction layer.
     *
     * Be aware that any usage of this method might seriously hurt backwards
     * compatibility and the future abstractions might differ a lot from the
     * vendor provided abstraction.
     *
     * Use this with care for features necessary in your customer and talk with
     * Frontastic about provising an abstraction.
     *
     * @return mixed
     */
    public function getDangerousInnerClient()
    {
        return $this->aggregate->getDangerousInnerClient();
    }
}
