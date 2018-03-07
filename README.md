# VirtueMart Context
## By [Edmonds Commerce](https://www.edmondscommerce.co.uk)

### Installation

Install via composer

composer require edmondscommerce/behat-virtuemart-context

### Include Contexts in Behat Configuration
``` yaml
default:
    suites:
        default:
            contexts:
                - EdmondsCommerce\BehatVirtueMart\VirtuemartAdminContext
                    adminUser: adminUserName
                    adminPassword: adminPassword
                    adminPath: uri/to/admin
                - EdmondsCommerce\BehatVirtueMart\VirtuemartFrontContext

```

The tests do assume the default out of the box theme.
