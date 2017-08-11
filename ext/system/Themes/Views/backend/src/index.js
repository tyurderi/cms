import Router from '@/router';

Router.addRoutes([
    {
        name: 'themes',
        path: '/config/themes',
        component: require('@Themes/views/Index')
    }
]);