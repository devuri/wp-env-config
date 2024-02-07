# Multi-Tenant Applications

## Overview

This documentation explains the architecture and flow of our Multi-Tenant application ( Introduced v0.8.0 ). 
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
## Install
After that you need to prepare your .env file and include the required `APP_TENANT_ID=48562e29-dc29-4aad-aca2-9a345ea9515c` and set `IS_MULTI_TENANT_APP=true` 
DO NOT skip this step as both are required.

> you will need to do the same for each env file.
> Note: your content directory will be updated to: `WP_CONTENT_DIR =>	/public/48562e29-dc29-4aad-aca2-9a345ea9515c/app`
> Ensure you move or copy your themes etc to the new directory.
> Notice that the tenant_id is used for storing your files separately. But you can now specify completely separate database.

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

## Multi-Tenant Suitability

A  Multi-Tenant application is designed to provide a shared platform for multiple tenants, allowing them to manage and customize their websites efficiently. However, it's important to note that a multi-tenant architecture may not be the best fit for every use case.

### Considerations

- **Isolation Needs**: If your application requires strict isolation between tenants, such as separate files or complete independence, a multi-tenant architecture may not be the ideal choice.

- **Performance Demands**: Highly resource-intensive or performance-critical applications might benefit from a dedicated hosting environment.

- **Security Requirements**: Depending on the nature of your application and regulatory requirements, you may need additional security measures that a multi-tenant setup might not fully address.

- **Customization Complexity**: Extensive customizations for individual tenants may introduce complexities that are best handled through a dedicated environment.

### Use Case Evaluation

> [!WARNING]
> 
> Before adopting a multi-tenant architecture, carefully evaluate your specific use case and requirements. In some cases, a dedicated or single-tenant solution might be a more suitable option.

A Multi-Tenant application is best suited for scenarios where resource optimization, easy management, and shared infrastructure are paramount.
If your use case aligns with these characteristics, this solution can provide an efficient and cost-effective platform for your tenants.

> Remember that each project has unique needs, and we encourage you to select the architecture that best aligns with your objectives and constraints.


**Please Note: This documentation is a work in progress, and we welcome contributions and improvements. If you have suggestions or identify areas that need clarification, feel free to submit pull requests or reach out to our development team. 
Your feedback is valuable in enhancing the quality of this documentation.**
