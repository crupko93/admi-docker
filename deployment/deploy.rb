using Rainbow
lock '3.11.2'

set :application,   'admin-app'
set :repo_url,      'git@github.com:FLYGOPROJECT/admin-app.git'
set :repo_tree,     'app'
set :keep_releases, 3

set :user, 'centos'

set :npm_target_path, -> { fetch(:release_path) }

set :laravel_roles, :all
set :laravel_artisan_flags, "--env=#{fetch(:stage)}"
set :laravel_migration_roles, :db
set :laravel_migration_artisan_flags, "--force --env=#{fetch(:stage)}"
set :laravel_version, 6.0
set :laravel_upload_dotenv_file_on_deploy, true
set :laravel_server_user, 'www-data'
set :laravel_ensure_linked_dirs_exist, true
set :laravel_set_linked_dirs, true
set :laravel_5_linked_dirs, [
    'public/storage',
    'storage/logs',
    'storage/passport'
]
set :laravel_5_acl_paths, [
    'bootstrap/cache',
    'public/storage',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs'
]

set :asset_paths, [
    'public/mix-manifest.json',
    'public/css',
    'public/js'
]

# Whenever v 0.10.*
# set :whenever_load_file, -> { "#{fetch(:release_path)}/schedule.rb" }
# set :whenever_command, 'whenever'

# Whenever v 0.9.*
set :whenever_environment, -> { fetch(:stage) }
set :whenever_command, -> {
    "whenever --update-crontab #{fetch(:application)} -f #{fetch(:release_path)}/schedule.rb --set environment=#{fetch(:stage)} --roles=app,db,web"
}

set :slackistrano, {
    klass: Slackistrano::SlackMessaging,
    channel: '#deployment',
    webhook: 'https://hooks.slack.com/services/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',
    username: 'Capistrano',
    icon_url: 'https://pbs.twimg.com/profile_images/378800000067686459/5da4e1d78e930197cb7dc002ceafdfda.png'
}

def git_current_branch
    proc { `git rev-parse --abbrev-ref HEAD`.chomp }.call
end
def git_short_remote_hash(branch)
    proc { `git rev-parse --short origin/#{branch}`.chomp }.call
end
def git_has_unpushed_refs
    !proc { `git status --porcelain` }.call.empty?
end

Rake::Task['deploy:reverted'].prerequisites.delete('composer:install')

namespace :deploy do
    # Execute safety checks before starting actual deploys
    before :starting, :safety_checks do
        # [!] For production, ensure current branch is `master`
        if "#{fetch(:stage)}" === 'production' && git_current_branch != 'master'
            puts "\n  " + 'Please switch to `master` branch before deploying to production!'.yellow.underline + "\n\n"
            exit
        end

        # [!] Ensure no unpushed refs exist locally
        if git_has_unpushed_refs
            puts "\n  " + 'Local branch contains unpushed refs. Please push before deploying!'.yellow.underline + "\n\n"
            exit
        end
    end
    before :starting, 'assets:build'

    after :starting, :show_branch do
        # Set terminal coloring based on branch
        if git_current_branch === 'master'
            puts "\nDeploying " + " #{fetch :branch} ".white.bright.bg(:red) + "\n\n"
        else
            puts "\nDeploying " + " #{fetch :branch} ".black.bright.bg(:yellow) + "\n\n"
        end
    end

    after :starting, 'composer:install_executable'

    before :publishing, 'assets:upload'
    after :publishing, 'laravel:migrate'

    # after :finished, 'opcache:reset'
    after :finished, 'php:restart'
    after :finished, 'laravel:worker:restart'
end
