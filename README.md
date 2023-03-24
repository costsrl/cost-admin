CostAdmin
=======

**What is CostAdmin?**

CostAdmin is a Module for Manage ACL / NAVIGATION DATA based on Laminas Framework 2 -3 

**What exactly does CostAdmin?**

CsnNavigation has been created with educational purposes to demonstrate how Navigation can be done. It is fully functional.

Installation
============

Installation via composer is supported, just make sure you've set ```"minimum-stability": "dev"```
in your ```composer.json```file and after that run ```php composer.phar require cost/cost-navigation:dev-master```

Go to your application configuration in ```./config/application.config.php```and add 'CostNavigation'.
An example application configuration could look like the following:


open composer.json and add under auotload key

"autoload" : {
    "psr-4" : {
      "CostAdmin\\" : "vendor/cost/cost-admin/src",
    }
```

"repositories": [
        {
            "type": "vcs",
            "url": "http://git.cost.it/cost/cost-admin.git"
        }
    ]



depends on:
1) CostBase
2) CostAuthentication
3) CostAuthorization


```
'modules' => array(
    'Application',
    'Zf2datatable',
    'CostBase',
    'CostAuthentication',
    'CostAuthorization',
    'CostAdmin'
)```


Add migration file for Doctrine Migration : run public/index.php migrations:migrate
note: for mssql use [user] instead of user table 