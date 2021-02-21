import * as Tokens from '@/utils/tokens';

let autoLoginResolver;
let autoLoginPromise = new Promise(resolve => { autoLoginResolver = resolve });
import Vue from 'vue'
export default {
    namespaced: true,
    state: {
        user: null,
        articlesUser: [],
        oneArticle: [],
        rubrics: [],
        oneRubric: [],
        users: []
    },
    getters: {
        user: (state) => state.user,
        ready: () => autoLoginPromise,
        articlesUser: (state) => state.articlesUser,
        oneArticle: (state) =>state.oneArticle,
        rubrics: (state) => state.rubrics,
        oneRubric: (state) => state.oneRubric,
        users: (state) => state.users         
    },
    mutations: {
        setUser(state, user) {
            state.user = user;                          
        },
        setArticlesUser(state, articles){
            state.articlesUser = articles;
        },
        setOneArticle(state, data){
            state.oneArticle = data;
        },
        deleteArticle(state, id){
            let index = state.articlesUser.findIndex(item => item.id == id);
            state.articlesUser.splice(index, 1);             
        },
        rubrics(state, data) {
            state.rubrics = data;
        },
        oneRubric(state, data) {
            state.oneRubric = data;
        },
        users(state, data) {
            state.users = data;
        }
    },
    actions: {
        async login({ commit }, formdata) {             
            let data = await Vue.http.post('entrance.php', formdata, {credentials: true})
                .then(response => response.json())
                .then(data => {                     
                    return data;
                }); 
            if (data.res) {
                Tokens.setTokens(data.accessToken);
                let user = Tokens.getJWTPayload(data.accessToken);
                commit('setUser', user);
            }            
            return data;
        },
        async autoLogin({ commit, dispatch }) {              
            let data = await Vue.http.get('check.php', { credentials: true })
                .then(response => response.json())
                .then(data => {
                    return data;
                })
                .catch((err)=>{
                    console.log(err.status); //подавление ошибки при статусе 401                       
                });    
                          
            if (data && data.user) {
                let user = Tokens.getJWTPayload(Tokens.getAccessToken());                
                commit('setUser', user);                                                                  
            }
            else {                                                   
                dispatch('cleanUser');                                                     
            }

            autoLoginResolver();
        },
        cleanUser({ commit }) {
            commit('setUser', null);
            Tokens.cleanTokensData();                         
        },
        async logout() {            
            await Vue.http.post('logout.php');             
        },
        setUser({ commit }, user){   //вызывается при удачном рефреше из main.js       
            commit('setUser', user);                                     
        },
        articlesUser({commit, state}){
            Vue.http.get('articles_user.php', { params: {id_user: state.user.id}, credentials: true })
                .then(response => response.json())
                .then(data => {
                    commit('setArticlesUser', data);
                });
        },
        oneArticle({commit, state}, id){
            Vue.http.get('article_user_for_update.php', { params: { id_user: state.user.id, id_article: id }, credentials: true })
            .then(response => response.json())
            .then(data => {
                commit('setOneArticle', data);
            });
        },
        deleteArticle({commit}, id){
            commit('deleteArticle', id);
        },
        rubrics({commit}) {
            Vue.http.get('rubrics_all.php')
                .then(response => response.json())
                .then(data => {
                    commit('rubrics', data);
                });
        },
        oneRubric({commit}, data) {
            Vue.http.get('rubric_one.php', { params: { id: data}})
                .then(response => response.json())
                .then(data => {
                    commit('oneRubric', data);
                });
        },
        users({commit}, data) {
            Vue.http.get('users.php', { params: data })
            .then(response => response.json())
            .then(data => {
                commit('users', data);
            });
        }         
    }
}