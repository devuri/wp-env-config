# Changelog

## [0.6.3](https://github.com/devuri/wp-env-config/compare/v0.6.2...v0.6.3) (2023-07-07)


### Bug Fixes

* fix adn exclude on Sucuri ([ef62d71](https://github.com/devuri/wp-env-config/commit/ef62d71fe3d8d2b5a01aee451d2271afa7caf170))
* fixes undefined array item ([aae1cea](https://github.com/devuri/wp-env-config/commit/aae1ceab8ac9e3675251df314c41dc4615f56d2f))
* use int on port checks ([d06195f](https://github.com/devuri/wp-env-config/commit/d06195faf61aadc66fc817f83be65df52c09da7b))


### Miscellaneous Chores

* build ([15301a0](https://github.com/devuri/wp-env-config/commit/15301a03244e05f9a3b8485e963ed700d767cd8e))

## [0.6.2](https://github.com/devuri/wp-env-config/compare/v0.6.1...v0.6.2) (2023-07-07)


### Features

* add `DatabaseBackup` cli `db:backup` ([648bfc5](https://github.com/devuri/wp-env-config/commit/648bfc5847d8a1a93c5158425c71cab7dcea30dc))
* add backups by month and year to backup command, include snap.json ([b856ed7](https://github.com/devuri/wp-env-config/commit/b856ed7753373dbbb952d34ade59c20157dbf107))
* add upgrade to use `devuri/secure-password` ([5c485a7](https://github.com/devuri/wp-env-config/commit/5c485a745a3f4a9b8cb969c08cbecc5e537919fe))
* adds  Restricted Admin ([d308ca6](https://github.com/devuri/wp-env-config/commit/d308ca69d13e7ae980cc7d8cf350a4252116babc))
* adds `basic auth` plugin option ([ef3c868](https://github.com/devuri/wp-env-config/commit/ef3c868eb9f7c0a36c56f0ab7ec51194b8a1ce8c))
* adds `PublicKey` support for encryption or verification purposes ([c019481](https://github.com/devuri/wp-env-config/commit/c019481604c9c6310e1966e644e4fa14d982a61a))
* adds `sudo_admin_group` users with higher privileges ([90f4dc3](https://github.com/devuri/wp-env-config/commit/90f4dc3cff9ddde8f5934445a4e7ac6dd09ab9aa))
* adds `wpi -u admin` cli wp installer `QuickInstaller` ([d20f90a](https://github.com/devuri/wp-env-config/commit/d20f90a84b8d36d9e77a2d26b26fcc9e6c6f2dee))
* disable error handler with `false` ([5b9f186](https://github.com/devuri/wp-env-config/commit/5b9f18676d3bf8adad43ab5977ba8d1316c93b8d))


### Bug Fixes

* comment fix ([faf94ea](https://github.com/devuri/wp-env-config/commit/faf94eabdf563e0a0afc4c3721b1b12377b1a0fe))
* deps ([3d88f46](https://github.com/devuri/wp-env-config/commit/3d88f465e02e1085efac68fe2c5f1016daea0923))
* do not lock out the admin with basic auth ([fd96d60](https://github.com/devuri/wp-env-config/commit/fd96d600e525087a98d4e77be5a69f09d85cc7c4))
* fix extra theme directory ([0d60b40](https://github.com/devuri/wp-env-config/commit/0d60b4059fbed513242df2c1d7f179a257b039f3))
* fixes require ([e29ada0](https://github.com/devuri/wp-env-config/commit/e29ada08e1bac54f511eb7e91ea9c1952bf57687))


### Miscellaneous Chores

* build ([6f557f9](https://github.com/devuri/wp-env-config/commit/6f557f98d265b3f36c3ffed1b181b69ac32d9862))
* build ([4a7c339](https://github.com/devuri/wp-env-config/commit/4a7c339547e1b84bcfee83950445428095821d30))

## [0.6.1](https://github.com/devuri/wp-env-config/compare/v0.6.0...v0.6.1) (2023-07-01)


### Features

* adds `Setup` and application error handling to `App` ([453d5e9](https://github.com/devuri/wp-env-config/commit/453d5e96ccef412600cce9bff342442fbaada6cd))
* adds `sudo_admin` and Custom `Sucuri settings`  Disable Sucuri WAF ui ([214e1d8](https://github.com/devuri/wp-env-config/commit/214e1d88ea4ac442e4b3c143bae9d477b9ba486c))
* adds try catch block to `wpc_app` helper ([3e449ec](https://github.com/devuri/wp-env-config/commit/3e449eca2696c37b6195ca4806123edca3b50384))
* app config options: `security`, smtp `mailer` and `redis cache` ([5744c54](https://github.com/devuri/wp-env-config/commit/5744c546a2c11d72869ee85776b54d9bb7128004))
* use `ConstantTrait` and add `redis` and `security` settings to BaseKernel ([80924a4](https://github.com/devuri/wp-env-config/commit/80924a496de29b8b8c6389ec6dca91930b996a6b))


### Bug Fixes

* replace cli `install` =&gt; `i` to install plugins and themes ([5f3419b](https://github.com/devuri/wp-env-config/commit/5f3419bf9fe6789f285656395ddfbc30963c16f7))
* trim for 8.1 ([7bed1be](https://github.com/devuri/wp-env-config/commit/7bed1be3f4b06ed9efc8c69719570c6b265aec11))
* various fixes for sucuriscan ([fba2017](https://github.com/devuri/wp-env-config/commit/fba20173accd9b182d0322f47eb531f6804e6b42))


### Miscellaneous Chores

* build ([ca71cc7](https://github.com/devuri/wp-env-config/commit/ca71cc7e7ae068c0acf312f9b66b3af9407dd7ea))

## [0.6.0](https://github.com/devuri/wp-env-config/compare/v0.5.1...v0.6.0) (2023-06-27)


### ⚠ BREAKING CHANGES

* !BREAKING `symfony/console` is now `6.0` requires php `8.0.2`

### Features

* !BREAKING `symfony/console` is now `6.0` requires php `8.0.2` ([01fb09b](https://github.com/devuri/wp-env-config/commit/01fb09b74573fbac8a1e24322f3e9aaa041976c6))
* `templates_dir` replaces theme_dir ([b2859dd](https://github.com/devuri/wp-env-config/commit/b2859dde1f903ec9c177df1ced686e881f27a966))
* adds `config_file` for overrides ([a4bff89](https://github.com/devuri/wp-env-config/commit/a4bff899f58480d07bdd64e06249da4f2d6edd30))
* adds `make:htpass` cli to create htaccess basic auth password file ([f2dbd3f](https://github.com/devuri/wp-env-config/commit/f2dbd3f84b24434990f6ce4344655b0c9618a50b))
* adds alias `prod`, `local`, `dev` and `sec` for environment switch ([f9ee749](https://github.com/devuri/wp-env-config/commit/f9ee7498dd4a5f34e0258f8c4b2851e67bdb4443))
* adds dev `laravel/envoy` and `spatie/ssh` ([824023f](https://github.com/devuri/wp-env-config/commit/824023f8f62d655528d4b00c96de2cbf287e8cfa))
* adds security headers ([c929311](https://github.com/devuri/wp-env-config/commit/c929311d0943bb71ce88bd04bcf96e1c38580ab3))
* get installed plugins as composer dependencies ([f15ac59](https://github.com/devuri/wp-env-config/commit/f15ac5983c5dbbe608a6471c021ef1e888fdd8d5))
* rename `Nino Cli =&gt; 0.1.3` ([41fd1b3](https://github.com/devuri/wp-env-config/commit/41fd1b3deb8ad1de7831665da9a8f77d70065371))


### Bug Fixes

* `symfony/console:6.0` version constraint for console ([1afe274](https://github.com/devuri/wp-env-config/commit/1afe2748bdeeb0eb39cf699379f9de3350673419))
* 7.3 compat fixes ([5765d92](https://github.com/devuri/wp-env-config/commit/5765d92c9e22e20167a399c26c5a3b280062c55c))
* adds `APP_TEST_PATH` to fix tests warning ([183b8c8](https://github.com/devuri/wp-env-config/commit/183b8c8c179be02988ab17e32fcf9d0c42b167ae))
* fix console output for config ([110efb1](https://github.com/devuri/wp-env-config/commit/110efb1e58fe36a1cae62d7c06cbbd249b15a340))
* fix htpass cli ([924c06d](https://github.com/devuri/wp-env-config/commit/924c06d450aeb619937f92fe531e181ce7767bea))
* fix test namespace ([5f30867](https://github.com/devuri/wp-env-config/commit/5f308670f71094aa9b839af990e9950b47c1cd37))
* fixes for cli tools ([8980692](https://github.com/devuri/wp-env-config/commit/8980692f9f4e7722e8a524c5801a114f1b771300))
* php serve cli ([db0fc02](https://github.com/devuri/wp-env-config/commit/db0fc026c2318c19a9c370e7c95629b46f9efcc3))
* use gmdate ([81b1469](https://github.com/devuri/wp-env-config/commit/81b146922a4e34c942eef2c775915050e57e48a5))
* version upgrades ([52fd71e](https://github.com/devuri/wp-env-config/commit/52fd71e8d75c7b45b957f6c2efd6610163de320e))


### Miscellaneous Chores

* build ([fb5d648](https://github.com/devuri/wp-env-config/commit/fb5d648f1d9dfc675e6bd9efca9fc144a85cbc6c))
* **build:** build ([581b61a](https://github.com/devuri/wp-env-config/commit/581b61a811dc946d20bf1b0a62592f840c5154c9))
* **build:** build ([c799978](https://github.com/devuri/wp-env-config/commit/c7999780bd65dae07797c5e671c5c519bcd1bbfa))
* **build:** build ([b153478](https://github.com/devuri/wp-env-config/commit/b15347800bfd08ad6696f0aa7b055c34ff6805b0))

## [0.5.1](https://github.com/devuri/wp-env-config/compare/v0.5.0...v0.5.1) (2023-06-18)


### Features

* adds `wpc_app_config_core()` load core plugin ([62ba254](https://github.com/devuri/wp-env-config/commit/62ba254ed314222b68d063f7f3375d14b16ee769))

## [0.5.0](https://github.com/devuri/wp-env-config/compare/0.4.1...v0.5.0) (2023-06-18)


### ⚠ BREAKING CHANGES

* `breaking change` refactor

### Features

* `breaking change` refactor ([20f7150](https://github.com/devuri/wp-env-config/commit/20f715069822449c9afeeb63da74f8269643d07f))
* `nino` is now available in `vendor/bin` ([561c27d](https://github.com/devuri/wp-env-config/commit/561c27d54ce5dd8696be9fadbbdf5eb6be6e97a0))
* Add `config(false)`  to use WP_ENVIRONMENT_TYPE ([5d5f2e4](https://github.com/devuri/wp-env-config/commit/5d5f2e49c496216999cc0f27c2a1492913cef9f6))
* add `get_http_env()` Get the current set wp app env ([ce3bcdb](https://github.com/devuri/wp-env-config/commit/ce3bcdbd2d11518dcf4c214ea031ef122ee2a2ea))
* add configMap() Display a list of constants defined by Setup. ([68f1fa5](https://github.com/devuri/wp-env-config/commit/68f1fa5b005d9fd5befd60684d5e139b848b9124))
* Adds 'wordpress'  =&gt; 'wp', ([16f5804](https://github.com/devuri/wp-env-config/commit/16f5804254d8c009cf385e294828dfc4103c8f46))
* Adds `asset_url()` ([47d33b8](https://github.com/devuri/wp-env-config/commit/47d33b852bdf0caf8d1e40a5e8ac960b92449f49))
* Adds `Asset::url` ([d8572c2](https://github.com/devuri/wp-env-config/commit/d8572c27f1e44acbe5a5b5f68529751e70802100))
* Adds `CryptTrait`, Encrypts the values of sensitive data in the given configuration array ([e0d8760](https://github.com/devuri/wp-env-config/commit/e0d8760d138604bf3a23bb4830ebe1e18954ca3f))
* Adds `DEVELOPER_ADMIN` const an int user ID ([d935426](https://github.com/devuri/wp-env-config/commit/d9354266aafee02ebc938545bf6172efdcc7c1cf))
* Adds `env()` function ([c9ce38b](https://github.com/devuri/wp-env-config/commit/c9ce38ba7f3ad207ff6e183b0a15a4b4c491e413))
* Adds `generate:composer` to create composer file ([3612106](https://github.com/devuri/wp-env-config/commit/36121060a561a947c63a1430615fcebad6454014))
* Adds `HTTP_ENV_CONFIG` `get_environment()` ([8737d11](https://github.com/devuri/wp-env-config/commit/8737d11c1a626999582f16fd91aefcc05b0bd614))
* Adds `HttpKernel` default args ([5e4a020](https://github.com/devuri/wp-env-config/commit/5e4a020911853d0f613636b65026706cd5948675))
* adds `nino install` to install plugin or theme ([e704045](https://github.com/devuri/wp-env-config/commit/e704045e429034843222b785ab86a9b3789ae279))
* Adds `Nino` Cli ([299a889](https://github.com/devuri/wp-env-config/commit/299a889414fa76c4ccb127b2acffb129fdb2a51d))
* Adds `oops` error handler ([3cbb8f2](https://github.com/devuri/wp-env-config/commit/3cbb8f2454a44dbbf485aaa607b6fc9f3d9d5748))
* Adds `overrides` for `config.php` ([f5c2c6c](https://github.com/devuri/wp-env-config/commit/f5c2c6cdf6829d1b6147e56c543622841054ae9f))
* Adds `set_env_secret( string $key )` to define secret env vars ([f5a4b84](https://github.com/devuri/wp-env-config/commit/f5a4b84cc9c7954ad74ac1b98404339b74b0e062))
* Adds `SSL` support by `certbot` ([9ccb5cf](https://github.com/devuri/wp-env-config/commit/9ccb5cf4cee49947e617008e76db69a4d167ef06))
* Adds `static::detect_error()` and `static::env()` ([b310e16](https://github.com/devuri/wp-env-config/commit/b310e16f680a1261e7150e15b6cc074bc14643ea))
* Adds `USE_APP_THEME` check ([d55fd95](https://github.com/devuri/wp-env-config/commit/d55fd950c7e228fa0a24ecafa9739d5d68ec7365))
* Adds `uuid` ([15c61c1](https://github.com/devuri/wp-env-config/commit/15c61c10de604585fbf7e280f1f9c39aa8bd082d))
* Adds `wpc_app` function ([593e767](https://github.com/devuri/wp-env-config/commit/593e7676e82c5d1ab217c267226f1a3cc5ec8d08))
* adds a list of setup options ([9dea7b3](https://github.com/devuri/wp-env-config/commit/9dea7b39933c05d42bb8b79e57c45d1fdb7fdd75))
* Adds changes `.env` db prefix if set to `wp_` ([39b03e7](https://github.com/devuri/wp-env-config/commit/39b03e7f57c0088d18e1d731ea17263c7c2fd174))
* Adds cookie-related override for WordPress constants ([5039404](https://github.com/devuri/wp-env-config/commit/5039404c658acb821cab3ea1fb9de5ff62dc3528))
* Adds custom theme dir ([39f97ba](https://github.com/devuri/wp-env-config/commit/39f97ba00d20b905090d7c79661f7ec7d1e353f4))
* Adds docs dir ([4f89446](https://github.com/devuri/wp-env-config/commit/4f89446f0a706283c4a8c9cdcbd124c389ca637e))
* adds Environment::secure() ([8a2f109](https://github.com/devuri/wp-env-config/commit/8a2f1099d9be5fd21466d8a674a583be050edee4))
* Adds Generator to create `htpasswd` ([3190fc9](https://github.com/devuri/wp-env-config/commit/3190fc9c9544354cd6fbe7d580ebcadc74df66aa))
* adds getEnvironment() to get the current Environment setup ([46f65d5](https://github.com/devuri/wp-env-config/commit/46f65d550a054ad67e9c9128f66743b6615099eb))
* Adds Kernel ([8fc96c2](https://github.com/devuri/wp-env-config/commit/8fc96c20ecd8034d0691c6a05d634732a225628b))
* adds more error reporting for `debug` ([7c55d36](https://github.com/devuri/wp-env-config/commit/7c55d36c193deb5f3325c89f1901fee9977ea150))
* Adds multiple `env` file support: https://github.com/vlucas/phpdotenv/pull/394 ([a4f97b3](https://github.com/devuri/wp-env-config/commit/a4f97b3da326c967d5d19406f25cd0f17eec2f7a))
* Adds new `core` plugin ([cb219d8](https://github.com/devuri/wp-env-config/commit/cb219d82a8fb0f719662cf832954869681f98886))
* Adds suggest `spatie/ssh` ([cf0befa](https://github.com/devuri/wp-env-config/commit/cf0befaeaa0f5c4d04224310dfe5327df30dea27))
* Adds support for `aaemnnosttv/wp-sqlite-db` ([f8b3d80](https://github.com/devuri/wp-env-config/commit/f8b3d807ad2b3f247d9e48e7422693fb34a3e5a1))
* Adds support for custom log dir `year-month-day.log` ([54c4ba0](https://github.com/devuri/wp-env-config/commit/54c4ba03378dbd8a8d060c331ac7361d8b970a39))
* Adds tests for `HttpKernel` ([b18b06c](https://github.com/devuri/wp-env-config/commit/b18b06c2a2cf652e6d27a91081d87d90d8ecda16))
* can now disable and bypass the default setup process ([617938a](https://github.com/devuri/wp-env-config/commit/617938a07228cde57087be0379a9b3efe77a8588))
* create `uuid` dir path to store phpmyadmin or adminer ([a968668](https://github.com/devuri/wp-env-config/commit/a968668c70c6db06f96da198d82a78e91044d919))
* defines Environment types ([36e7778](https://github.com/devuri/wp-env-config/commit/36e7778f0a1b66cf60a510102360564ef11e2b70))
* error handler can now be passed in as a `Kernel` argument ([da5419c](https://github.com/devuri/wp-env-config/commit/da5419c288719941e941ec93795f6eeefe6ee4fb))
* Hash env output on the command line ([05a6eb2](https://github.com/devuri/wp-env-config/commit/05a6eb252204a4bfb2f7a281013586c999ad2b45))
* optionally pass in the `HttpKernel` instance ([50f2d92](https://github.com/devuri/wp-env-config/commit/50f2d927fb8f931e9ed643789ede52c170c5dca0))
* Prevent Admin users from deactivating plugins. ([1326209](https://github.com/devuri/wp-env-config/commit/1326209cee84a40821371e944d82e5d6da62a468))
* register custom theme directory in `Core Plugin` ([7162fcd](https://github.com/devuri/wp-env-config/commit/7162fcdc9a74b983eacf2b500021a89ef0d43ced))
* Set slug of the default theme ([147fe09](https://github.com/devuri/wp-env-config/commit/147fe0914c2fe3916b383c3636a87aa29a828b89))
* simplify environment setup, allow bypass of default setup ([8ef04d5](https://github.com/devuri/wp-env-config/commit/8ef04d5009528209fdcbf0e33a733707f2bb88aa))
* Validate `.env` port matches `local` server port ([df8297c](https://github.com/devuri/wp-env-config/commit/df8297c6f04b6dd3a9192b0a46a03c737cf8472c))
* when `null` or `false` the `WP_ENVIRONMENT_TYPE` is used ([5adb242](https://github.com/devuri/wp-env-config/commit/5adb242ac31633e70e77bf38662699b4731bfbba))


### Bug Fixes

* Adds `Error Handler` docs ([1292dde](https://github.com/devuri/wp-env-config/commit/1292dde5e8f5fa5ecf94429fd7d1677a66b6ab61))
* bin missing from package ([0d93d5c](https://github.com/devuri/wp-env-config/commit/0d93d5c5d62f15a842bfd15f7371befc48b99ace))
* consolidate `env` methods ([1f093c7](https://github.com/devuri/wp-env-config/commit/1f093c7688bd9b4c2c34e848b45dedb6a200b5e9))
* create `.env` before we serve in cases where it does not exist ([c952204](https://github.com/devuri/wp-env-config/commit/c9522046a08e287e1c5f0eab482c749997860c26))
* dump error message for dotenv ([a186bbd](https://github.com/devuri/wp-env-config/commit/a186bbd4b96475c0d54472aa106e3eca971f61d2))
* fix ConfigInterface ([3570754](https://github.com/devuri/wp-env-config/commit/35707547213bbc77665a9d9d94cc851d300bdcd0))
* fix example file ([c84cd88](https://github.com/devuri/wp-env-config/commit/c84cd8852a8e31bde1f600c11521bffb2c23bf79))
* fix the return type of `env` should be mixed ([5e10591](https://github.com/devuri/wp-env-config/commit/5e10591723be7034c8082d1c118773d0503fec1d))
* fixes `root_dir_path` ([f3481af](https://github.com/devuri/wp-env-config/commit/f3481af3a5be0f9a12b067a434ad2b08be0cdcc5))
* fixes `strtolower` conversion ([92f5820](https://github.com/devuri/wp-env-config/commit/92f58202276c823328b908734650eededf379bf8))
* fixes debug error handlers based on `environment` ([434b06f](https://github.com/devuri/wp-env-config/commit/434b06f81caedc5abc35a01cf42a9bc308726065))
* fixes error log location ([e884570](https://github.com/devuri/wp-env-config/commit/e884570ed0f5f1b94ede53d39f4e1674d2d35e72))
* fixes interface in v0.30.01 ([813ac64](https://github.com/devuri/wp-env-config/commit/813ac64f66662bd27bd5bb998c412ec55c27f6c5))
* fixes return type for `Setup::get_environment() ` ([3d9d8fc](https://github.com/devuri/wp-env-config/commit/3d9d8fcd3c6b3fb4c335d3a5bc61bcc4ea8c9e65))
* fixes rreturn type set to ConfigInterface ([338912a](https://github.com/devuri/wp-env-config/commit/338912a62a8b730181362868a42e8db731f7e93a))
* fixes symfony compatability ([155b0a7](https://github.com/devuri/wp-env-config/commit/155b0a7076f401b0283e4f7f0ca94f8e8fca11e1))
* fixes the `APP_THEME_DIR` ([2123cd6](https://github.com/devuri/wp-env-config/commit/2123cd6ab35491fd23fa89af02b60bc19fe60ff9))
* fixes the `env` function more reliable output ([64559af](https://github.com/devuri/wp-env-config/commit/64559afa6d43a917f3cca20c810b6f21182a76f5))
* fixes translation string ([994e7d2](https://github.com/devuri/wp-env-config/commit/994e7d20cb1be774fb682a4c4592ae8cc38a25fd))
* fixes white lable plugin ([be9fb1b](https://github.com/devuri/wp-env-config/commit/be9fb1b95043e7e099c76808511ff8f2b8f011cf))
* fixes WP_DEBUG not set ([c0129b5](https://github.com/devuri/wp-env-config/commit/c0129b5b795048930aac2e643a8dc70a402821c0))
* improve and fix the `get_config_map()` ([3ba1a9b](https://github.com/devuri/wp-env-config/commit/3ba1a9b2f20358f590fa45bb819a2e60513a03aa))
* remove string constraint in uploads param ([fb5ae22](https://github.com/devuri/wp-env-config/commit/fb5ae220fd1c6cd0a1c81053bdb3d7368e537b4c))
* symfony debug now only depends on `environment` value ([b84171e](https://github.com/devuri/wp-env-config/commit/b84171ef7a66f36d128136beb24b7010a0ef6e58))
* trait `Generator` is now `Generate` ([28383b7](https://github.com/devuri/wp-env-config/commit/28383b7256c9f439d1f2e42f0b41f9d57bc89c36))
* use `$this-&gt;nino` ([1f1338d](https://github.com/devuri/wp-env-config/commit/1f1338dfd248d2d148e33acbeec9f9816866896f))
* Verifiy files to avoid Dotenv warning. ([b762c2d](https://github.com/devuri/wp-env-config/commit/b762c2de3a23aa5d07b55ff6ee2c25192a38590d))


### Miscellaneous Chores

* **master:** release 0.20.1 ([0bdaa7f](https://github.com/devuri/wp-env-config/commit/0bdaa7f5eb5a3cb3b4272901e48d4750418e6667))
* **master:** release 0.20.2 ([fed64c4](https://github.com/devuri/wp-env-config/commit/fed64c4a70aca5013848e8b9d2a0026d77d58477))
* **master:** release 0.30.2 ([d2b6ce5](https://github.com/devuri/wp-env-config/commit/d2b6ce5afa7efc2fb90742aa09b9aa35fc1858ad))
* **master:** release 0.30.3 ([f244bcc](https://github.com/devuri/wp-env-config/commit/f244bcce13a6adff651c9dd4ce897e907fe81be7))
* **master:** release 0.30.4 ([f3962f3](https://github.com/devuri/wp-env-config/commit/f3962f3ed2a43b9e2f0bde80efdb71e7722871d2))
* **master:** release 0.30.5 ([c66a61e](https://github.com/devuri/wp-env-config/commit/c66a61edd4807d35bc06673ecfa0bc547a654518))
* **master:** release 0.30.6 ([a99bbd7](https://github.com/devuri/wp-env-config/commit/a99bbd7a8b945f1b7024d7eba6183d81a9b92f59))
* **master:** release 0.30.7 ([2bbd582](https://github.com/devuri/wp-env-config/commit/2bbd58218fed4445775a29b6f4358317121c25b3))
* **master:** release 0.30.8 ([24317c0](https://github.com/devuri/wp-env-config/commit/24317c0b1c7849a057ebe2d4ae1d2c9179b97033))
* **master:** release 0.30.9 ([250a69c](https://github.com/devuri/wp-env-config/commit/250a69cf8ac4f05b6e999bf4af1560b6d3f38bef))

## [0.30.9](https://github.com/devuri/wp-env-config/compare/v0.30.8...v0.30.9) (2023-06-18)


### Features

* add `get_http_env()` Get the current set wp app env ([ce3bcdb](https://github.com/devuri/wp-env-config/commit/ce3bcdbd2d11518dcf4c214ea031ef122ee2a2ea))
* Adds `generate:composer` to create composer file ([3612106](https://github.com/devuri/wp-env-config/commit/36121060a561a947c63a1430615fcebad6454014))
* adds `nino install` to install plugin or theme ([e704045](https://github.com/devuri/wp-env-config/commit/e704045e429034843222b785ab86a9b3789ae279))
* Adds `USE_APP_THEME` check ([d55fd95](https://github.com/devuri/wp-env-config/commit/d55fd950c7e228fa0a24ecafa9739d5d68ec7365))
* Adds `wpc_app` function ([593e767](https://github.com/devuri/wp-env-config/commit/593e7676e82c5d1ab217c267226f1a3cc5ec8d08))
* Adds custom theme dir ([39f97ba](https://github.com/devuri/wp-env-config/commit/39f97ba00d20b905090d7c79661f7ec7d1e353f4))
* error handler can now be passed in as a `Kernel` argument ([da5419c](https://github.com/devuri/wp-env-config/commit/da5419c288719941e941ec93795f6eeefe6ee4fb))
* Prevent Admin users from deactivating plugins. ([1326209](https://github.com/devuri/wp-env-config/commit/1326209cee84a40821371e944d82e5d6da62a468))
* register custom theme directory in `Core Plugin` ([7162fcd](https://github.com/devuri/wp-env-config/commit/7162fcdc9a74b983eacf2b500021a89ef0d43ced))


### Bug Fixes

* fixes `strtolower` conversion ([92f5820](https://github.com/devuri/wp-env-config/commit/92f58202276c823328b908734650eededf379bf8))
* fixes the `APP_THEME_DIR` ([2123cd6](https://github.com/devuri/wp-env-config/commit/2123cd6ab35491fd23fa89af02b60bc19fe60ff9))

## [0.30.8](https://github.com/devuri/wp-env-config/compare/v0.30.7...v0.30.8) (2023-03-23)


### Features

* Adds `uuid` ([15c61c1](https://github.com/devuri/wp-env-config/commit/15c61c10de604585fbf7e280f1f9c39aa8bd082d))
* Adds cookie-related override for WordPress constants ([5039404](https://github.com/devuri/wp-env-config/commit/5039404c658acb821cab3ea1fb9de5ff62dc3528))
* Adds Generator to create `htpasswd` ([3190fc9](https://github.com/devuri/wp-env-config/commit/3190fc9c9544354cd6fbe7d580ebcadc74df66aa))
* Adds multiple `env` file support: https://github.com/vlucas/phpdotenv/pull/394 ([a4f97b3](https://github.com/devuri/wp-env-config/commit/a4f97b3da326c967d5d19406f25cd0f17eec2f7a))
* Adds suggest `spatie/ssh` ([cf0befa](https://github.com/devuri/wp-env-config/commit/cf0befaeaa0f5c4d04224310dfe5327df30dea27))
* create `uuid` dir path to store phpmyadmin or adminer ([a968668](https://github.com/devuri/wp-env-config/commit/a968668c70c6db06f96da198d82a78e91044d919))
* Set slug of the default theme ([147fe09](https://github.com/devuri/wp-env-config/commit/147fe0914c2fe3916b383c3636a87aa29a828b89))
* Validate `.env` port matches `local` server port ([df8297c](https://github.com/devuri/wp-env-config/commit/df8297c6f04b6dd3a9192b0a46a03c737cf8472c))


### Bug Fixes

* consolidate `env` methods ([1f093c7](https://github.com/devuri/wp-env-config/commit/1f093c7688bd9b4c2c34e848b45dedb6a200b5e9))
* fixes `root_dir_path` ([f3481af](https://github.com/devuri/wp-env-config/commit/f3481af3a5be0f9a12b067a434ad2b08be0cdcc5))
* fixes debug error handlers based on `environment` ([434b06f](https://github.com/devuri/wp-env-config/commit/434b06f81caedc5abc35a01cf42a9bc308726065))
* trait `Generator` is now `Generate` ([28383b7](https://github.com/devuri/wp-env-config/commit/28383b7256c9f439d1f2e42f0b41f9d57bc89c36))
* use `$this-&gt;nino` ([1f1338d](https://github.com/devuri/wp-env-config/commit/1f1338dfd248d2d148e33acbeec9f9816866896f))
* Verifiy files to avoid Dotenv warning. ([b762c2d](https://github.com/devuri/wp-env-config/commit/b762c2de3a23aa5d07b55ff6ee2c25192a38590d))

## [0.30.7](https://github.com/devuri/wp-env-config/compare/v0.30.6...v0.30.7) (2023-03-17)


### Features

* Adds changes `.env` db prefix if set to `wp_` ([39b03e7](https://github.com/devuri/wp-env-config/commit/39b03e7f57c0088d18e1d731ea17263c7c2fd174))


### Bug Fixes

* create `.env` before we serve in cases where it does not exist ([c952204](https://github.com/devuri/wp-env-config/commit/c9522046a08e287e1c5f0eab482c749997860c26))

## [0.30.6](https://github.com/devuri/wp-env-config/compare/v0.30.5...v0.30.6) (2023-03-16)


### Bug Fixes

* dump error message for dotenv ([a186bbd](https://github.com/devuri/wp-env-config/commit/a186bbd4b96475c0d54472aa106e3eca971f61d2))

## [0.30.5](https://github.com/devuri/wp-env-config/compare/v0.30.4...v0.30.5) (2023-03-16)


### Features

* Adds `SSL` support by `certbot` ([9ccb5cf](https://github.com/devuri/wp-env-config/commit/9ccb5cf4cee49947e617008e76db69a4d167ef06))

## [0.30.4](https://github.com/devuri/wp-env-config/compare/v0.30.3...v0.30.4) (2023-03-16)


### Features

* `nino` is now available in `vendor/bin` ([561c27d](https://github.com/devuri/wp-env-config/commit/561c27d54ce5dd8696be9fadbbdf5eb6be6e97a0))
* Adds support for `aaemnnosttv/wp-sqlite-db` ([f8b3d80](https://github.com/devuri/wp-env-config/commit/f8b3d807ad2b3f247d9e48e7422693fb34a3e5a1))


### Bug Fixes

* bin missing from package ([0d93d5c](https://github.com/devuri/wp-env-config/commit/0d93d5c5d62f15a842bfd15f7371befc48b99ace))

## [0.30.3](https://github.com/devuri/wp-env-config/compare/v0.30.2...v0.30.3) (2023-03-14)


### Features

* Adds `CryptTrait`, Encrypts the values of sensitive data in the given configuration array ([e0d8760](https://github.com/devuri/wp-env-config/commit/e0d8760d138604bf3a23bb4830ebe1e18954ca3f))


### Bug Fixes

* Adds `Error Handler` docs ([1292dde](https://github.com/devuri/wp-env-config/commit/1292dde5e8f5fa5ecf94429fd7d1677a66b6ab61))
* fixes return type for `Setup::get_environment() ` ([3d9d8fc](https://github.com/devuri/wp-env-config/commit/3d9d8fcd3c6b3fb4c335d3a5bc61bcc4ea8c9e65))

## [0.30.2](https://github.com/devuri/wp-env-config/compare/v0.30.1...v0.30.2) (2023-03-14)


### Features

* Adds `HTTP_ENV_CONFIG` `get_environment()` ([8737d11](https://github.com/devuri/wp-env-config/commit/8737d11c1a626999582f16fd91aefcc05b0bd614))
* Adds `Nino` Cli ([299a889](https://github.com/devuri/wp-env-config/commit/299a889414fa76c4ccb127b2acffb129fdb2a51d))
* Adds `oops` error handler ([3cbb8f2](https://github.com/devuri/wp-env-config/commit/3cbb8f2454a44dbbf485aaa607b6fc9f3d9d5748))
* Adds `set_env_secret( string $key )` to define secret env vars ([f5a4b84](https://github.com/devuri/wp-env-config/commit/f5a4b84cc9c7954ad74ac1b98404339b74b0e062))
* Adds new `core` plugin ([cb219d8](https://github.com/devuri/wp-env-config/commit/cb219d82a8fb0f719662cf832954869681f98886))
* Hash env output on the command line ([05a6eb2](https://github.com/devuri/wp-env-config/commit/05a6eb252204a4bfb2f7a281013586c999ad2b45))
* optionally pass in the `BaseKernel` instance ([50f2d92](https://github.com/devuri/wp-env-config/commit/50f2d927fb8f931e9ed643789ede52c170c5dca0))


### Bug Fixes

* fix the return type of `env` should be mixed ([5e10591](https://github.com/devuri/wp-env-config/commit/5e10591723be7034c8082d1c118773d0503fec1d))
* fixes interface in v0.30.01 ([813ac64](https://github.com/devuri/wp-env-config/commit/813ac64f66662bd27bd5bb998c412ec55c27f6c5))
* fixes symfony compatability ([155b0a7](https://github.com/devuri/wp-env-config/commit/155b0a7076f401b0283e4f7f0ca94f8e8fca11e1))
* fixes the `env` function more reliable output ([64559af](https://github.com/devuri/wp-env-config/commit/64559afa6d43a917f3cca20c810b6f21182a76f5))
* fixes translation string ([994e7d2](https://github.com/devuri/wp-env-config/commit/994e7d20cb1be774fb682a4c4592ae8cc38a25fd))
* fixes white lable plugin ([be9fb1b](https://github.com/devuri/wp-env-config/commit/be9fb1b95043e7e099c76808511ff8f2b8f011cf))
* fixes WP_DEBUG not set ([c0129b5](https://github.com/devuri/wp-env-config/commit/c0129b5b795048930aac2e643a8dc70a402821c0))
* improve and fix the `get_config_map()` ([3ba1a9b](https://github.com/devuri/wp-env-config/commit/3ba1a9b2f20358f590fa45bb819a2e60513a03aa))
* symfony debug now only depends on `environment` value ([b84171e](https://github.com/devuri/wp-env-config/commit/b84171ef7a66f36d128136beb24b7010a0ef6e58))

## [0.20.2](https://github.com/devuri/wp-env-config/compare/v0.20.1...v0.20.2) (2023-03-10)


### Features

* Add `config(false)`  to use WP_ENVIRONMENT_TYPE ([5d5f2e4](https://github.com/devuri/wp-env-config/commit/5d5f2e49c496216999cc0f27c2a1492913cef9f6))
* Adds `DEVELOPER_ADMIN` const an int user ID ([d935426](https://github.com/devuri/wp-env-config/commit/d9354266aafee02ebc938545bf6172efdcc7c1cf))
* Adds `overrides` for `config.php` ([f5c2c6c](https://github.com/devuri/wp-env-config/commit/f5c2c6cdf6829d1b6147e56c543622841054ae9f))
* Adds support for custom log dir `year-month-day.log` ([54c4ba0](https://github.com/devuri/wp-env-config/commit/54c4ba03378dbd8a8d060c331ac7361d8b970a39))
* when `null` or `false` the `WP_ENVIRONMENT_TYPE` is used ([5adb242](https://github.com/devuri/wp-env-config/commit/5adb242ac31633e70e77bf38662699b4731bfbba))


### Bug Fixes

* fixes error log location ([e884570](https://github.com/devuri/wp-env-config/commit/e884570ed0f5f1b94ede53d39f4e1674d2d35e72))

## [0.20.1](https://github.com/devuri/wp-env-config/compare/v0.20.0...v0.20.1) (2023-03-07)


### Features

* add configMap() Display a list of constants defined by Setup. ([68f1fa5](https://github.com/devuri/wp-env-config/commit/68f1fa5b005d9fd5befd60684d5e139b848b9124))
* Adds 'wordpress'  =&gt; 'wp', ([16f5804](https://github.com/devuri/wp-env-config/commit/16f5804254d8c009cf385e294828dfc4103c8f46))
* Adds `asset_url()` ([47d33b8](https://github.com/devuri/wp-env-config/commit/47d33b852bdf0caf8d1e40a5e8ac960b92449f49))
* Adds `Asset::url` ([d8572c2](https://github.com/devuri/wp-env-config/commit/d8572c27f1e44acbe5a5b5f68529751e70802100))
* Adds `env()` function ([c9ce38b](https://github.com/devuri/wp-env-config/commit/c9ce38ba7f3ad207ff6e183b0a15a4b4c491e413))
* Adds `BaseKernel` default args ([5e4a020](https://github.com/devuri/wp-env-config/commit/5e4a020911853d0f613636b65026706cd5948675))
* Adds `static::detect_error()` and `static::env()` ([b310e16](https://github.com/devuri/wp-env-config/commit/b310e16f680a1261e7150e15b6cc074bc14643ea))
* adds a list of setup options ([9dea7b3](https://github.com/devuri/wp-env-config/commit/9dea7b39933c05d42bb8b79e57c45d1fdb7fdd75))
* adds config method in class Setup ([5a5502b](https://github.com/devuri/wp-env-config/commit/5a5502b2a86712dca1434ae511ba6be310a8021d))
* Adds docs dir ([4f89446](https://github.com/devuri/wp-env-config/commit/4f89446f0a706283c4a8c9cdcbd124c389ca637e))
* adds Environment::secure() ([8a2f109](https://github.com/devuri/wp-env-config/commit/8a2f1099d9be5fd21466d8a674a583be050edee4))
* adds Exception try catch block ([c71034f](https://github.com/devuri/wp-env-config/commit/c71034f3182e510f62ac2418a8d2c570f9cd20df))
* adds getEnvironment() to get the current Environment setup ([46f65d5](https://github.com/devuri/wp-env-config/commit/46f65d550a054ad67e9c9128f66743b6615099eb))
* Adds Kernel ([8fc96c2](https://github.com/devuri/wp-env-config/commit/8fc96c20ecd8034d0691c6a05d634732a225628b))
* adds more error reporting for `debug` ([7c55d36](https://github.com/devuri/wp-env-config/commit/7c55d36c193deb5f3325c89f1901fee9977ea150))
* Adds tests for `BaseKernel` ([b18b06c](https://github.com/devuri/wp-env-config/commit/b18b06c2a2cf652e6d27a91081d87d90d8ecda16))
* can now disable and bypass the default setup process ([617938a](https://github.com/devuri/wp-env-config/commit/617938a07228cde57087be0379a9b3efe77a8588))
* constant can be overridden in wp-config.php, add Directory $path ([e9fa1b5](https://github.com/devuri/wp-env-config/commit/e9fa1b50cc5e0ea33d5a278e6485de9ea6cce0ae))
* defines Environment types ([36e7778](https://github.com/devuri/wp-env-config/commit/36e7778f0a1b66cf60a510102360564ef11e2b70))
* simplify environment setup, allow bypass of default setup ([8ef04d5](https://github.com/devuri/wp-env-config/commit/8ef04d5009528209fdcbf0e33a733707f2bb88aa))


### Bug Fixes

* debug settings, adds DISALLOW_FILE_EDIT ([e908ae1](https://github.com/devuri/wp-env-config/commit/e908ae181bb1680dc47fdd8a4fbacdeb884bcaf1))
* fix ConfigInterface ([3570754](https://github.com/devuri/wp-env-config/commit/35707547213bbc77665a9d9d94cc851d300bdcd0))
* fix example file ([c84cd88](https://github.com/devuri/wp-env-config/commit/c84cd8852a8e31bde1f600c11521bffb2c23bf79))
* fixes rreturn type set to ConfigInterface ([338912a](https://github.com/devuri/wp-env-config/commit/338912a62a8b730181362868a42e8db731f7e93a))
* remove string constraint in uploads param ([fb5ae22](https://github.com/devuri/wp-env-config/commit/fb5ae220fd1c6cd0a1c81053bdb3d7368e537b4c))
