let merge   = require('webpack-merge');
let prodEnv = require('./prod.env');

module.exports = merge(prodEnv, {
    NODE_ENV: '"development"',
    BASE_PATH: '"http://192.168.33.11:8080"'
});
