export default {
    state: {
        token: localStorage.getItem('access_token') ? localStorage.getItem('access_token') : null,
        lang: localStorage.getItem('lang') ? localStorage.getItem('lang') : 'ku',
        sidebarCollapse: true,
    },
    getters: {
        getLang(state) {
            return state.lang;
        },
        getLoggedIn(state) {
            return state.loggedIn
        },
        getSidebarCollapse(state) {
            return state.sidebarCollapse;
        },
        loggedIn(state) {
            return state.loggedIn;
        },
    },
    mutations: {
        getSidebarCollapse(state) {
            state.sidebarCollapse = !state.sidebarCollapse;
        },
        showSlidebar(state) {
            state.sidebarCollapse = true;
        },
        hideSlidebar(state) {
            state.sidebarCollapse = false;
        },
        setLang(state, locale) {
            state.lang = locale;
        },
    },
    actions: {
        login(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login', {
                            email: credentials.email,
                            password: credentials.password,
                        })
                        .then(response => {
                            localStorage.setItem("loggedIn", true);
                            resolve(response);
                        })
                        .catch(error => {
                            reject(error.response.data.errors);
                        });
                });
            });
        },
        logout() {
            return new Promise((resolve, reject) => {
                axios.get('/api/logout')
                    .then(response => {
                        localStorage.removeItem("loggedIn");
                        resolve(true)
                    })
            })
        },
        toggleSlidebar(context) {
            context.commit('getSidebarCollapse');
        },
        showSlidebar(context) {
            context.commit('showSlidebar');
        },
        hideSlidebar(context) {
            context.commit('hideSlidebar');
        },
        changeLang(context, locale) {
            localStorage.setItem('lang', locale);
            context.commit('setLang', locale);
        },
    },
}