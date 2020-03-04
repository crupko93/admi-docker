if defined?(Slackistrano::Messaging)
    module Slackistrano
        class SlackMessaging < Messaging::Base

            def deployer
                '*' + super + '*'
            end

            def branch
                branch = super
                '`' + branch + '` (`' + git_short_remote_hash(branch) + '`)'
            end

            def application
                '`' + super + '`'
            end

            def stage
                '*' + super.to_s + '*' + (super.to_s === 'production' ? ' :warning:' : '')
            end

        end
    end
end

