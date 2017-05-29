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
    },
    {
        name: 'users-groups',
        path: '/users/groups',
        component: require('@User/views/Groups')
    }
]);

import '@User/store/user';
import '@User/store/group';