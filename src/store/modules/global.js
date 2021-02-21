export default {
    namespaced: true,
    state: {
        app_wrap_overflow : '',
        domain: 'BlogOnVue'
    },
    getters: {
        app_wrap_overflow(state) {
            return state.app_wrap_overflow;
        },
        domain(state){
            return state.domain;
        }
    },
    mutations: {
        app_wrap_overflow(state, data){
            state.app_wrap_overflow = data;
        }
    },
    actions: {
        app_wrap_overflow(store, data){
            store.commit('app_wrap_overflow', data);
        }
    }
};