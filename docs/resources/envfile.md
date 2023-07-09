# Environment Variables

This file contains the environment variables used to configure the settings for your website. The variables define various aspects such as database connections, security settings, backup options, and more. Please review and modify the values accordingly to suit your specific needs.

## Instructions

1. Create a new file in your project directory and name it `.env`.
2. Copy the contents from this example file into your newly created `.env` file.
3. Replace the placeholder values with your actual configuration details.

```shell
WP_HOME='http://example.com'
WP_SITEURL="${WP_HOME}/wp"

BASIC_AUTH_USER='admin'
BASIC_AUTH_PASSWORD='MySecurePassword123'

USE_APP_THEME=false
WP_ENVIRONMENT_TYPE='production'
BACKUP_PLUGINS=true

SEND_EMAIL_CHANGE_EMAIL=true
SENDGRID_API_KEY='YOUR_SENDGRID_API_KEY'
SUDO_ADMIN='1'

WEB_APP_PUBLIC_KEY='d8f6a9b7-c4e3-42a6-9e0b-5f2d1c0e7a8b'
SEND_EMAIL_CHANGE_EMAIL=true

# Premium
AVADAKEY='YOUR_AVADA_THEME_LICENSE_KEY'
BACKUP_PLUGINS=true

MEMORY_LIMIT='512M'
MAX_MEMORY_LIMIT='512M'

FORCE_SSL_ADMIN=true
FORCE_SSL_LOGIN=true

# backup
ENABLE_S3_BACKUP=true
S3_BACKUP_KEY='YOUR_S3_ACCESS_KEY'
S3_BACKUP_SECRET='YOUR_S3_SECRET_KEY'
S3_BACKUP_BUCKET='wp-s3-backups'
S3_BACKUP_REGION='us-east-1'
S3_BACKUP_DIR='my-website-backups'

DB_NAME='my_wp_db'
DB_USER='db_user'
DB_PASSWORD='db_password'
DB_HOST='localhost'
DB_PREFIX='wp_xyz123_'

```

## Configuration

### WordPress Settings

- `WP_HOME`: The URL of your website's home page.
- `WP_SITEURL`: The URL of your WordPress installation relative to the home URL.

### Basic Authentication

- `BASIC_AUTH_USER`: The username for basic authentication.
- `BASIC_AUTH_PASSWORD`: The password for basic authentication.

### Application Theme

- `USE_APP_THEME`: Set to `true` if you want to use a custom application theme, or `false` otherwise.

### Environment Type

- `WP_ENVIRONMENT_TYPE`: The environment type of your website (e.g., `production`, `staging`, `development`, etc.).

### Backup Settings

- `BACKUP_PLUGINS`: Set to `true` if you want to enable backup for plugins, or `false` otherwise.

### Email Settings

- `SEND_EMAIL_CHANGE_EMAIL`: Set to `true` if you want to send email notifications for changed email addresses, or `false` otherwise.
- `SENDGRID_API_KEY`: Your SendGrid API key for sending emails.

### Administrator Privileges

- `SUDO_ADMIN`: Set to `1` if you want to grant super admin privileges, or `0` otherwise.

### Web Application Public Key

- `WEB_APP_PUBLIC_KEY`: The public key for your web application.

### Premium Features

- `AVADAKEY`: Your Avada theme license key for premium features.

### Memory Limit

- `MEMORY_LIMIT`: The memory limit for your website (e.g., `256M`, `512M`, etc.).
- `MAX_MEMORY_LIMIT`: The maximum memory limit for your website.

### SSL Settings

- `FORCE_SSL_ADMIN`: Set to `true` if you want to force SSL for admin pages, or `false` otherwise.
- `FORCE_SSL_LOGIN`: Set to `true` if you want to force SSL for login pages, or `false` otherwise.

### Backup Settings

- `ENABLE_S3_BACKUP`: Set to `true` if you want to enable backup to Amazon S3, or `false` otherwise.
- `S3_BACKUP_KEY`: Your Amazon S3 access key.
- `S3_BACKUP_SECRET`: Your Amazon S3 secret key.
- `S3_BACKUP_BUCKET`: The name of the Amazon S3 bucket to store backups.
- `S3_BACKUP_REGION`: The region of your Amazon S3 bucket.
- `S3_BACKUP_DIR`: The directory path in the Amazon S3 bucket to store backups.

### Database Settings

- `DB_NAME`: The name of your WordPress database.
- `DB_USER`: The username for accessing the database.
- `DB_PASSWORD`: The password for accessing the database.
- `DB_HOST`: The host of the database server.
- `DB_PREFIX`: The database table prefix for WordPress.

## Note

- Ensure that you keep this file secure and do not expose it publicly, as it contains sensitive configuration details.
- Make sure to backup this file and your database regularly to avoid any loss of data.

