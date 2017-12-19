# PommFosUserBundle

FosUserBundle driver for PommProject 2

This bundle permit to use FosUserBundle with Pomm easily.

## Installation

With composer :
```
composer require evkoh/pomm-project-fos-user-bundle dev-master
```

You can execute [SQL script](src/lib/Resources/database/tables.sql) to create table.
It's an example to use with default configuration.

You can also create users with the fos_user command (See https://symfony.com/doc/current/bundles/FOSUserBundle/command_line_tools.html)

## Configuration

In `app/config/config.yml`, add configuration below :

```
parameters:
    pomm_fos_user.connection_name: '%YOUR_DATABASE_CONNECTION%'   # Default is "default"

fos_user:
    db_driver: custom
    firewall_name: main
    user_class: EvKoh\PommProjectFosUserBundle\Model\User
    from_email:
        address:        noreply@myhost.ltd
        sender_name:    Do not reply
    service:
        user_manager: pomm_fos_user_bundle.user_manager
```

And that's all.

## Overriding

You can use another table/model to store your users.

In `app/config/config.yml`, change configuration below :

parameters:
    pomm_fos_user.user_model_class: Path\To\MyOwnUserModel

fos_user:
    user_class: Path\To\MyOwnUser

Those two classes must inherit from `EvKoh\PommProjectFosUserBundle\Model\User` and `EvKoh\PommProjectFosUserBundle\Model\UserModel` on your own bundle to override this model.

See an example of implementation here : https://github.com/vibby/germ/blob/master/src/GermBundle/Model/Germ/PersonSchema/AccountModel.php
