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

###### Install TinyMVC

1. Go to [the offical website](http://www.tinymvc.com/download/) to download the latest version.
2. Extract the `tinymvc` folder only (you don't need the rest).
 - If you plan to use TinyMVC for another project, put it in a global location.
 - If you don't, you can just put it in the same directory.

###### Create some folders

```
mkdir -p htdocs/img/appsicons/
```

###### Edit the `index.php` file

1. Replace PATH_TO_TinyMVC_GLOBAL_FILES with the path of the `tinymvc` folder you previously installed.
2. Replace PATH_TO_THIS_REPO_ROOT with the path of the website.

```
cp templates/index.php.template htdocs/index.php
$EDITOR htdocs/index.php
```

###### Install the database

to do

###### Edit the configuration of the database

```
cp templates/config_database.php.template myapp/configs/config_database.php
$EDITOR myapp/configs/config_database.php
```

###### Install the unofficial Android Market API:

```
wget https://github.com/splitfeed/android-market-api-php/archive/master.zip
unzip master.zip
mv master myapp/plugins/android-market-api-php
rm master.zip
```