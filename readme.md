## Installation
1. Clone a repository
```
git clone https://github.com/nybbom/url-shortener
```
2. Download composer dependencies
```
cd url-shortener
composer install
```
3. Edit .env.example file and rename it to .env
    1. [Database configuration](https://laravel.com/docs/5.3/database#introduction)
4. Generate an application key
```
php artisan key:generate
```
5. Migrate a database
```
php artisan migrate
```


## Features
- Custom alias
- Local history of shortens (saved in session)
- Statistics of each shorten with filters

## Technologies
- [Laravel 5.3](https://laravel.com/docs/5.3)
- [bulma.io](bulma.io)
- [jQuery](https://code.jquery.com/)
- [jQuery UI datepicker](http://jqueryui.com/datepicker/)
