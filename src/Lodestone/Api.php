<?php

namespace Lodestone;

use Lodestone\Http\AsyncHandler;
use Lodestone\Http\Http;
use Lodestone\Http\RequestConfig;
use Lodestone\Api\{
    ApiAbstract,
    Character,
    FreeCompany,
    Linkshell,
    PvPTeam,
    Lodestone,
    Leaderboards,
    DevPosts
};
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
    private $namespaces = [];

    /**
     * @var HttpClientInterface|null
     */
    private $httpClient;

    public function __construct(?HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    public function setHttpClient(?HttpClientInterface $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @template T
     *
     * @param class-string<T> $namespace
     *
     * @return T
     *
     * will return an existing set namespace or a new one.
     */
    private function getApiNamespace(string $namespace)
    {
        if (!isset($this->namespaces[$namespace])) {
            $class = new $namespace();

            if (isset($this->httpClient) && $class instanceof ApiAbstract) {
                $class->setHttpClient($this->httpClient);
            }

            $this->namespaces[$namespace] = $class;
        }

        return $this->namespaces[$namespace];
    }

    public function requestId(string $name)
    {
        AsyncHandler::setRequestId($name);
        return $this;
    }

    /**
     * Access the Lodestone API Configuration
     */
    public function config(): RequestConfig
    {
        return $this->getApiNamespace(RequestConfig::class);
    }

    public function http(): Http
    {
        return $this->getApiNamespace(Http::class);
    }

    public function character(): Character
    {
        return $this->getApiNamespace(Character::class);
    }

    public function freecompany(): FreeCompany
    {
        return $this->getApiNamespace(FreeCompany::class);
    }

    public function linkshell(): Linkshell
    {
        return $this->getApiNamespace(Linkshell::class);
    }

    public function pvpteam(): PvPTeam
    {
        return $this->getApiNamespace(PvPTeam::class);
    }

    public function lodestone(): Lodestone
    {
        return $this->getApiNamespace(Lodestone::class);
    }

    public function devposts(): DevPosts
    {
        return $this->getApiNamespace(DevPosts::class);
    }

    public function leaderboards(): Leaderboards
    {
        return $this->getApiNamespace(Leaderboards::class);
    }
}
