namespace :assets do

    desc "Build client-side assets"
    task :build do
        on roles(:app) do
            proc { `cd app && npm run production --silent --no-progress` }.call
        end
    end

    desc "Upload client-side assets to remote"
    task :upload do
        on roles(:app) do
            fetch(:asset_paths).each do |path|
                upload! "#{Dir.pwd}/app/#{path}", "#{release_path}/public/", { :recursive => true }
            end
        end
    end

end
