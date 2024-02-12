<?php

namespace Urisoft\App\Traits;

trait TenantTrait
{
    /**
     * Retrieves the path for a tenant-specific file, with an option to enforce strict finding.
     *
     * In a multi-tenant application, this function attempts to find a file specific to the current tenant.
     * If the file is not found and 'find_or_fail' is set to true, the function will return null.
     * If the tenant-specific file does not exist (and 'find_or_fail' is false), it falls back to a default file path.
     * If neither file is found, or the application is not in multi-tenant mode, null is returned.
     *
     * @param string $file         The name of the file (without the .php extension).
     * @param string $dir          The directory within the app path where the file should be located.
     * @param bool   $find_or_fail Whether to fail if the tenant-specific file is not found.
     *
     * @return null|string The path to the file if found, or null otherwise.
     */
    public function get_tenant_file_path( string $file, string $dir, bool $find_or_fail = false ): ?string
    {
        if ( $this->is_multitenant_app() && \defined( 'APP_TENANT_ID' ) ) {
            $tenant_id = APP_TENANT_ID;
        } else {
            return null;
        }

        // Construct the path for the tenant-specific file
        $tenant_file_path = "{$this->path}/{$file}.php";

        // Check for the tenant file's existence
        if ( file_exists( $tenant_file_path ) ) {
            return $tenant_file_path;
        }
        if ( $find_or_fail ) {
            return null;
        }

        // Construct the path for the fallback/default file
        $fallback_file_path = "{$dir}/config/{$file}.php";

        // Return the fallback file path if it exists
        return file_exists( $fallback_file_path ) ? $fallback_file_path : null;
    }

    /**
     * Determines the env file application path, accounting for multi-tenancy.
     *
     * @param string $base_path The base application directory path.
     *
     * @return string The determined application path.
     */
    protected function determine_envpath( $base_path ): string
    {
        if ( $this->is_multitenant_app() && \defined( 'APP_TENANT_ID' ) ) {
            $config_dir = SITE_CONFIG_DIR;

            return "{$base_path}/{$config_dir}/" . APP_TENANT_ID;
        }

        return $base_path;
    }

    /**
     * Determines if the application is configured to operate in multi-tenant mode.
     *
     * This is based on the presence and value of the `ALLOW_MULTITENANT` constant.
     * If `ALLOW_MULTITENANT` is defined and set to `true`, the application is
     * considered to be in multi-tenant mode.
     *
     * @return bool Returns `true` if the application is in multi-tenant mode, otherwise `false`.
     */
    protected function is_multitenant_app(): bool
    {
        return \defined( 'ALLOW_MULTITENANT' ) && ALLOW_MULTITENANT === true;
    }
}
