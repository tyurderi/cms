function resolve(dir) { return path.join(__dirname, '..', dir) }

let path            = require('path'),
    utils           = require('./utils'),
    config          = require('../config'),
    vueLoaderConfig = require('./vue-loader.conf'),
    php_console     = require('./console');
    configuration   = {
        entry: {
            app: './themes/backend/default/src/main.js'
        },
        output: {
            path: config.build.assetsRoot,
            filename: '[name].js',
            publicPath: process.env.NODE_ENV === 'production'
                ? config.build.assetsPublicPath
                : config.dev.assetsPublicPath
        },
        resolve: {
            extensions: ['.js', '.vue', '.json'],
            alias: {
                'vue$': 'vue/dist/vue.common.js',
                '@': resolve('themes/backend/default/src'),
            }
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader',
                    options: vueLoaderConfig
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader',
                    include: [resolve('themes/backend/default/src'), resolve('test')]
                },
                {
                    test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                    loader: 'url-loader',
                    query: {
                        limit: 10000,
                        name: utils.assetsPath('img/[name].[hash:7].[ext]')
                    }
                },
                {
                    test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
                    loader: 'url-loader',
                    query: {
                        limit: 10000,
                        name: utils.assetsPath('fonts/[name].[hash:7].[ext]')
                    }
                }
            ]
        }
    };

{
    console.log('Generating plugins.js and plugins.json...');
    php_console('vue:collect');

    let plugins = require(resolve('themes/backend/default/src/plugins.json'));

    for (let key in plugins.alias)
    {
        if (!plugins.alias.hasOwnProperty(key))
        {
            continue;
        }

        configuration.resolve.alias[key] = resolve(plugins.alias[key].relativePath);

        console.log('Registered custom alias: %s to %s', key, configuration.resolve.alias[key]);
    }
}

module.exports = configuration;