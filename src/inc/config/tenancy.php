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
