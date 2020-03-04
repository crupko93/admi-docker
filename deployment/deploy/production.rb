set :stage,     :production
set :deploy_to, '/var/www/change-this.com'

set :branch, 'master'

set :laravel_dotenv_file, './app/.env.production'

set :ssh_options, {
    :user => 'ubuntu',
    :forward_agent => true,
    :auth_methods => ['publickey'],
    :keys => ['../keys/production.pem']
}

set :composer_install_flags, '--no-dev --no-interaction --quiet --optimize-autoloader'

server 'change-this.com', roles: %w{app db web}

SSHKit.config.command_map[:composer] = "#{shared_path.join('composer.phar')}"

namespace :deploy do
    # after :publishing, 'laravel:key:generate'
end
