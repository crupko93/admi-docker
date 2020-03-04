namespace :laravel do

    # :key
    namespace :key do

        desc "Generate encryption key"
        task :generate do
            on roles fetch(:laravel_roles) do
                within release_path do
                    invoke "laravel:artisan", "key:generate"
                end
            end
        end

    end
    # END :key

    # :maintenance
    namespace :maintenance do

        desc "Take the application down for maintenance"
        task :down do
            on roles fetch(:laravel_roles) do
                within release_path do
                    invoke "laravel:artisan", "down"
                end
            end
        end

        desc "Bring the application up from maintenance"
        task :up do
            on roles fetch(:laravel_roles) do
                within release_path do
                    invoke "laravel:artisan", "up"
                end
            end
        end

    end
    # END :maintenance

    # :worker
    namespace :worker do

        desc "Restart queue worker using Supervisor"
        task :restart do
            on roles fetch(:laravel_roles) do
                execute 'sudo supervisorctl restart laravel-worker:*'
            end
        end

        desc "Kill currently running queue worker processes"
        task :kill do
            on roles fetch(:laravel_roles) do
                execute "sudo pkill -fe '^php /var/www/.*/current/artisan queue:work'"
            end
        end

    end
    # END :worker

end
