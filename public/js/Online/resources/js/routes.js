import home from "./pages/home";
import sites from "./pages/sites";
import news from "./pages/news";
import about from "./pages/about";
import login from "./pages/login";

export let routes = [{
        path: '/login',
        component: login,
    },
    {
        path: '/',
        component: home,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/sites',
        component: sites,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/news',
        component: news,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/about',
        component: about,
        meta: {
            requiresAuth: true
        },
    },
];
