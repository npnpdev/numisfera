# E2E tests

This folder contains tests about ps_banner module. 

## Supported versions

These tests can be run on `1.7.7.x` and `1.7.6.x` PrestaShop versions.

## Stack

In these tests, we used the actual stack :

- [prestashop_test_lib](https://www.npmjs.com/package/prestashop_test_lib) which uses [playwright](https://playwright.dev/) as a node library to automate browsers.
- [mocha](https://mochajs.org/) as a test framework.
- [chai](https://www.chaijs.com/) as an assertion library.
- [js-image-generator](https://www.npmjs.com/package/js-image-generator) as a node library to generate fake images for tests.

## Running tests

Before running tests, you should run `npm install`, to install all dependencies.
### Running all tests

```shell
PS_VERSION='1.7.7' URL_FO='http://localhost.com/' npm run e2e-tests 
```

### Running all tests with fast fail mode

The fast fail mode give us the possibility to abort the run after first test failure. It uses the --bail option from [mocha](https://mochajs.org/#command-line-usage).

This mode is mostly used in CI (like here in Github Actions), to stop the tests as quickly as possible.

```shell
PS_VERSION='1.7.7' URL_FO='http://localhost/prestashop/' npm run e2e-tests-fast-fail
```

### Env variables

Some variables can be used to run tests:

| Parameter           | Description                                          |
|---------------------|----------------------------------------------------- |
| PS_VERSION          | PrestaShop minor version (not patch version) (default to **`1.7.7`**)|
| URL_FO              | URL of your PrestaShop website Front Office (default to **`http://localhost/prestashop/`**) |
| URL_BO              | URL of your PrestaShop website Back Office (default to **`URL_FO + admin-dev/`**) |
| BROWSER             | Specific browser to launch for tests (default to **`chromium`**) |
| HEADLESS            | Boolean to run tests in [headless mode](https://en.wikipedia.org/wiki/Headless_software) or not (default to **`true`**) |

For more variables, please take a look on global vars on [prestashop_test_lib](https://github.com/PrestaShopCorp/prestashop_test_lib/blob/master/kernel/utils/globals.js).

Enjoy :wink: :v: