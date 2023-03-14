# Changelog

## [0.30.2](https://github.com/devuri/wp-env-config/compare/v0.30.1...v0.30.2) (2023-03-14)


### Features

* Adds `HTTP_ENV_CONFIG` `get_environment()` ([8737d11](https://github.com/devuri/wp-env-config/commit/8737d11c1a626999582f16fd91aefcc05b0bd614))
* Adds `Nino` Cli ([299a889](https://github.com/devuri/wp-env-config/commit/299a889414fa76c4ccb127b2acffb129fdb2a51d))
* Adds `oops` error handler ([3cbb8f2](https://github.com/devuri/wp-env-config/commit/3cbb8f2454a44dbbf485aaa607b6fc9f3d9d5748))
* Adds `set_env_secret( string $key )` to define secret env vars ([f5a4b84](https://github.com/devuri/wp-env-config/commit/f5a4b84cc9c7954ad74ac1b98404339b74b0e062))
* Adds new `core` plugin ([cb219d8](https://github.com/devuri/wp-env-config/commit/cb219d82a8fb0f719662cf832954869681f98886))
* Hash env output on the command line ([05a6eb2](https://github.com/devuri/wp-env-config/commit/05a6eb252204a4bfb2f7a281013586c999ad2b45))
* optionally pass in the `HttpKernel` instance ([50f2d92](https://github.com/devuri/wp-env-config/commit/50f2d927fb8f931e9ed643789ede52c170c5dca0))


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
* Adds `HttpKernel` default args ([5e4a020](https://github.com/devuri/wp-env-config/commit/5e4a020911853d0f613636b65026706cd5948675))
* Adds `static::detect_error()` and `static::env()` ([b310e16](https://github.com/devuri/wp-env-config/commit/b310e16f680a1261e7150e15b6cc074bc14643ea))
* adds a list of setup options ([9dea7b3](https://github.com/devuri/wp-env-config/commit/9dea7b39933c05d42bb8b79e57c45d1fdb7fdd75))
* adds config method in class Setup ([5a5502b](https://github.com/devuri/wp-env-config/commit/5a5502b2a86712dca1434ae511ba6be310a8021d))
* Adds docs dir ([4f89446](https://github.com/devuri/wp-env-config/commit/4f89446f0a706283c4a8c9cdcbd124c389ca637e))
* adds Environment::secure() ([8a2f109](https://github.com/devuri/wp-env-config/commit/8a2f1099d9be5fd21466d8a674a583be050edee4))
* adds Exception try catch block ([c71034f](https://github.com/devuri/wp-env-config/commit/c71034f3182e510f62ac2418a8d2c570f9cd20df))
* adds getEnvironment() to get the current Environment setup ([46f65d5](https://github.com/devuri/wp-env-config/commit/46f65d550a054ad67e9c9128f66743b6615099eb))
* Adds Kernel ([8fc96c2](https://github.com/devuri/wp-env-config/commit/8fc96c20ecd8034d0691c6a05d634732a225628b))
* adds more error reporting for `debug` ([7c55d36](https://github.com/devuri/wp-env-config/commit/7c55d36c193deb5f3325c89f1901fee9977ea150))
* Adds tests for `HttpKernel` ([b18b06c](https://github.com/devuri/wp-env-config/commit/b18b06c2a2cf652e6d27a91081d87d90d8ecda16))
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
