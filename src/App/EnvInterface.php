<?php

namespace Urisoft\App;

/**
 * Interface for managing different environment settings.
 */
interface EnvInterface
{
    /**
     * Configure the environment for secure production.
     *
     * This method should be used in a secure production environment.
     */
    public function env_secure(): void;

    /**
     * Configure the environment for production.
     *
     * This method should be used in a production environment.
     */
    public function env_production(): void;

    /**
     * Configure the environment for staging.
     *
     * This method should be used in a staging environment.
     */
    public function env_staging(): void;

    /**
     * Configure the environment for development.
     *
     * This method should be used in a development environment.
     */
    public function env_development(): void;

    /**
     * Configure the environment for debugging.
     *
     * This method should be used for debugging purposes.
     */
    public function env_debug(): void;
}
