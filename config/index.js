var path = require('path')

module.exports = {
  build: {
    env: require('./prod.env'),
    index: path.resolve(__dirname, '../backend/index.html'),
    assetsRoot: path.resolve(__dirname, '../backend'),
    assetsSubDirectory: 'static',
    assetsPublicPath: '/js/vue/backend/',
    productionSourceMap: true,
    productionGzip: false,
    productionGzipExtensions: ['js', 'css'],
    bundleAnalyzerReport: process.env.npm_config_report
  },
  dev: {
    env: require('./dev.env'),
    port: 8080,
    autoOpenBrowser: false,
    assetsSubDirectory: 'static',
    assetsPublicPath: '/',
    proxyTable: {
        '/api': {
            target: 'http://local.dev/js/vue/',
            changeOrigin: true
        }
    },
    cssSourceMap: false
  }
}
