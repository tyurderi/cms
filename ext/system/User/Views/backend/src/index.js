import Router from '@/router';

Router.addRoutes([
    {
        name: 'users',
        path: '/users',
        component: require('@User/views/Index')
    },
    {
        name: 'user-edit',
        path: '/users/edit/:id',
        component: require('@User/views/Form')
    }
]);

import '@User/store/user';