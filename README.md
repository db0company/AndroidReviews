AndroidReviews
==============

Install it
-----------

### Requirements

* A web server (ex: Apache)
* PHP 5.3+

### Manual installation required

Edit the `index.php` file:

```
mkdir -p htdocs
cp templates/index.php.template htdocs/index.php
$EDITOR htdocs/index.php
```

Create the database.

Edit the database configuration file:
```
cp templates/config_database.php.template myapp/configs/config_database.php
$EDITOR myapp/configs/config_database.php
```

Install the unofficial Android Market API:
```
wget https://github.com/splitfeed/android-market-api-php/archive/master.zip
unzip master.zip
mv master android-market-api-php
rm master.zip
```

