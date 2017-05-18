import Router from '@/router';

import '@PluginManager/store/plugin';

Router.addRoutes([
    {
        name: 'plugin-manager',
        path: '/pluginManager',
        component: require('@PluginManager/views/Index')
    }
]);