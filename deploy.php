<?php

namespace Deployer;

require 'recipe/laravel.php';

set('application', 'Meel.me');
set('repository', 'git@github.com:SjorsO/meel.git');
set('git_tty', true);
set('keep_releases', 5);

host('sjors@meel.me')->set('deploy_path', '/var/www/meel');


task('build-npm-assets', 'npm i; npm run prod');

task('clear-opcache', 'sudo service apache2 reload');


after('deploy:failed', 'deploy:unlock');


task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',

    'deploy:vendors',
    'build-npm-assets',

    'deploy:writable',

//    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:config:cache',
    'artisan:route:cache',

    'artisan:migrate',

    'deploy:symlink',

    'clear-opcache',
    'artisan:queue:restart',

    'deploy:unlock',
    'cleanup',
]);
