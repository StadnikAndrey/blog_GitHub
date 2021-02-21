import Vue from 'vue';

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import { store } from './store';

import Home from './components/page/Home.vue' 
import Contacts from './components/page/Contacts.vue'
import Ab from './components/page/Ab.vue'
import Entranсe from './components/page/Entranсe.vue'
import Registration from './components/page/Registration.vue'
import Agreement from './components/page/Agreement.vue'
import Article from './components/page/Article.vue'
import E404 from './components/page/E404.vue'
import Cabinet from './components/page/cabinet/Cabinet.vue'
import UserHome from './components/page/cabinet/UserHome.vue'
import InsertArticle from './components/page/cabinet/InsertArticle.vue'
import UpdateArticle from './components/page/cabinet/UpdateArticle.vue'
import ReferenceArticle from './components/page/cabinet/ReferenceArticle.vue'

import Adminpanel from './components/page/adminpanel/Adminpanel.vue'
import AdminUser from './components/page/adminpanel/AdminUser.vue'
import AdminArticles from './components/page/adminpanel/AdminArticles.vue'

import AdminRubrics from './components/page/adminpanel/AdminRubrics.vue'
import AdminRubricsMain from './components/page/adminpanel/AdminRubricsMain.vue'
import AdminRubricsInsert from './components/page/adminpanel/AdminRubricsInsert.vue'
import AdminRubricsUpdate from './components/page/adminpanel/AdminRubricsUpdate.vue'

const routes = [     
    {   name: 'home',
        path: '/home',
        component: Home,
        async beforeEnter(to, from, next) {
            // для пререндеринга
            let event = new Event('rendered-ready');
            window.document.dispatchEvent(event);
            next();
        }                 
    },
    {
        path: '/:filter/:name/:id',
        component: Home                 
    },
    {
        path: '/article/:id',
        component: Article                 
    },
    {
        path: '/about',
        component: Ab,
        async beforeEnter(to, from, next) {  
            // для пререндеринга
            let event = new Event('rendered-ready');
            window.document.dispatchEvent(event);
            next();
        }
    },
    {
        path: '/contacts',
        component: Contacts,
        async beforeEnter(to, from, next) {  
            // для пререндеринга
            let event = new Event('rendered-ready');
            window.document.dispatchEvent(event);
            next();
        }
    },
    {
        name: 'entrance',
        path: '/entrance',
        component: Entranсe,
        async beforeEnter(to, from, next) {
            await store.getters['user/ready'];

            if (store.getters['user/user']) {
                next({ path: '/cabinet' });
            }
            else {
                next();
            }
        }
    },
    {
        name: 'exit',
        path: '/exit',
        component: Home,
        beforeEnter(to, from, next) {
            store.dispatch('user/cleanUser');
            store.dispatch('user/logout');             
            next('/home');             
        }
    },
    {
        path: '/registration',
        component: Registration,
        async beforeEnter(to, from, next) {
            await store.getters['user/ready'];
            setTimeout(()=>{
                if (store.getters['user/user']) {
                    next({ path: '/cabinet' });
                }
                else {
                    next();
                }
            },500);             
        }
    },
    {
        path: '/agreement',
        component: Agreement         
    },
    {   
        name: 'E404',
        path: '/E404',
        component: E404         
    },
    {         
        path: '/cabinet/',
        component: Cabinet,
        meta: { requiresAuth: true },
        children: [             
            { name: 'userHome', path: '', component: UserHome },
            { name: 'insertArticle', path: 'insert', component: InsertArticle },
            { name: 'updateArticle', path: 'article/update/:id_article', component: UpdateArticle },            
            { path: 'reference', component: ReferenceArticle }           
        ]        
    },
    {         
        path: '/adminpanel/',
        component: Adminpanel,
        meta: {  admin: true },
        children: [
            { path: '', redirect: 'articles'},
            { path: 'users', component: AdminUser },
            { path: 'articles', component: AdminArticles },
            { path: '/adminpanel/rubrics',
              component: AdminRubrics,
              children: [
                  { path: '', redirect: 'adminpanel/rubrics/main' },
                  { path: 'adminpanel/rubrics/main', component: AdminRubricsMain },
                  { path: 'adminpanel/rubrics/insert', component: AdminRubricsInsert },
                  { path: 'adminpanel/rubrics/update/:id', component: AdminRubricsUpdate }, 
              ]
            }
        ]
    },
    {
        path: '/',
        redirect: '/home'
    },
    {
        path: '*',
        redirect: '/home'
    },
];

const router = new VueRouter({
    routes,
    mode: 'history',
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }     
}); 

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        await store.getters['user/ready'];  
        setTimeout(() => { //чтобы дождаться загрузки user после рефреша
            if (!store.getters['user/user']) {
                next({ name: 'entrance' });
            } else {
                next();
            } 
        }, 500);             
                         
    } else {
        next(); 
    }
     
    if (to.matched.some(record => record.meta.admin)) {
            await store.getters['user/ready'];
            setTimeout(() => {  
                if (store.getters['user/user'] && store.getters['user/user'].admin == 1) {
                    next();
                } else {
                    next({ name: 'entrance' });
                } 
            }, 500);             
         
    } else {
        next();
    }
})
export default router;