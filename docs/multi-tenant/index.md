# Multi-Tenant Application

### Step 1: Backup Your Site
Before making any changes, ensure you have a full backup of your WordPress files and database.

### Step 2: Enable Multisite in `wp-config.php`
- Locate your `wp-config.php` file in the directory of your WordPress installation.
- Update the following line setting `ALLOW_MULTITENANT` as `true`: [WordPress config file](https://github.com/devuri/wp-env-app/blob/main/public/wp-config.php#L11)
  ```php
  define('ALLOW_MULTITENANT', true);
  ```

## Overview

This document provides a comprehensive guide to the architecture and operational flow of our Multi-Tenant Application, introduced in version 0.8.0. The platform is designed to support multiple tenants (websites), each with its unique configuration and customization capabilities, on a shared infrastructure.

## Domain and Tenant Mapping

- Tenants are uniquely identified by a Universal Unique Identifier (UUID).
- The mapping between a tenant's domain (derived from HTTP_HOST) and its UUID is crucial. For instance:

`example.com => a345ea9515c`

  > This mapping is typically configured in `bootstrap.php`. Refer to the env app for more details: [Env App Bootstrap](https://github.com/devuri/wp-env-app/blob/main/bootstrap.php)

```php
// 'app' parameter is mandatory for configuration.
$http_app = wpc_app(__DIR__, 'app', ['example.com' => 'a345ea9515c']);
```

## Installation Steps

1. Prepare the `.env` file for each tenant, including essential variables like `APP_TENANT_ID=a345ea9515c` and `IS_MULTITENANT=true`.

   > Ensure each tenant's `.env` file is configured accordingly.

   > **Note**: The uploads directory will be set to `/public/app/tenant/a345ea9515c/uploads`. Make sure to transfer or replicate your site files to this new directory. This setup uses the `tenant_id` for isolated file storage per tenant.

## Configuration and Environment Files

- Tenant-specific configuration files are located as follows:
  - `.env`: `"path/sites/a345ea9515c/.env"`
  - `config.php`: `"path/sites/a345ea9515c/config.php"`

## Tenant-Specific Variables

- Each tenant's `.env` file contains specific environment variables, such as:
  - `APP_TENANT_ID=a345ea9515c`
  - `IS_MULTITENANT=true`
  - Optionally, `APP_TENANT_SECRET` for additional security.

## Customizing Tenant Configurations

- Utilize `env('APP_TENANT_ID')` within the application to access and modify configurations dynamically for each tenant.

## Isolated Uploads Directory

- Media files for each tenant are stored in a separate directory, typically structured as `wp-content/tenant/{tenant_id}/uploads`. Depending on the framework's setup, the default path might vary, but it generally follows the format `app/tenant/{tenant_id}/uploads`.
- It's recommended to encode the tenant UUID in file URLs to enhance security.

## Shared Resources

- Plugins and Themes are shared across tenants, optimizing resources and simplifying management.
- The shared resources' paths are defined in the `app.php` file and the framework's `composer.json`.

## Plugin and Theme Management

- An MU (Must-Use) plugin controls the availability of specific plugins and themes for each tenant.
- The `IS_MULTITENANT` variable aids in configuring resource availability and is particularly useful during tenant migrations or when converting a tenant to a standalone installation.

## Suitability for Multi-Tenant Architecture

While the Multi-Tenant Application offers efficient resource sharing and management, it's crucial to assess its suitability based on your specific needs:

### Considerations

- **Isolation**: Full isolation might require alternative solutions if stringent separation is needed.
- **Performance**: High-demand applications may benefit from dedicated resources.
- **Security**: Additional measures might be necessary to meet specific security standards.
- **Customization**: Extensive per-tenant customizations could complicate the multi-tenant model.

### Evaluation

> [!CAUTION]
>
> Thoroughly evaluate your use case before adopting a multi-tenant architecture. For certain scenarios, a single-tenant or dedicated solution might be more appropriate.

The Multi-Tenant Application excels in scenarios prioritizing efficient management and shared infrastructure. It's an ideal choice if these factors align with your project goals.

> Every project is unique, so it's important to choose an architecture that fits your specific requirements and constraints.

**Note: This documentation is continually evolving, and we welcome community contributions and feedback. If you have suggestions or notice areas needing improvement, please contribute via pull requests or contact our development team. Your input is invaluable in refining this guide.**
