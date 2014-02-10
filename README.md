AndroidReviews
==============

Install the Website
-------------------

### Requirements

* A web server (ex: Apache)
* PHP 5.3+ with curl support
* MySQL
* `lessc` (installed from packages or `npm`)

### Manual installation required

###### Get the website!

Clone or download it from [GitHub](https://github.com/db0company/AndroidReviews).
Move to the directory you just created with the sources `cd AndroidReviews`

###### Install TinyMVC

1. Go to [the offical website](http://www.tinymvc.com/download/) to download the latest version.
2. Extract the `tinymvc` folder only (you don't need the rest).
 - If you plan to use TinyMVC for another project, put it in a global location.
 - If you don't, you can just put it in the same directory (`AndroidReviews`)

###### Create and edit the `index.php` file

```shell
cp templates/index.php.template htdocs/index.php
$EDITOR htdocs/index.php
```

1. Replace `PATH_TO_TinyMVC_GLOBAL_FILES` with the path of the `tinymvc` folder you previously installed (depends if you decided to use a global location or keep it with the rest of this website)
2. Replace `PATH_TO_THIS_REPO_ROOT` with the path of the website (probably `AndroidReviews`)


###### Install the database

```shell
# CREATE DATABASE androidreviews
mysql androidreviews < db.sql
```

###### Edit the configuration of the database

```shell
cp templates/config_database.php.template myapp/configs/config_database.php
$EDITOR myapp/configs/config_database.php
```

###### Install the API consumer

```shell
wget https://raw.github.com/db0company/generic-api/master/consumer/php/consumer.php -O myapp/plugins/consumer.php
```

##### In production? Compile the CSS file

```shell
lessc --yui-compress htdocs/less/androidreviews.less > htdocs/css/androidreviews.min.css
```

Edit the header to comment or remove the 2 lines about less files and uncomment the css line.

##### Configure your webserver

Your domain should point on the `htdocs` folder.

Install the API
---------------

You may install it on the same server, but the goal of this API is to serve several countries by having APIs installed in several different countries that communicate with the Android Market.

### Requirements

* A web server (ex: Apache)
* PHP 5.3+ with curl support
* The submodule `generic-api`, should be cloned automatically with the website

### Manual installation required

Stay in the main folder `AndroidReviews'.

###### Create some folders

```shell
mkdir -p api/icons/
```

###### Edit the configuration

```shell
cp templates/conf.php.template api/conf.php
$EDITOR api/conf.php
```

###### Install the unofficial Android Market API:

```shell
wget https://github.com/splitfeed/android-market-api-php/archive/master.zip
unzip master.zip
mv master api/android-market-api-php
rm master.zip
```

###### Configure your Apache

Your domain should point on the `api` folder.
It should start with `country.api.yourdomain` where country is the country code where your server is hosted (`us` or `fr` or instance).
You should also point a sub domain `files.country.api.yourdomain` to the `api/icons` folder.

Add new countries
-----------------

You may install as much countries you'd like by installing the API (only, not the website) on different servers in different countries.
Those countries should be added in the website configuration file. A picture of the flag of the coutry should also be added in the website's folder `htdocs/img/countries`.
