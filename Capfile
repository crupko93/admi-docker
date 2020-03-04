set :deploy_config_path,'deployment/deploy.rb'
set :stages_root,       'deployment/deploy'
set :stage_config_path, 'deployment/deploy'

require 'rainbow/refinement'

require 'capistrano/setup'
require 'capistrano/deploy'
require 'capistrano/scm/git'

# require 'capistrano/env/v3'
# require 'capistrano/bower'

require 'capistrano/npm'
require 'capistrano/composer'
require 'capistrano/file-permissions'
require 'capistrano/laravel'

require 'slackistrano/capistrano'

require 'whenever/capistrano'

require_relative 'deployment/lib/SlackMessaging'

install_plugin Capistrano::SCM::Git

# Load custom tasks
Dir.glob('deployment/tasks/*.rake').each { |r| import r }
