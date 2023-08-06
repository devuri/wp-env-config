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

SENDGRID_API_KEY='YOUR_SENDGRID_API_KEY'
SUDO_ADMIN='1'

WPENV_AUTO_LOGIN_SECRET_KEY=null
WEB_APP_PUBLIC_KEY='b75b666f-ac11-4342-b001-d2546f1d3a5b'
SEND_EMAIL_CHANGE_EMAIL=false

# Premium
ELEMENTOR_PRO_LICENSE=''
AVADAKEY=''
BACKUP_PLUGINS=false

MEMORY_LIMIT='512M'
MAX_MEMORY_LIMIT='512M'

FORCE_SSL_ADMIN=true
FORCE_SSL_LOGIN=true

# s3backup
ENABLE_S3_BACKUP=false
S3ENCRYPTED_BACKUP=false
S3_BACKUP_KEY=null
S3_BACKUP_SECRET=null
S3_BACKUP_BUCKET='wp-s3snaps'
S3_BACKUP_REGION='us-west-1'
S3_BACKUP_DIR=null
DELETE_LOCAL_S3BACKUP=false

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
- `ELEMENTOR_PRO_LICENSE` : Elementor Pro theme license key.

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

