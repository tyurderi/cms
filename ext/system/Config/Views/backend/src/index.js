import Router from '@/router';

Router.addRoutes([
    {
        name: 'config',
        path: '/config',
        component: require('@Config/views/Index')
    },
    {
        name: 'config-domain',
        path: '/config/domains',
        component: require('@Config/views/domain/Index')
    },
    {
        name: 'config-domain-create',
        path: '/config/domains/reate',
        component: require('@Config/views/domain/Form')
    },
    {
        name: 'config-domain-edit',
        path: '/config/domains/edit/:id',
        component: require('@Config/views/domain/Form')
    }
]);

import '@Config/store/domain';