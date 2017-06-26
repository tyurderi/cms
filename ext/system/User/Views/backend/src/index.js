import Router from '@/router';

Router.addRoutes([
    {
        name: 'users',
        path: '/users',
        component: require('@User/views/user/Index')
    },
    {
        name: 'user-create',
        path: '/users/create',
        component: require('@User/views/user/Form')
    },
    {
        name: 'user-edit',
        path: '/users/edit/:id',
        component: require('@User/views/user/Form')
    },
    {
        name: 'user-group',
        path: '/users/groups',
        component: require('@User/views/group/Index')
    },
    {
        name: 'user-group-create',
        path: '/users/groups/create',
        component: require('@User/views/group/Form')
    },
    {
        name: 'user-group-edit',
        path: '/users/groups/edit/:id',
        component: require('@User/views/group/Form')
    },
    {
        name: 'user-permission',
        path: '/users/permissions',
        component: require('@User/views/permission/Index')
    },
    {
        name: 'user-permission-create',
        path: '/users/permissions/create',
        component: require('@User/views/permission/Form')
    },
    {
        name: 'user-permission-edit',
        path: '/users/permissions/edit/:id',
        component: require('@User/views/permission/Form')
    },
    {
        name: 'user-permission-category',
        path: '/users/permissions/categories',
        component: require('@User/views/permission/category/Index')
    },
    {
        name: 'user-permission-category-create',
        path: '/users/permissions/categories/create',
        component: require('@User/views/permission/category/Form')
    },
    {
        name: 'user-permission-category-edit',
        path: '/users/permissions/categories/edit/:id',
        component: require('@User/views/permission/category/Form')
    }
]);

import '@User/store/user';
import '@User/store/group';
import '@User/store/permission';
import '@User/store/permission/category';