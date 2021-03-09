<?php

namespace DevUri\Config;

interface ConfigInterface
{

	/**
     * Runs config setup.
     *
     * Define in child class.
     *
	 * @param array|null $environment .
 	 * @param boolean $setup .
     */
	public function config($environment = null, $setup = true );

    /**
     * Debug Settings
     *
     * @return self
     */
   	public function debug(): ConfigInterface;

    /**
     * Symfony Debug.
     *
     * @param $enable
     * @return self
     */
	public function symfony_debug( bool $enable ): ConfigInterface;

	/**
	 * Site Url Settings
	 *
	 * @return self
	 */
	public function site_url(): ConfigInterface;

    /**
     * Uploads Directory Setting
     *
     * @return self
     */
	public function uploads(): ConfigInterface;

	/**
	 *  DB settings
	 *
	 * @return self
	 */
	public function database(): ConfigInterface;

	/**
	 * Optimize
	 *
	 * @return self
	 */
	public function optimize(): ConfigInterface;

	/**
	 * Memory Settings
	 *
	 * @return self
	 */
	public function memory(): ConfigInterface;

	/**
	 * Authentication Unique Keys and Salts
	 *
	 * @return self
	 */
	public function salts(): ConfigInterface;

	/**
	 * SSL
	 *
	 * @return self
	 */
	public function force_ssl(): ConfigInterface;

	/**
	 * AUTOSAVE and REVISIONS
	 *
	 * @return self
	 */
	public function autosave(): ConfigInterface;
}
