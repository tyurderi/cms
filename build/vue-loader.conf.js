let utils = require('./utils'),
    config = require('../config'),
    isProduction = process.env.NODE_ENV === 'production';

module.exports = {
    loaders: utils.cssLoaders({
        sourceMap: isProduction
            ? config.build.productionSourceMap
            : config.dev.cssSourceMap,
        extract: isProduction
    }),
    postcss: [
        require('autoprefixer')({
            browsers: ['last 2 versions']
        })
    ]
}
