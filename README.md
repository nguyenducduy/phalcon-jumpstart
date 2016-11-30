[Deprecated] Phalcon Jumpstart
================
Fast development web apps with CRUD code generator

[Wiki](https://github.com/nguyenducduy/phalcon-jumpstart/wiki)

## Requirements

- Apache >= 2.0
- PhalconPHP Framwork >=1.3.4
- PHP >=5.4 (extension: Pdo, mbstring, openssl)
- Sphinx Search Engine 2.2.+
- [Sphinx Search](http://sphinxsearch.com/) (Optional)
- [PhalconPHP](http://phalconphp.com/)
- libODBC x64 install CentOS: yum install unixODBC unixODBC-devel postgresql-libs (dependencies of SphinxSE)

## Supported Image Libraries

- GD Library (>=2.0)
- Imagick PHP extension (>=6.5.7)

## Supported Crypt Libraries (using for encrypt cookie)

- PHP-mcrypt Extension

## Note Important

- Write database JSON structure need remove "," character at the end of a block object, array.
- Any INTEGER field in database not null, need to Zero (0) if NULL.

## Additional Information

- After run migration task, login with default admin account:
    - Email: admin@fly.com
    - Password: 1

## .gitignore Settings
```
cache/annotations/*
cache/metadata/*
cache/volt/*
cache/security/*
cache/minified/*
logs/apache/*
logs/app/*
logs/mig/*
public/uploads/*
```

Documentation
================

## Create missing directory

- View .gitignore setting and create directory map with it.

## Migrate Database

- At the root url of project, open conf/global.php and edit mysql database connection.
- Run following command in shell prompt:
    php cli/cli.php migrate rebuild

## Load JSON Data to DB

- This task will load existed file called data.json stored in migration/ directory.
- Run following command in shell prompt:
    php cli/cli.php migrate load

## Write JSON Data

- After export JSON data from localhost/phpmyadmin, open file migration/data.json and paste the content follow structure:
    {
        "< table_name >": [{< json_data >}]
    }
