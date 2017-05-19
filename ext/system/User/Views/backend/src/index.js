import Router from '@/router';

Router.addRoutes([
    {
        name: 'users',
        path: '/users',
        component: require('@User/views/Index')
    }
]);

import '@User/store/user';