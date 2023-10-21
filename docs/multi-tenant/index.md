# Multi-Tenant Applications

## Overview

This documentation explains the architecture and flow of our Multi-Tenant SaaS application. 
The application supports multiple tenants (websites) on a shared platform, allowing each tenant to have its own configuration and customization.

## Domain to Tenant Mapping

- Each tenant is identified by a unique Universal Unique Identifier (UUID).
- The domain of a tenant, as extracted from the HTTP_HOST, is the key to mapping to the tenant's UUID. For example:

`example.com => 48562e29-dc29-4aad-aca2-9a345ea9515c`

  > This is usually done in bootstrap.php see env app: https://github.com/devuri/wp-env-app/blob/main/bootstrap.php

```php
// the 'app' param is required.
$http_app = wpc_app(__DIR__, 'app', ['example.com' => '2f7c4eab-9b8c-486e-b6d3-f8be67e5bf09'] );
```

## Configuration Files

- The UUID is used as the primary indicator to locate the configuration files specific to each tenant.
- The configuration files are located in the following directory structure:
  - `.env` file: `"path/sites/48562e29-dc29-4aad-aca2-9a345ea9515c/.env"`
  - `config.php` file: `"path/sites/48562e29-dc29-4aad-aca2-9a345ea9515c/config.php"`
  
## Tenant-Specific Environment Variables

- The `.env` file for each tenant includes tenant-specific environment variables.
- Examples of environment variables included:
  - `APP_TENANT_ID=48562e29-dc29-4aad-aca2-9a345ea9515c`
  - `IS_MULTI_TENANT_APP=true`
  - Optionally, `APP_TENANT_SECRET`

## Handling Tenant-Specific Configuration

- Developers can use the `env('APP_TENANT_ID')` function to easily access tenant-specific configuration throughout the application.
- This allows for easier configuration adjustments based on the specific tenant.

## Separate Uploads Directory

- Each tenant has a separate uploads directory for media files.
- Uploads are located in the equivalent of `wp-content/tenant_id/uploads` (this directory may be diffrerent based on framework configuration the default is app/tenant_id/uploads), where `tenant_id` corresponds to the UUID of the tenant.
- To enhance security, it's advisable to encode the tenant UUID, as it might be visible in URLs for uploaded files. This can be done using the `APP_TENANT_SECRET` or by encoding it as a base64-encoded string.

## Shared Plugins and Themes

- Plugins and themes are shared across tenants for simplicity and resource optimization.
- The location of shared plugins and themes is defined in the `app.php` and the framework's `composer.json` file.

## Managing Plugin and Theme Availability

- An MU (Must Use) plugin is utilized to manage the availability of plugins and themes for each tenant.
- Availability can be easily managed based on the site/tenant ID.
- The `IS_MULTI_TENANT_APP` environment variable can be used to determine how plugins and themes are made available.
- This feature is particularly useful for migrations when exporting a tenant as a stand-alone installation.

This architecture for the multi-tenant application enables efficient management and customization for each tenant while maintaining security and resource sharing. 
Developers can easily work with tenant-specific configurations and achieve robust control over tenant-specific files and settings.


**Please Note: This documentation is a work in progress, and we welcome contributions and improvements. If you have suggestions or identify areas that need clarification, feel free to submit pull requests or reach out to our development team. 
Your feedback is valuable in enhancing the quality of this documentation.**
