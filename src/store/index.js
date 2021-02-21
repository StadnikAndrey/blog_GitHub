import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import global from './modules/global'
import articles from './modules/articles'
import user from './modules/user' 

export const store = new Vuex.Store({
    modules: {
        global,
        articles,
        user         
    },
    strict: process.env.NODE_ENV !== 'production'
});