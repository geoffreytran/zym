================
Zym User Bundle
================

This bundle is an extension of `FOSUserBundle <https://github.com/FriendsOfSymfony/UserBundle>`_
and provides the ability to login with e-mails or usernames.

Requirements
============
 - FOSUserBundle [https://github.com/FriendsOfSymfony/UserBundle]

Installing
==========

Add UserBundle to your vendor/bundles/ dir
-------------------------------------------

::
    $ git submodule add git@git.zym.com:symfony/zymuserbundle.git vendor/bundles/Zym/Bundle/UserBundle

Add the Zym namespace to your autoloader
----------------------------------------

::

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Zym' => __DIR__.'/../vendor/bundles',
        // your other namespaces
    );

Add UserBundle to your application kernel
-----------------------------------------

::

    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Zym\Bundle\UserBundle\ZymUserBundle(),
            // ...
        );
    }

Configure your project
----------------------

::
    # app/config/zym_user.yml
    zym_user:
        db_driver: orm # Match the db driver value with fos_user.db_driver