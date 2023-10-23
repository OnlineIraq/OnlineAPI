import home from "./pages/home";
import sites from "./pages/sites";
import news from "./pages/news";
import packages from "./pages/packages";
import about from "./pages/about";
import complains from "./pages/complains";
import banners from "./pages/banners";
import login from "./pages/login";
import products from "./pages/products";
import podcasts from "./pages/podcasts";

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
        path: '/packages',
        component: packages,
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
    {
        path: '/complains',
        component: complains,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/banners',
        component: banners,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/products',
        component: products,
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/podcasts',
        component: podcasts,
        meta: {
            requiresAuth: true
        },
    },
];
