## Installation of Premium Plugins

This guide demonstrates how to install premium plugins via Composer. For this example, we will use ACF PRO and Elementor plugins.

### Step 1: Configure Authentication

Before installing premium plugins, it's essential to set up authentication in your Composer configuration. This ensures that you have the necessary credentials to access the plugin repositories. Here's how to do it:

**1. Create or Edit `auth.json`**

You should have an `auth.json` file in your Composer global configuration directory. If it doesn't exist, create it. Add the authentication details for both ACF PRO and Elementor as shown below:

```shell
{
  "http-basic": {
    "connect.advancedcustomfields.com": {
      "username": "Dvl2P9fLovYy2oJkdYOPiCrHXcRgGrmk9WR62HdErPasPsV43COx0anwTizc9XFrY8qysqqZ",
      "password": "https://mysite.com"
    },
    "composer.elementor.com": {
      "username": "token",
      "password": "<license-key>"
    }
  }
}
```

Replace `<license-key>` with your actual Elementor license key.

### Step 2: Add Repositories

Now, you need to add the repositories for the premium plugins to your `composer.json` file. This tells Composer where to find the plugins. Here's how to do it:

**1. Edit `composer.json`**

In your project's `composer.json` file, add the repositories for ACF PRO and Elementor as follows:

```shell
{
  "repositories": [
    {
      "type": "composer",
      "url": "https://composer.elementor.com",
      "only": [
        "elementor/elementor-pro"
      ]
    },
    {
      "type": "composer",
      "url": "https://connect.advancedcustomfields.com"
    }
  ]
}
```

### Step 3: Install the Plugin

With authentication and repositories set up, you can now install the premium plugins using Composer. For example, to install ACF PRO, run the following command:

```shell
composer require wpengine/advanced-custom-fields-pro

```
and for Elementor:

```shell
composer require elementor/elementor-pro

```

### Additional Information

You can find more detailed installation instructions and resources for both plugins on their respective websites:

- [ACF PRO Installation with Composer](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/)
- [Elementor Composer Integration](https://developers.elementor.com/docs/cli/composer/)
