<?php

namespace BookStore\Infrastructure;

use Exception;

class Container
{
    private array $services = [];

    /**
     * Adds service to services array
     *
     * @param string $name
     * @param $service
     * @return void
     */
    public function set(string $name, $service): void
    {
        $this->services[$name] = $service;
    }

    /**
     * Returns service with provided name
     *
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function get(string $name): mixed
    {
        if (!isset($this->services[$name])) {
            throw new Exception("Service not found: $name");
        }

        return $this->services[$name];
    }
}