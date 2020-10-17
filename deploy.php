<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'WebStack-Laravel');

// Project repository
set('repository', 'https://github.com.cnpmjs.org/luoweikingjj/WebStack-Laravel.git');

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);

set('keep_releases', 5);

// Hosts

host('111.231.203.189')
    ->user('deployer')
    ->port(22)
    ->identityFile('~/.ssh/deployerkey')
    //->become('www-data')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/webstack');

// Tasks

desc('Upload .env file');
task('env:upload', function() {
    upload('.env.master', '{{release_path}}/.env');
});

after('deploy:shared', 'env:upload');
after('deploy:failed', 'deploy:unlock');

before('deploy:symlink', 'artisan:migrate:fresh');
after('artisan:migrate:fresh', 'artisan:db:seed');
