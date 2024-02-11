# Multi-Tenant Application

### Step 1: Backup Your Site

Before making any changes, ensure you have a full backup of your WordPress files and database.

Install and activate the [Tenancy plugin](#), this will create the required database tables and admin area on the main site ( referred to as the Landlord site ).

### Enabling Multi-Tenancy in Your Configuration

To activate the multi-tenant functionality within your setup, follow these steps:

1. **Access Configuration Files:**
   - Navigate to the `config` directory within your application's installation.

2. **Modify Tenancy Configuration:**
   - Open the `tenancy.php` file located in this directory to adjust your tenancy settings.

3. **Activate Multi-Tenant Mode:**
   - Find and update the relevant line of code to turn on the multi-tenant feature by setting `ALLOW_MULTITENANT` to `true`:
    ```php
    define('ALLOW_MULTITENANT', true);
    ```

4. **Set the Landlord UUID:**
   - Additionally, you'll need to specify the UUID for the landlord (main tenant). This unique identifier is typically provided at the bottom of the plugin's main page after you've enabled the Tenancy Manager Plugin. If you have this information, update the following line accordingly:
    ```php
    define('LANDLORD_UUID', 'your-landlord-uuid-here');
    ```
    Replace `'your-landlord-uuid-here'` with your actual landlord UUID.

### Step 3: Configuring Landlord Environment Settings

To properly set up the Landlord environment for your multi-tenant application installation, follow these steps to ensure a proper database connection:

1. **Backup Your Existing Environment File**: Before making any changes, it's crucial to back up your current `.env` file.

2. **Create a New `.env` File**: In the root directory of application installation, create a new `.env` file. This file will store the environment-specific configurations for the Landlord database (settings in this env file are discarded after initial setup of the Landlord).

3. **Configure Landlord Database Settings**: Inside the newly created `.env` file, input the following configuration settings. These settings should match those of the main site (also referred to as the Landlord site) where the Tenancy plugin is installed. Adjust the values to reflect your specific Landlord database credentials:

   ```php
   # Landlord Database Configuration
   LANDLORD_DB_NAME=      # The name of your Landlord database
   LANDLORD_DB_USER=      # The username for your Landlord database access
   LANDLORD_DB_PASSWORD=  # The password for your Landlord database access
   LANDLORD_DB_HOST=localhost  # The hostname for your Landlord database server, typically 'localhost'
   LANDLORD_DB_PREFIX=wp_lo6j2n6v_  # The prefix for your Landlord database tables, adjust as needed
   ```

## Overview

This document provides a comprehensive guide to the architecture and operational flow of our Multi-Tenant Application, introduced in version 0.8.0. The platform is designed to support multiple tenants (websites), each with its unique configuration and customization capabilities, on a shared infrastructure.

## Domain and Tenant Mapping

- Tenants are uniquely identified by a Universal Unique Identifier (UUID).
- The mapping between a tenant's domain (derived from HTTP_HOST) and its UUID is crucial.

## Installation Steps

   > **Note**: The uploads directory will be set to `/public/app/tenant/a345ea9515c/uploads`. Make sure to transfer or replicate your site files to this new directory. This setup uses the `tenant_id` for isolated file storage per tenant.

## Configuration and Environment Files

- Tenant-specific configuration files are located as follows:
  - `.env`: `"path/site/a345ea9515c/.env"`
  - `config.php`: `"path/site/a345ea9515c/config.php"`

The framework supports distinct configurations for each tenant, enabling customized settings per site within a multi-tenant environment:

#### Locations

- **Environment File**: Located at `path/site/{tenant_id}/.env`, it stores environment-specific variables.
- **PHP Configuration**: Found at `path/site/{tenant_id}/config.php`, this file contains PHP configuration file overrides.

#### Loading Mechanism

1. **Tenant-Specific**: The framework first attempts to load configurations from the tenant's directory.
2. **Fallback**: In the absence of tenant-specific files, it defaults to the global `config.php`.
3. **Overrides**: Global settings in the default config can be overridden by tenant-specific files for flexibility.

#### Benefits

- Enables per-tenant customizations.
- Provides a fallback to ensure system stability.
- Allows global settings to be overridden at the tenant level.

This approach ensures a balance between customization for individual tenants and consistency across the framework.

## Tenant-Specific Variables

- Each tenant's `.env` file can contain specific environment variables, such as:
  - `APP_TENANT_ID=a345ea9515c`
  - Optionally, `APP_TENANT_SECRET` for additional security.

## Customizing Tenant Configurations

- Utilize `env('APP_TENANT_ID')` within the application to access and modify configurations dynamically for each tenant. It is also set as `APP_TENANT_ID` constant during initialization steps.

## Isolated Uploads Directory

- Media files for each tenant are stored in a separate directory, typically structured as `wp-content/tenant/{tenant_id}/uploads`. Depending on the framework's setup, the default path might vary, but it generally follows the format `app/tenant/{tenant_id}/uploads`.
- It's recommended to encode the tenant UUID in file URLs to enhance security.

## Shared Resources

- Plugins and Themes are shared across tenants, optimizing resources and simplifying management.
- The shared resources' paths are defined in the `app.php` file and the framework's `composer.json`.

## Plugin and Theme Management

- An MU (Must-Use) plugin controls the availability of specific plugins and themes for each tenant.
- The `IS_MULTITENANT` constant aids in configuring resource availability and is particularly useful during tenant migrations or when converting a tenant to a standalone installation.

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
