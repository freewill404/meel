# Meel.me
Email yourself - [meel.me](https://meel.me)

## Laravel
[Compare with current laravel/laravel version](https://github.com/laravel/laravel/compare/7028b17ed8bf35ee2f1269c0f9c985b411cb4469...master)

## Install
Required extensions:
- [gmp](http://php.net/manual/en/book.gmp.php) - for obfuscating ids in routes

Installation:
```bash
cp .env.example .env # fill in the arrows in the .env file

composer install

php artisan key:generate

php artisan passport:keys
 
php artisan migrate (--seed)
 
npm install && npm run dev
```

## License

This project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
