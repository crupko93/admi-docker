set :stage,     :staging
set :deploy_to, '/var/www/admin.onlineapps.io'

set :branch, git_current_branch

set :laravel_dotenv_file, './app/.env.staging'

set :ssh_options, {
    :user => 'ubuntu',
    :forward_agent => true,
    :auth_methods => ['publickey'],
    :keys => ['../keys/staging.pem']
}

set :composer_install_flags, '--dev --no-interaction --quiet --optimize-autoloader'

server 'admin.onlineapps.io', roles: %w{app db web}

SSHKit.config.command_map[:composer] = "#{shared_path.join('composer.phar')}"

namespace :deploy do
    # after :publishing, 'laravel:key:generate'
end