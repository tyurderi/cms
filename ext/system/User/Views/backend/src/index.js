import Router from '@/router';

Router.addRoutes([
    {
        name: 'users',
        path: '/users',
        component: require('@User/views/user/Index')
    },
    {
        name: 'user-edit',
        path: '/users/edit/:id',
        component: require('@User/views/user/Form')
    },
    {
        name: 'users-groups',
        path: '/users/groups',
        component: require('@User/views/group/Index')
    },
    {
        name: 'users-groups-edit',
        path: '/users/groups/edit/:id',
        component: require('@User/views/group/Form')
    }
]);

import '@User/store/user';
import '@User/store/group';