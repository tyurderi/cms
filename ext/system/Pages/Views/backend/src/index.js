import Router from '@/router';
import '@Pages/store/page';

Router.addRoutes([
    {
        name: 'page',
        path: '/pages',
        component: require('@Pages/views/Index')
    }
]);