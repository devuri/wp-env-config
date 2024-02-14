# Changelog

## [3.3.0](https://github.com/devuri/wp-env-config/compare/v3.2.0...v3.3.0) (2024-02-14)


### Features

* adds md5 hash for `APP_TENANT_ID` ([775b00b](https://github.com/devuri/wp-env-config/commit/775b00ba920b363ae0cf07922d401e3ea792cf8a))
* in `tenancy.php` we can override `PUBLIC_WEB_DIR` and `APP_CONTENT_DIR` ([74b6524](https://github.com/devuri/wp-env-config/commit/74b6524c8b0ddf816335eadb6f11d99097bd133d))
* tenant ID is always set even in non multi-tenat ([6d1fdae](https://github.com/devuri/wp-env-config/commit/6d1fdaed789aad40df2e51d835bda7fa0f88a603))
* use `wp_terminate` for `maintenance` which now can be per tenant ([baba0c9](https://github.com/devuri/wp-env-config/commit/baba0c97d5254500fc603802744e741cc08b3bec))


### Bug Fixes

* add tenat_id in admin footer ([a474e79](https://github.com/devuri/wp-env-config/commit/a474e790f65db2a035f21265eea1ce844968b1e8))
* adds plugin loader to load plugins via `option_active_plugins` filter ([9f453dd](https://github.com/devuri/wp-env-config/commit/9f453ddf9dcab26e7ed538631fb9d9fa0fdfb7c9))
* APP_TENANT_ID can be set to false in the .env to short-cercuit the custom uploads directory behavior. ([66763cc](https://github.com/devuri/wp-env-config/commit/66763cc0a3e4d96e77055bd99540042af78fa4bc))
* is `env( 'IS_MULTITENANT' )` ([290089a](https://github.com/devuri/wp-env-config/commit/290089ab5a7e719c0fc6a5d888afc504db242929))
* update `APP_CONTENT_DIR` so it does not require `/` ([24069bf](https://github.com/devuri/wp-env-config/commit/24069bf608bd0e722f37d88f813caaa990a43625))


### Miscellaneous Chores

* build ([c341a83](https://github.com/devuri/wp-env-config/commit/c341a835ac71683855951678f346ed29e3dfd3b4))
* build ([7062cba](https://github.com/devuri/wp-env-config/commit/7062cba6c555f615e994229903bb8c2836cc2b01))
* codefix ([e8ed662](https://github.com/devuri/wp-env-config/commit/e8ed6625ae8fcda5ccb1ccf7f0a37553ae309da8))

## [3.2.0](https://github.com/devuri/wp-env-config/compare/v3.1.2...v3.2.0) (2024-02-12)


### Features

* add `REQUIRE_TENANT_CONFIG` enforces a strict requirement ([60fe441](https://github.com/devuri/wp-env-config/commit/60fe4411f61d963129de089b01433092b22de951))
* enable Tenant-Specific Configuration with app.php ([b5b9c23](https://github.com/devuri/wp-env-config/commit/b5b9c23047dcfbc10f486b65aef99c1845067632))


### Miscellaneous Chores

* build ([351a624](https://github.com/devuri/wp-env-config/commit/351a6249c8404be8a8352867e1b9ca2488fb7637))
* build ([d74a24e](https://github.com/devuri/wp-env-config/commit/d74a24ea780a99ea7e69b934035a1d36967c6c11))
* fix file path ([d441619](https://github.com/devuri/wp-env-config/commit/d441619de1c7856128ee90c7bc077554e4680c55))

## [3.1.2](https://github.com/devuri/wp-env-config/compare/v3.1.1...v3.1.2) (2024-02-11)


### Bug Fixes

* better message for missing landlord data ([73447a1](https://github.com/devuri/wp-env-config/commit/73447a1fc415c4c953694364a55963657b49e0b9))

## [3.1.1](https://github.com/devuri/wp-env-config/compare/v3.1.0...v3.1.1) (2024-02-11)


### Bug Fixes

* do not show message it may contain dir info and full serv path ([d2acbc0](https://github.com/devuri/wp-env-config/commit/d2acbc0a7f62a06810bec4c42a8a027805e926a6))

## [3.1.0](https://github.com/devuri/wp-env-config/compare/v3.0.1...v3.1.0) (2024-02-11)


### Features

* auto generate env file if it does not exist ([58e8214](https://github.com/devuri/wp-env-config/commit/58e821446c35a8835876d9c2e901bd8761aab553))


### Miscellaneous Chores

* build ([48020ac](https://github.com/devuri/wp-env-config/commit/48020ac7c07da4d445075368f84176a9a537152c))

## [3.0.1](https://github.com/devuri/wp-env-config/compare/v3.0.0...v3.0.1) (2024-02-10)


### Miscellaneous Chores

* refactor ([95dbd68](https://github.com/devuri/wp-env-config/commit/95dbd68018e23ac33887e62d98f2e44b946e6f72))

## [3.0.0](https://github.com/devuri/wp-env-config/compare/v2.2.1...v3.0.0) (2024-02-10)


### ⚠ BREAKING CHANGES

* get tenants from database table `tenants` requires plugin `Tenancy`

### Features

* get tenant from DB for multi-tenant support ([6854fdf](https://github.com/devuri/wp-env-config/commit/6854fdf0248fa6811eebe3578066eff3eceb23e6))
* get tenants from database table `tenants` requires plugin `Tenancy` ([363d2c0](https://github.com/devuri/wp-env-config/commit/363d2c00e2e08c0ecaa68cb405f9cb03e4484836))
* simplify multi-tenant switching using defined database tables ([63936ce](https://github.com/devuri/wp-env-config/commit/63936cefc87a39016247bf5dc48d2ce0835bc192))


### Bug Fixes

* add _is_multitenant() ([a528712](https://github.com/devuri/wp-env-config/commit/a5287126aeb052375529f1d2f04e34ef7e6ae32a))


### Miscellaneous Chores

* add const stubs in test mode ([1727b34](https://github.com/devuri/wp-env-config/commit/1727b34af2f035f04d240346801f578c2a0a652f))
* build ([1c9b299](https://github.com/devuri/wp-env-config/commit/1c9b29986b094229678b2d309bb4795c41834101))
* build ([1a82379](https://github.com/devuri/wp-env-config/commit/1a82379a2a08d3218f124e0c086b3952786c3254))

## [2.2.1](https://github.com/devuri/wp-env-config/compare/v2.2.0...v2.2.1) (2024-02-08)


### Bug Fixes

* adds `EnvTypes` class ([721bcec](https://github.com/devuri/wp-env-config/commit/721bcec9900d1ff60766ccbfa5917dd98ebf3819))
* fix config location ([05a53d9](https://github.com/devuri/wp-env-config/commit/05a53d9c4a26fe7bfb233128c4539e3b23ae2838))
* if use `WP_ENVIRONMENT_TYPE` if defined in `config` will override  .env ([dfcc1ea](https://github.com/devuri/wp-env-config/commit/dfcc1ea792df59e2f3a638224f9f045513df94ce))


### Miscellaneous Chores

* build ([8e37399](https://github.com/devuri/wp-env-config/commit/8e37399c2aaf07578e700973d292b32b0a089950))

## [2.2.0](https://github.com/devuri/wp-env-config/compare/v2.1.1...v2.2.0) (2024-02-08)


### Features

* add `AppHostManager` to streamline how we handle http, replaces http functions ([a7d10d1](https://github.com/devuri/wp-env-config/commit/a7d10d1712886664c6140ea490d34489e85accb8))
* adds new `wp_terminate()` function  ([116af93](https://github.com/devuri/wp-env-config/commit/116af930cc2a9b12e0d0801326e32cbd044d2bf5))
* Improvements for multi-tenant applications ([e32166c](https://github.com/devuri/wp-env-config/commit/e32166cb86a8f62a11468d15affd1c15cab27f9c))


### Bug Fixes

* ensure we have tenant ID ([b3e392c](https://github.com/devuri/wp-env-config/commit/b3e392c33a137171776a908a930bab15bc453f4c))
* fix FILTER_UNSAFE_RAW ([2b4c2df](https://github.com/devuri/wp-env-config/commit/2b4c2df8fef01cd355e55767418686f202209ae2))
* Loads tenant-specific  `config.php` or default `config.php` ([b31d7bb](https://github.com/devuri/wp-env-config/commit/b31d7bb094db6fdf96ad7d542271791598fe3884))
* separate uploads for multi tenant. ([9c8d82c](https://github.com/devuri/wp-env-config/commit/9c8d82c1a5382ac01769ccc2213b4accbe589a4a))
* user needs 'manage_tenant' or Remove delete action link for plugins ([f3df3ec](https://github.com/devuri/wp-env-config/commit/f3df3ecd762c9713f58c3512c449a11850bcbbf2))


### Miscellaneous Chores

* build ([a061004](https://github.com/devuri/wp-env-config/commit/a061004669cec856e49009e7fec03d03d7af576b))
* build ([d81320c](https://github.com/devuri/wp-env-config/commit/d81320c3af6033d6a65a76aee343a91ac4db857b))
* codefix ([6cfc2e3](https://github.com/devuri/wp-env-config/commit/6cfc2e366daac12a78a154bbd85128506e9d03d2))
* update tests ([afa5b5f](https://github.com/devuri/wp-env-config/commit/afa5b5fa57e5c374cb7792a026da7c118dcbee4e))

## [2.1.1](https://github.com/devuri/wp-env-config/compare/v2.1.0...v2.1.1) (2024-02-06)


### Bug Fixes

* include psr/log version to fix php7.4 errors ([e9b038d](https://github.com/devuri/wp-env-config/commit/e9b038d07d502830077c842217c92b8f1f4c525f))
* psr fix ([d78fecf](https://github.com/devuri/wp-env-config/commit/d78fecff3b2c915c36010be5fa59c583514b4cda))
* Update symfony/error-handler ([4222d9a](https://github.com/devuri/wp-env-config/commit/4222d9a9a266fde3021d7dbecd897e053b0b7181))

## [2.1.0](https://github.com/devuri/wp-env-config/compare/v2.0.0...v2.1.0) (2024-01-05)


### Features

* add `get_default_file_names()` to retrieves the default file names ([e705f0e](https://github.com/devuri/wp-env-config/commit/e705f0eaa7bc78df1a667b6e30fe3282406ef8df))


### Bug Fixes

* update public key handler ([85713aa](https://github.com/devuri/wp-env-config/commit/85713aa115410a1636211d109206d129317083c2))


### Miscellaneous Chores

* build ([56bca88](https://github.com/devuri/wp-env-config/commit/56bca88224b605498d93be611cca5760a12f6847))

## [2.0.0](https://github.com/devuri/wp-env-config/compare/v1.3.3...v2.0.0) (2023-12-09)


### ⚠ BREAKING CHANGES

* re `App.php` to `Urisoft\App\Http\AppFramework`

### Miscellaneous Chores

* build ([10289e9](https://github.com/devuri/wp-env-config/commit/10289e9b9ff276c4c59095235e37c027c1db7f46))


### Code Refactoring

* re `App.php` to `Urisoft\App\Http\AppFramework` ([defe801](https://github.com/devuri/wp-env-config/commit/defe80168d477f4420061bbf4aacf7e9f08ada8f))

## [1.3.3](https://github.com/devuri/wp-env-config/compare/v1.3.2...v1.3.3) (2023-11-03)


### Bug Fixes

* add `app_sanitizer()` ([bdfd3cc](https://github.com/devuri/wp-env-config/commit/bdfd3ccf85536faccac27dd36f79504247d64a70))
* env local added todefault files ([4a563fb](https://github.com/devuri/wp-env-config/commit/4a563fbc58c5aa260830d6208301a2aa8a8ff947))
* exit for when tenant is not defined ([bf058a7](https://github.com/devuri/wp-env-config/commit/bf058a798be90e49e6941be23ce605adfdf6930d))

## [1.3.2](https://github.com/devuri/wp-env-config/compare/v1.3.1...v1.3.2) (2023-11-02)


### Bug Fixes

* remove 'manage_options' check ([a82e43d](https://github.com/devuri/wp-env-config/commit/a82e43db6f90af29d4e91e7b252a2aec40e7d8b3))

## [1.3.1](https://github.com/devuri/wp-env-config/compare/v1.3.0...v1.3.1) (2023-11-02)


### Bug Fixes

* fixes the elementor pro wpenv auto activation ([fe7cb72](https://github.com/devuri/wp-env-config/commit/fe7cb722abfdcb5232cc353995e8ecc9d38eaade))

## [1.3.0](https://github.com/devuri/wp-env-config/compare/v1.2.0...v1.3.0) (2023-11-02)


### Features

* adds plugins list admin page ([c929c9d](https://github.com/devuri/wp-env-config/commit/c929c9d277d20664ba7874d6c2a741480b318bc8))


### Miscellaneous Chores

* **master:** release 1.2.0 ([f68727a](https://github.com/devuri/wp-env-config/commit/f68727a39260509d85be6291aad155f5476e4303))

## [1.2.0](https://github.com/devuri/wp-env-config/compare/v1.1.0...v1.2.0) (2023-11-01)


### Features

* add option to Auto-Activate Elementor as Hourly Cron. ([08fec09](https://github.com/devuri/wp-env-config/commit/08fec09efca45028cd5aee7de0fadec613d58663))
* disable `application_passwords` in .env file ([c3df479](https://github.com/devuri/wp-env-config/commit/c3df479fab54612ffc03965dc7a0b5fdab83717f))


### Bug Fixes

* fixes the core app events runner ([844cf5b](https://github.com/devuri/wp-env-config/commit/844cf5b70a4b1122b2627fff658db2c3cc8e1e09))
* use upgraded encryption 0.3 ([2d479fb](https://github.com/devuri/wp-env-config/commit/2d479fb1782b5096f3a35055195cd3ce237267a6))


### Miscellaneous Chores

* build ([500918d](https://github.com/devuri/wp-env-config/commit/500918dbf94c31ff714bdafc029c2b5ddb8fb894))

## [1.1.0](https://github.com/devuri/wp-env-config/compare/v1.0.5...v1.1.0) (2023-10-28)


### Features

* update encryption to `0.2` ([a96eb0a](https://github.com/devuri/wp-env-config/commit/a96eb0a50c41acc8905bd5752d51e492d04034bf))

## [1.0.5](https://github.com/devuri/wp-env-config/compare/v1.0.4...v1.0.5) (2023-10-28)


### Miscellaneous Chores

* build ([908c8b1](https://github.com/devuri/wp-env-config/commit/908c8b197708701e7062ac13176aab23fdadf119))

## [1.0.4](https://github.com/devuri/wp-env-config/compare/v1.0.3...v1.0.4) (2023-10-28)


### Bug Fixes

* new env function fix ([5eeef88](https://github.com/devuri/wp-env-config/commit/5eeef882535b1a463ec1debb8abe4b11c78bea9a))


### Miscellaneous Chores

* build ([b792b53](https://github.com/devuri/wp-env-config/commit/b792b53d33e3de8f48ff749ac5de52b783669ef8))

## [1.0.3](https://github.com/devuri/wp-env-config/compare/v0.8.2...v1.0.3) (2023-10-27)


### Features

* adds Generates a list of WordPress plugins in Composer format ([1f45e9c](https://github.com/devuri/wp-env-config/commit/1f45e9ca951674ce1c8783004770eacbded919c6))


### Miscellaneous Chores

* build ([3badbef](https://github.com/devuri/wp-env-config/commit/3badbefc9e734e197fd43b9650ee70b6a0022625))

## [0.8.2](https://github.com/devuri/wp-env-config/compare/v0.8.1...v0.8.2) (2023-10-26)


### Features

* Adds a 'WP is not installed' message for dev environments. ([f8b5189](https://github.com/devuri/wp-env-config/commit/f8b51897a9759e9445306649c7d299a0fe39b320))


### Bug Fixes

* adds required `IS_MULTI_TENANT_APP` in .env ([d1ea36c](https://github.com/devuri/wp-env-config/commit/d1ea36c5f4253924bf18f5d9a8f4e088f40dee9c))


### Miscellaneous Chores

* build ([672f12c](https://github.com/devuri/wp-env-config/commit/672f12c7b714bc3bf3283aa00d602496af3e6abb))

## [0.8.1](https://github.com/devuri/wp-env-config/compare/v0.8.0...v0.8.1) (2023-10-23)


### Bug Fixes

* fixes updates on bool ([cea92af](https://github.com/devuri/wp-env-config/commit/cea92af60b266a8a9a406396ce0cf1cb8c1e34a7))

## [0.8.0](https://github.com/devuri/wp-env-config/compare/v0.7.7...v0.8.0) (2023-10-21)


### ⚠ BREAKING CHANGES

* Multi-Tenant Support

### Features

* add filter `wpenv_powered_by` ([19670d9](https://github.com/devuri/wp-env-config/commit/19670d9a4f3055aa5a02ee466d3ace6173249f49))
* adds Color Scheme ([2f6d645](https://github.com/devuri/wp-env-config/commit/2f6d6459aae53ad3267c1fcbfb3384c140f0ac95))
* Multi-Tenant Support ([70b4f02](https://github.com/devuri/wp-env-config/commit/70b4f02b4230fcce59ded6365a176d87e2cb6053))
* WIP adds `multi_tenant` support ([b87c5fe](https://github.com/devuri/wp-env-config/commit/b87c5feece6b9e51fb7122739e38a67377097e11))


### Bug Fixes

* adds `$http_env_type` property ([072d023](https://github.com/devuri/wp-env-config/commit/072d02352abedb9b6173eb017707ceac0e7b6f52))
* get_user_by can be false ([9ed3826](https://github.com/devuri/wp-env-config/commit/9ed3826c38cab42d70cc9f83031606eb1c5553c8))


### Miscellaneous Chores

* build ([7d68095](https://github.com/devuri/wp-env-config/commit/7d68095829788df53f465f0c6f2df537276a0f95))
* build ([1face78](https://github.com/devuri/wp-env-config/commit/1face780ff4a4c5f5b8e669ab8f688130c87e8d1))

## [0.7.7](https://github.com/devuri/wp-env-config/compare/v0.7.6...v0.7.7) (2023-09-25)


### Features

* adds `wpenv_auto_login` action and wp_user_exists() method ([3f24141](https://github.com/devuri/wp-env-config/commit/3f241410192081dce309cbfe47e3263dfca4f8be))

## [0.7.6](https://github.com/devuri/wp-env-config/compare/v0.7.5...v0.7.6) (2023-09-24)


### Bug Fixes

* adds sub domain for Integrated Version Control label ([4d69a2d](https://github.com/devuri/wp-env-config/commit/4d69a2d9af8af5c2cb2a573e4232958705c839e8))
* fixes count on available updates ([7f8ac5e](https://github.com/devuri/wp-env-config/commit/7f8ac5ebb92a71ee25ec9aa6a205463c03c0f70d))
* removes `get_core_updates` use `transient( 'update_core' )` ([71af3b6](https://github.com/devuri/wp-env-config/commit/71af3b6d98af61811ae693762be16fcfd9ee6261))

## [0.7.5](https://github.com/devuri/wp-env-config/compare/v0.7.4...v0.7.5) (2023-09-23)


### Bug Fixes

* remove console split to standalone repo `devuri/wpenv-console` ([e88794d](https://github.com/devuri/wp-env-config/commit/e88794d70830f82a0d87dd2926ce548b555aef4f))

## [0.7.4](https://github.com/devuri/wp-env-config/compare/v0.7.3...v0.7.4) (2023-09-15)


### Bug Fixes

* debug log dates use 'm-d-Y' ([001ac17](https://github.com/devuri/wp-env-config/commit/001ac17703e62ea11d092f39962fa0b8b666ca2d))

## [0.7.3](https://github.com/devuri/wp-env-config/compare/v0.7.2...v0.7.3) (2023-09-15)


### Features

* adds `get_user_constants()` and `get_server_env()` ([4d4aa8a](https://github.com/devuri/wp-env-config/commit/4d4aa8ab66b354c5c3365cd7c37472c2e13c7423))
* adds a way to Display Updates in Secure Environment ([dc30ff8](https://github.com/devuri/wp-env-config/commit/dc30ff8d9b88468c67c17a4a08b5c0c95f06a8c1))
* adds loginkey so we can do `nino config loginkey` ([78394c2](https://github.com/devuri/wp-env-config/commit/78394c261d890350d2c1cc14bb34b94a6367a5b0))


### Bug Fixes

* adds message saying the key wass added ([2c8642b](https://github.com/devuri/wp-env-config/commit/2c8642bb1d876f92ead4e66f99167cd0db5797d1))
* adds some context on failed auto login ([2ceacd8](https://github.com/devuri/wp-env-config/commit/2ceacd853e26dae41ee8e5c9262f1b86f43646a7))
* update package installer: `php nino n:i -p theme brisko` ([e48a1f5](https://github.com/devuri/wp-env-config/commit/e48a1f5852557d933ec0745ea83351fd1acac83a))


### Miscellaneous Chores

* build ([38f356c](https://github.com/devuri/wp-env-config/commit/38f356c347c519145424d135b17b1d442117fc98))

## [0.7.2](https://github.com/devuri/wp-env-config/compare/v0.7.1...v0.7.2) (2023-09-10)


### Bug Fixes

* `ConstantConfigTrait` is now `ConstantBuilderTrait` ([f24c5f8](https://github.com/devuri/wp-env-config/commit/f24c5f861bb4ef0a142226479d49a0555c1f18f4))


### Miscellaneous Chores

* adds test ([d572ab1](https://github.com/devuri/wp-env-config/commit/d572ab167aab73fc629aff7067a0cf11b8f1b262))
* codefix ([b91b91f](https://github.com/devuri/wp-env-config/commit/b91b91f6b0a76daa058b94ebb47a4e82ab47840c))
* docs ([45740ec](https://github.com/devuri/wp-env-config/commit/45740ec19cee0eeb1dbef880717276058178c0e5))

## [0.7.1](https://github.com/devuri/wp-env-config/compare/v0.7.0...v0.7.1) (2023-09-09)


### Bug Fixes

* configMap() is get_constant_map() ([c537d75](https://github.com/devuri/wp-env-config/commit/c537d75f6a9297a9b6fb5e6ef7afdb4b689ced90))

## [0.7.0](https://github.com/devuri/wp-env-config/compare/v0.6.6...v0.7.0) (2023-09-09)


### ⚠ BREAKING CHANGES

* replace `ConfigTrait` with `ConstantConfigTrait`

### Features

* replace `ConfigTrait` with `ConstantConfigTrait` ([780aa6f](https://github.com/devuri/wp-env-config/commit/780aa6fec5783cbe318a86561a7b248af3a8c6d5))


### Bug Fixes

* `Environment` is now `EnvironmentSwitch` adds `EnvInterface` ([de0d579](https://github.com/devuri/wp-env-config/commit/de0d5792a4d61c8b6545867ce93841b4261710fb))


### Miscellaneous Chores

* codefix ([afe79f6](https://github.com/devuri/wp-env-config/commit/afe79f617cb7ea63030dec531598bf3b55511b8e))
* update composer ([512105d](https://github.com/devuri/wp-env-config/commit/512105d0c84060f8fc2c4eb89e8807c7f9f7f910))

## [0.6.6](https://github.com/devuri/wp-env-config/compare/v0.6.5...v0.6.6) (2023-09-08)


### Features

* adds `devuri/cpt-meta-box` 0.4 `use DevUri\PostTypeMeta\MetaBox` ([a4f8024](https://github.com/devuri/wp-env-config/commit/a4f802475195b32dbd40f1343566efa518176661))
* adds `php nino up` and `php nino down` handles maintenance mode, `up` will create .maintenance in the public dir and `down` will remove it ([4d9705c](https://github.com/devuri/wp-env-config/commit/4d9705ced87d4431f0fe53fa171e312499608997))
* new `config()` function to get config options ([757a06f](https://github.com/devuri/wp-env-config/commit/757a06f08e80130843db18851a68749ab23b8f56))


### Bug Fixes

* autofix port number on local ([9437ebc](https://github.com/devuri/wp-env-config/commit/9437ebc891bae8659e67147324c284d6f1015c14))
* only enable auto login if `WPENV_AUTO_LOGIN_SECRET_KEY` is available ([e8aed54](https://github.com/devuri/wp-env-config/commit/e8aed5409d6b8484775a21f8874919d77b6a66e0))


### Miscellaneous Chores

* build ([982b319](https://github.com/devuri/wp-env-config/commit/982b31940bdc24e72e3237452ff19a886deb2002))
* docs ([c1678b3](https://github.com/devuri/wp-env-config/commit/c1678b3d49cb621573e62ff837a014206e671af0))
* misc updates for auto login ([d27efd3](https://github.com/devuri/wp-env-config/commit/d27efd3eae920b8121f1ce700aceced57493ff20))

## [0.6.5](https://github.com/devuri/wp-env-config/compare/v0.6.4...v0.6.5) (2023-08-06)


### Bug Fixes

* fixes created dir before cli is run ([b4e064f](https://github.com/devuri/wp-env-config/commit/b4e064f0a86cfc3b28b9bf6e45be5086a53dbc37))

## [0.6.4](https://github.com/devuri/wp-env-config/compare/v0.6.3...v0.6.4) (2023-08-06)


### Features

* `wpi` is now `wp:install` ([483f349](https://github.com/devuri/wp-env-config/commit/483f349f75310d8a30223bf35c1b6eb24aadc774))
* add blog title to install options ([c81e11f](https://github.com/devuri/wp-env-config/commit/c81e11fadd38a4bf1e455212d025f73d962f862f))
* add support for file `Exception` using `Defuse\Crypto\File` ([0e4ecc9](https://github.com/devuri/wp-env-config/commit/0e4ecc9a9b78e9b107d5d9ac3d466966e02b6cec))
* adds `config('key')` for accessing nested data using dot notation ([a21ab49](https://github.com/devuri/wp-env-config/commit/a21ab49d10d299e2167e5bb568da95d8f2caad95))
* adds `devuri/dot-access` ([d975244](https://github.com/devuri/wp-env-config/commit/d975244ad5f4680197cf6b8947b06107c377d573))
* adds `php-encryption` and `Encryption` class ([d02bd3d](https://github.com/devuri/wp-env-config/commit/d02bd3dd65a0647ed45109d507a5dc33dd1dec60))
* adds Auto-Login MU Plugin and CLI ([6b50e33](https://github.com/devuri/wp-env-config/commit/6b50e33ac3e752ecbf539067a3623f1e7dff369f))
* adds Elementor Pro activation class ([557f696](https://github.com/devuri/wp-env-config/commit/557f6968a4779cddab209a419697e9b54dcf05d5))
* adds encrypted backup option ([4251bcf](https://github.com/devuri/wp-env-config/commit/4251bcffda63e8fbf860cd054d41b2367c56f91b))
* adds s3 backup option ([30d4e3f](https://github.com/devuri/wp-env-config/commit/30d4e3f442c1464f219d1b658855128bdb86855c))
* adds TODO Setup Activity Logs. ([939ee99](https://github.com/devuri/wp-env-config/commit/939ee996869cb347e0a68047b5cb3ede02a26bf9))
* better handle on `S3_BACKUP_DIR` now uses domain as default ([3e44c3e](https://github.com/devuri/wp-env-config/commit/3e44c3e98803c7149e08012600d2b025d22ef3e0))
* can generate key file with: `php nino config cryptkey` ([518f63c](https://github.com/devuri/wp-env-config/commit/518f63c0baca7906609254ad61d069264ffd8374))
* fix filename and use month name, add `DELETE_LOCAL_S3BACKUP` ([51bd6e7](https://github.com/devuri/wp-env-config/commit/51bd6e756a0a4169f582c644acfbbc04352f7ccb))
* s3backup_dir env option `S3_BACKUP_DIR` ([f30e6fc](https://github.com/devuri/wp-env-config/commit/f30e6fcfece2fae740f4e453ddabe97e3df0a361))
* use `devuri/encryption` replaces `Encryption` class ([e54da9f](https://github.com/devuri/wp-env-config/commit/e54da9f97d87592ec4d5aaba9f68a88c484caa86))


### Bug Fixes

* append login secret ([7c52612](https://github.com/devuri/wp-env-config/commit/7c526123da9c65d1f4a66c4ee719df0de7a73421))
* better login and token handling ([bae96fd](https://github.com/devuri/wp-env-config/commit/bae96fd83aa1c22816403dcfe99e0671d41b0f8c))
* create bucket is not always an option ([a29c51e](https://github.com/devuri/wp-env-config/commit/a29c51eed5093085dfb7cd1f9c6043027ede834b))
* deactivate is true ([f36b67e](https://github.com/devuri/wp-env-config/commit/f36b67eda562c0f817095ede91b289f26f28cb72))
* encode and ecode ciphertext ([839f667](https://github.com/devuri/wp-env-config/commit/839f6678718bb2313d690885f35266e2da851ce0))
* fix backup, same day backups now use timestamp ([5751540](https://github.com/devuri/wp-env-config/commit/5751540101e524300e33e1d1e3f9281982e2bbf4))
* fixes bucket creation ([12f9378](https://github.com/devuri/wp-env-config/commit/12f9378a4560cffa068b14bca0fbaeeaa3c73e7a))
* missing Encryption ([b9a7ab0](https://github.com/devuri/wp-env-config/commit/b9a7ab0c32238e517245bc97d26b05b5e1cb0bc8))
* move auto login to core Plugin so its always available ([5df8a86](https://github.com/devuri/wp-env-config/commit/5df8a869e0c7c572867e795831dcbe9c3b767208))
* remove detects the error ([11cabef](https://github.com/devuri/wp-env-config/commit/11cabef72ef2b96fbb9d70fd01326d4775f608d3))
* use `symfony/console:5.4.*` for php 7.4 ([ae8b85e](https://github.com/devuri/wp-env-config/commit/ae8b85e01cda9768b6a55112d038a5efd02d47c1))
* use `WPENV_AUTO_LOGIN_SECRET_KEY` ([55a9743](https://github.com/devuri/wp-env-config/commit/55a9743041bb4ea34229fcddbc5a722b1bc45f01))


### Miscellaneous Chores

* build ([f069a9e](https://github.com/devuri/wp-env-config/commit/f069a9e4ed5cb3f31931d2c794cdb9d05d157c0d))
* build ([f5e7405](https://github.com/devuri/wp-env-config/commit/f5e7405eb5b6a73ba495b73f554964923287fb35))
* build ([3b1538d](https://github.com/devuri/wp-env-config/commit/3b1538d796d206c692b7893c0dd35e29b2a98cdb))
* build ([f23350f](https://github.com/devuri/wp-env-config/commit/f23350f80740852dbe4b8ecc16540b8c062562a0))
* build ([3a28e0d](https://github.com/devuri/wp-env-config/commit/3a28e0dcaeb2f9ace070f431edb96dc6186db604))
* build ([363260b](https://github.com/devuri/wp-env-config/commit/363260b5a305195d94819c466ef1d88654e4bcbb))
* build ([c425c57](https://github.com/devuri/wp-env-config/commit/c425c573dc207559cf3ed2bd5cecd36cf61e5a0f))
* build ([0dad801](https://github.com/devuri/wp-env-config/commit/0dad801cc99b812833b04743ba94e1a429cc4881))
* build ([196e182](https://github.com/devuri/wp-env-config/commit/196e182ca81b223f4e9d8a5a2cf0026ccbdaa2c2))
* code build ([1f4bc3c](https://github.com/devuri/wp-env-config/commit/1f4bc3ccab6a42f0bb6933ad4c2cd4d3d16b222b))
* tests for encoded values ([da2e0d3](https://github.com/devuri/wp-env-config/commit/da2e0d39fb88e96be94e9cc4ddbdb39ef74ecad8))

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
