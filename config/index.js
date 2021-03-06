let path = require('path');

module.exports = {
    build: {
        env: require('./prod.env'),
        index: path.resolve(__dirname, '../backend/index.html'),
        assetsRoot: path.resolve(__dirname, '../backend'),
        assetsSubDirectory: 'static',
        assetsPublicPath: '/backend/',
        productionSourceMap: true,
        productionGzip: false,
        productionGzipExtensions: ['js', 'css'],
        bundleAnalyzerReport: process.env.npm_config_report
    },
    dev: {
        env: require('./dev.env'),
        port: 8303,
        autoOpenBrowser: false,
        assetsSubDirectory: 'static',
        assetsPublicPath: '/',
        proxyTable: {
            '/api': {
                target: 'http://vuex_cms.dev/',
                changeOrigin: true
            }
        },
        cssSourceMap: false
    }
};
