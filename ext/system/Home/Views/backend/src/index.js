import Router from '@/router';

Router.addRoutes([
    {
        name: 'home',
        path: '/',
        component: require('@Home/views/Index')
    }
]);