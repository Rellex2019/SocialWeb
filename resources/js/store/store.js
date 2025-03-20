
const authStore = {
    namespaced: true,
    state: {
        isAuthenticated: false,
        user: null,
    },
    mutations: {
        setUser(state, user) {
            state.isAuthenticated = true;
            state.user = user;
            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('isAuthenticated', true);
        },
        logout(state) {
            state.isAuthenticated = false;
            state.user = null;

            localStorage.removeItem('user');
            localStorage.removeItem('isAuthenticated');
        },

        initializeStore(state) {
            const user = localStorage.getItem('user');
            const isAuthenticated = localStorage.getItem('isAuthenticated');

            if (user) {
                state.user = JSON.parse(user);
                state.isAuthenticated = isAuthenticated === 'true';
            }
        }
    },
    actions: {
    },
    getters: {
        isAuthenticated: (state) => state.isAuthenticated,
        user: (state) => state.user,
    },
};


export default authStore;
