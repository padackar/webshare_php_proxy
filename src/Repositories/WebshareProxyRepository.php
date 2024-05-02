<?php

namespace omrva\WebsharePhpProxy\Repositories;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use omrva\WebsharePhpProxy\Models\ProxyServer;

class WebshareProxyRepository
{
    /** @var string */
    public static $authToken;

    /** @var string */
    public static $proxyListUrl = 'https://proxy.webshare.io/api/v2/proxy/list/?mode=direct&page=1&page_size=100';

    /** @var string  */
    public static $proxyListCacheKey = 'proxy_list';
    /** @var int */
    public static $proxyListCacheLifetimeInSeconds = 60 * 60 * 24;  // 1 day

    /**
     * @param string|null $authToken
     * @param bool $storeToCache
     * @return Collection
     * @throws Exception
     */
    public function createProxyList(string $authToken = null, bool $storeToCache = true): Collection
    {
        if (empty($authToken)) {
            $authToken = static::$authToken;
        }

        if (empty($authToken)) {
            throw new Exception('Authentication token not set.');
        }

        $curlUrl = static::$proxyListUrl;

        $list = new Collection();

        while (!empty($curlUrl)) {
            $curl = curl_init($curlUrl);

            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Token ' . $authToken
            ]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $resp = curl_exec($curl);

            if (curl_errno($curl)) {
                throw new Exception(curl_error($curl));
            }

            curl_close($curl);

            $proxyArr = json_decode($resp, true);
            foreach ($proxyArr['results'] as $proxyServer) {
//                $list[] = $proxyServer;
                $list[] = $this->makeProxyServerObject($proxyServer);
            }

            $curlUrl = $proxyArr['next'];
        }

        if ($storeToCache) {
            $this->storeToCache($list);
        }

        return $list;
    }

    /**
     * @return Collection
     * @throws Exception
     */
    public function getProxyList()
    {
        if (Cache::has(static::$proxyListCacheKey)) {
            return Cache::get(static::$proxyListCacheKey);
        }

        return $this->createProxyList();
    }

    /**
     * @return mixed|null
     * @throws Exception
     */
    public function getRandomProxyServer()
    {
        return $this->getProxyList()
            ->where('valid', true)
            ->shuffle()
            ->first();
    }

    /**
     * @param $list
     * @return void
     */
    public function storeToCache($list): void
    {
        Cache::put(static::$proxyListCacheKey, $list, static::$proxyListCacheLifetimeInSeconds);
    }

    /**
     * @param array $data
     * @return ProxyServer
     */
    public function makeProxyServerObject(array $data): ProxyServer
    {
        $obj = new ProxyServer();

        foreach ($data as $key => $value) {
            $obj->{$key} = $value;
        }

        return $obj;
    }
}