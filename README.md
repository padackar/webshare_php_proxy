# Webshare proxy repository

## Description
Repository for work with Webshare proxy servers.

Service stores list of proxy servers in cache to decrease response time. \
Default cache time is 1 day.

## Settings
`WebshareProxyRepository` class has few properties that needs to be set for correct functionality. \
These properties are declared as `static` so simple way how to set those is to extend 
`WebshareProxyRepository` and set static values.

#### Properties:
- `$authToken` - your authentication token for Webshare API
- `$proxyListUrl` - URL for getting list of proxy servers (if different than usual)
- `$proxyListCacheKey` - cache key for storing list of proxy server with data
- `$proxyListCacheLifetimeInSeconds` - proxy list cached data lifetime (in seconds)

## Methods
`createProxyList` - creates, stores and returns newly obtained proxy list from Webshare API

`getProxyList` - returns proxy list from cache (if available) or calls `createProxyList` method and returns its result

`getRandomProxyServer` - returns random server from list (obtained from `getProxyList` method)