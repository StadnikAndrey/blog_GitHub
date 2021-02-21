import Vue from 'vue'
export default {
    namespaced: true,
    state: {
        articles: [],      
        filter: {
            page: 0,
            rubric: null,
            author:  null
        },
        getrubrics: [],
        countpages: 0,  //количество страниц     
        article:  [],
        blockRequest: false,
        allArticlesForAdmin: []               
    },
    getters: {
        articles(state) {
            return state.articles;
        },
        page(state) {
            return state.filter.page;
        },
        rubrics(state) {
            return state.getrubrics;
        },
        countpages(state) {             
            return state.countpages;
        },
        article(state){
            let article = state.article;             
            return article;
        },
        allArticlesForAdmin(state) {
            return state.allArticlesForAdmin;
        },
        blockRequest(state){
            return state.blockRequest;
        }
    },
    mutations: {         
        loadArticles(state, data) {                                                   
            state.articles = data;                                                
        },
        incrementPage(state){                                  
            if (state.filter.page < state.countpages && state.blockRequest==false) {
                state.filter.page++;                 
            }                                                                           
        },
        loadArticlesPage(state, data){
            state.articles = [...state.articles, ...data];    
            state.blockRequest = false;                     
        },
        filter(state, data){             
            for(let key in state.filter){
                if (key == Object.keys(data).join()) {
                    let k = Object.keys(data).join();
                    let value = Object.values(data).join();
                    state.filter[k] = value;  
                     state.filter.page = 0; 
                }else{
                    state.filter[key] = null;
                    state.filter.page = 0;
                }
            }
            state.filter.page = 0;                                                         
        },
        loadRubrics(state, data){             
            state.getrubrics = data;
        },         
        countpages(state, data){
            state.blockRequest = true;
            let count = Math.ceil(data/5);
            state.countpages = count; 
            state.blockRequest = false;            
        },
        article(state, data){
            state.article = data;
        },
        blockRequest(state){
            state.blockRequest = true;
        },
        allArticlesForAdmin(state, data){
            state.allArticlesForAdmin = data;
        }
    },
    actions: {         
        async incrementPage(store){ 
            store.commit('incrementPage');                        
            if (store.state.filter.page < store.state.countpages && store.state.blockRequest == false) {   
                store.commit('blockRequest');                              
                await Vue.http.get('articles.php', { params: store.state.filter, credentials: true })
                    .then(response => response.json())
                    .then(data => {
                        store.commit('loadArticlesPage', data);
                    });                               
            }                          
        },
        filter(store, obj ){
            store.commit('filter', obj);             
            Vue.http.get('articles.php', { params: store.state.filter, credentials: true })
            .then(response => response.json())
            .then(data => {
                store.commit('loadArticles', data);
            }); 
        },
        getRubrics(store){                         
            Vue.http.get('rubrics.php', { credentials: true })
            .then(response => response.json())
            .then(data => {
                store.commit('loadRubrics', data);
            });                          
        },
        countPages(store){
            Vue.http.get('countpages.php', { params: store.state.filter, credentials: true })
            .then(response => response.json())
            .then(data => {
                store.commit('countpages', data);                     
            });
        },
        article(store, obj){                   
            Vue.http.get('article.php', { params: obj, credentials: true })
            .then(response => response.json())
            .then(data => {
                store.commit('article', data);
            }).then(() => {
                // для пререндеринга
                let event = new Event('rendered-ready');
                window.document.dispatchEvent(event);
            });                 
        },
        allArticlesForAdmin(store){
            Vue.http.get('articles_for_admin.php')
            .then(response => response.json())
            .then(data => {
                store.commit('allArticlesForAdmin', data);
            }); 
        } 
    }
};