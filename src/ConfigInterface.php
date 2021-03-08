<?php

namespace DevUri\Config;

interface ConfigInterface
{

    /**
     * Runs config setup.
     *
     * @param array|string $setup
     * @return void
     */
	public function config($setup): void;

    /**
     * Debug Settings
     *
     * @param $environment
     * @return self
     */
   	public function debug( $environment ): ConfigInterface;

    /**
     * Symfony Debug.
     *
     * @param $enable
     * @return self
     */
	public function symfony_debug( $enable ): ConfigInterface;

	/**
	 * Site Url Settings
	 *
	 * @return self
	 */
	public function site_url(): ConfigInterface;

    /**
     * Uploads Directory Setting
     *
     * @param $upload_dir
     * @return self
     */
	public function uploads( string $upload_dir ): ConfigInterface;

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
