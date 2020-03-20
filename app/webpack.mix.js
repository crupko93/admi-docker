const mix                 = require('laravel-mix');
const fs                  = require('fs');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/styles/app.sass', 'public/css');


mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias     : {
            '~'    : path.join(__dirname, './resources/js'),
            '$comp': path.join(__dirname, './resources/js/components')
        }
    },
    plugins: [
        new VuetifyLoaderPlugin({allChunks: true})
    ],
    module : {
        rules: [
            {
                test   : /\.js?$/,
                exclude: /(bower_components)/,
                use    : [
                    {
                        loader : 'babel-loader',
                        options: mix.config.babel()
                    }
                ]
            },
            {
                test  : /\.styl$/,
                loader: ['style-loader', 'css-loader', 'stylus-loader']
            }
        ]
    },

    output: {
        chunkFilename: 'js/chunk/[name]/[chunkhash].js'
    }
});

// larave-mix bug workaround;
// see: https://laracasts.com/discuss/channels/elixir/mix-npm-run-hot-browser-crash-uncaught-typeerror-cannot-read-property-call-of-undefined
if (Mix.isWatching()) {
    let fires      = 0;
    let tempString = '\n//temp';
    let filename   = 'resources/styles/app.sass';
    mix.then((stats) => {
        if (fires === 0) {
            fs.appendFile(filename, tempString, function (err) {
                if (err) throw err;
                console.log('Added temp string to force recompiling');
            });
            fs.readFile(filename, 'utf8', function (err, data) {
                if (err) throw err;
                let newData = data.replace(tempString, '');
                fs.writeFile(filename, newData, function (err) {
                    if (err) throw err;
                    console.log('Removed temp string');
                });
            });
        }
        fires++;
    });
}