# Meel.me
Email yourself - [meel.me](https://meel.me)

## Laravel
[Compare with current laravel/laravel version](https://github.com/laravel/laravel/compare/70532dd8ae1eb4cf27c66c92d8bc6fa4ed2c7a18...master)

## Install
Required extensions:
- [gmp](http://php.net/manual/en/book.gmp.php) - for obfuscating ids in routes

Installation:
```bash
cp .env.example .env

# Fill in the .env file

composer install

php artisan key:generate

php artisan passport:keys
 
php artisan migrate:fresh --seed
 
npm install && npm run dev
```

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
