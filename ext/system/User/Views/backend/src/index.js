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
    },
    {
        name: 'users-permissions',
        path: '/users/permissions',
        component: require('@User/views/permission/Index')
    },
    {
        name: 'users-permissions-edit',
        path: '/users/permissions/edit/:id',
        component: require('@User/views/permission/Form')
    }
]);

import '@User/store/user';
import '@User/store/group';
import '@User/store/permission';
import '@User/store/permission/category';