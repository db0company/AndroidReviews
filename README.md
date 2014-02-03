AndroidReviews
==============

Install it
-----------

### Requirements

* A web server (ex: Apache)
* PHP 5.3+

### Manual installation required

###### Get the website!

Clone or download it from [GitHub](https://github.com/db0company/AndroidReviews).
Move to the directory you just created with the sources `cd AndroidReviews`

###### Install TinyMVC

1. Go to [the offical website](http://www.tinymvc.com/download/) to download the latest version.
2. Extract the `tinymvc` folder only (you don't need the rest).
 - If you plan to use TinyMVC for another project, put it in a global location.
 - If you don't, you can just put it in the same directory (`AndroidReviews`)

###### Create some folders

```shell
cd AndroidReviews
mkdir -p htdocs/img/appsicons/
```

###### Create and edit the `index.php` file

```shell
cp templates/index.php.template htdocs/index.php
$EDITOR htdocs/index.php
```

1. Replace `PATH_TO_TinyMVC_GLOBAL_FILES` with the path of the `tinymvc` folder you previously installed (depends if you decided to use a global location or keep it with the rest of this website)
2. Replace `PATH_TO_THIS_REPO_ROOT` with the path of the website (probably `AndroidReviews`)


###### Install the database

```shell
mysql androidreviews < db.sql
```

###### Edit the configuration of the database

```shell
cp templates/config_database.php.template myapp/configs/config_database.php
$EDITOR myapp/configs/config_database.php
```

###### Install the unofficial Android Market API:

```shell
wget https://github.com/splitfeed/android-market-api-php/archive/master.zip
unzip master.zip
mv master myapp/plugins/android-market-api-php
rm master.zip
```

###### Install the API consumer

```shell
wget https://raw.github.com/db0company/generic-api/master/consumer/php/consumer.php -O myapp/plugins/consumer.php
```

##### Compile the CSS file

```shell
lessc --yui-compress htdocs/less/androidreviews.less > htdocs/css/androidreviews.min.css
```
