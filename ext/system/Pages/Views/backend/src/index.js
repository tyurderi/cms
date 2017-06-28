import Router from '@/router';

Router.addRoutes([
    {
        name: 'page',
        path: '/pages',
        component: require('@Pages/views/Index')
    }
]);