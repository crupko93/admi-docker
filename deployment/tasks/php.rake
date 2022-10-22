namespace :php do

    desc "Restart PHP FPM daemon"
    task :restart do
        on roles(:app) do
            execute 'sudo service php-fpm restart'
        end
    end

end

namespace :opcache do

    desc "Do a global PHP opcache invalidation"
    task :reset do
        on roles(:app) do
            execute "php -r 'opcache_reset();'"
        end
    end

end
