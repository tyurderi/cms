require('./check-versions')();

let config = require('../config');

if (!process.env.NODE_ENV)
{
    process.env.NODE_ENV = JSON.parse(config.dev.env.NODE_ENV)
}

let opn             = require('opn'),
    path            = require('path'),
    express         = require('express'),
    webpack         = require('webpack'),
    proxyMiddleware = require('http-proxy-middleware'),
    webpackConfig   = require('./webpack.dev.conf');

let port            = process.env.PORT || config.dev.port,
    autoOpenBrowser = !!config.dev.autoOpenBrowser,
    proxyTable      = config.dev.proxyTable;

let app = express(),
    compiler = webpack(webpackConfig);

let devMiddleware = require('webpack-dev-middleware')(compiler, {
    publicPath: webpackConfig.output.publicPath,
    quiet: true,
    stats: {
        colors: true,
        chunks: false
    },
    watchOptions: {
        aggregateTimeout: 300,
        poll: 1000
    }
});

let hotMiddleware = require('webpack-hot-middleware')(compiler, {
    log: () => {}
});

// force page reload when html-webpack-plugin template changes
compiler.plugin('compilation', function(compilation) {
    compilation.plugin('html-webpack-plugin-after-emit', function(data, cb) {
        hotMiddleware.publish({ action: 'reload' });
        cb()
    })
});

Object.keys(proxyTable).forEach(function(context) {
    let options = proxyTable[context];

    if (typeof options === 'string')
    {
        options = { target: options }
    }

    app.use(proxyMiddleware(options.filter || context, options))
});

app.use(require('connect-history-api-fallback')());
app.use(devMiddleware);
app.use(hotMiddleware);

let staticPath = path.posix.join(config.dev.assetsPublicPath, config.dev.assetsSubDirectory)
app.use(staticPath, express.static('./themes/backend/default/static'))

let uri = 'http://localhost:' + port

devMiddleware.waitUntilValid(function () {
    console.log('> Listening at ' + uri + '\n')
});

module.exports = app.listen(port, function (err) {
    if (err)
    {
        console.log(err);
        return
    }

    if (autoOpenBrowser && process.env.NODE_ENV !== 'testing')
    {
        opn(uri)
    }
});
