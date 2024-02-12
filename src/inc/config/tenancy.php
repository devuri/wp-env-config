<?php

/**
 * Specifies the UUID of the main site (also known as the landlord) in a multi-tenant setups.
 *
 * This constant should be assigned the UUID value of the primary tenant that acts as the landlord.
 * Setting this value is crucial for identifying the main tenant in a multi-tenant configuration.
 */
\define( 'LANDLORD_UUID', null );

/*
 * Controls the activation of multi-tenant capabilities within the WordPress environment.
 *
 * When enabled, this feature allows the operation of multiple WordPress sites from a single installation,
 * facilitating a multi-tenant architecture. It's essential to ensure proper configuration of tenant-specific
 * settings and understand the implications of a multi-tenant setup prior to enabling.
 *
 * For comprehensive guidance on configuring a multi-tenant environment, refer to the official documentation:
 * @link https://devuri.github.io/wp-env-config/multi-tenant/
 */
\define( 'ALLOW_MULTITENANT', false );

/*
 * Determines the handling of tenant-specific configurations in a multi-tenant application.
 *
 * When set to `true`, the application enforces a strict requirement where each tenant must
 * have their own `config/{tenant_id}/app.php` file. If a tenant-specific configuration file
 * is not found, the application will throw an exception, indicating the necessity for tenant
 * specific configurations.
 *
 * This ensures that each tenant has explicitly defined settings and
 * does not fall back to using a shared or default configuration, enhancing security and
 * customization for each tenant.
 */
\define( 'REQUIRE_TENANT_CONFIG', false );
