<?php

namespace Urisoft\App;

interface ConfigInterface
{
    /**
     * Runs config setup.
     *
     * Define in child class.
     *
     * @param null|string[] $environment .
     * @param bool          $setup       .
     */
    public function config( $environment = null, bool $setup = true ): self;

    /**
     * Debug Settings.
     *
     * @param false|string $error_log_dir
     *
     * @return self
     */
    public function debug( $error_log_dir ): self;

    /**
     * Symfony Debug.
     *
     * @param $enable
     *
     * @return self
     */
    public function set_error_handler( ?string $handler = null ): self;

    /**
     * Site Url Settings.
     *
     * @return self
     */
    public function site_url(): self;

    /**
     *  DB settings.
     *
     * @return self
     */
    public function database(): self;

    /**
     * Optimize.
     *
     * @return self
     */
    public function optimize(): self;

    /**
     * Memory Settings.
     *
     * @return self
     */
    public function memory(): self;

    /**
     * Authentication Unique Keys and Salts.
     *
     * @return self
     */
    public function salts(): self;

    /**
     * SSL.
     *
     * @return self
     */
    public function force_ssl(): self;

    /**
     * AUTOSAVE and REVISIONS.
     *
     * @return self
     */
    public function autosave(): self;
}
