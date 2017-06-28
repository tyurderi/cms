import Router from '@/router';

Router.addRoutes([
    {
        name: 'config',
        path: '/config',
        component: require('@Config/views/Index')
    },
    {
        name: 'config-sites',
        path: '/config/sites',
        component: require('@Config/views/site/Index')
    },
    {
        name: 'config-site-create',
        path: '/config/sites/reate',
        component: require('@Config/views/site/Form')
    },
    {
        name: 'config-site-edit',
        path: '/config/sites/edit/:id',
        component: require('@Config/views/site/Form')
    }
]);

import '@Config/store/site';