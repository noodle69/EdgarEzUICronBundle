# EdgarEzUICronBundle

## Installation

### Get the bundle using composer

Add EdgarEzUICronBundle by running this command from the terminal at the root of
your symfony project:

```bash
composer require edgar/ez-uicron-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Edgar\CronBundle\EdgarCronBundle(),
        new Edgar\EzUICronBundle\EdgarEzUICronBundle(),
        // ...
    );
}
```

## Add doctrine ORM support

in yout ezplatform.yml, add

```yaml
doctrine:
    orm:
        auto_mapping: true
```

## Update your SQL schema

```
php bin/console doctrine:schema:update --force
```

## Add routing

Add to your global configuration app/config/routing.yml

```yaml
edgar.ezuicron:
    resource: '@EdgarEzUICronBundle/Resources/config/routing.yml'
    prefix: /_uicron
    defaults:
        siteaccess_group_whitelist: 'admin_group'    
```
