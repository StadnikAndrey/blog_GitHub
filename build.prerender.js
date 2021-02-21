const path = require('path');
const PrerenderSPAPlugin = require('prerender-spa-plugin');
const Vue = require('vue');
const VueResource = require('vue-resource');
Vue.use(VueResource); 
 
module.exports = (api, options) => {        
    api.registerCommand('build:prerender', async (args) => {
        let staticDir = path.join(__dirname, options.outputDir);
        let outputDir = path.join(__dirname, 'prerendered');
        let routes = await Vue.http.get('http://blogonvue.zzz.com.ua/api/articles_for_admin.php')
            .then(response => response.json())
            .then(data => {
                return data.map(article => `/article/${article.id}`);                 
            })
            .catch((e) => {
                console.log(e);
            });             
        routes.unshift('/', '/about', '/contacts');
         
        api.chainWebpack(config  => {
            config.plugin('prerender').use(PrerenderSPAPlugin, [{
                staticDir,
                outputDir,
                routes,
                renderer: new PrerenderSPAPlugin.PuppeteerRenderer({
                    renderAfterDocumentEvent: 'rendered-ready'
                }),
                server: {
                    proxy: {
                        "/api": {
                            target: "http://blogonvue.zzz.com.ua",
                            secure: false,
                            changeOrigin: true
                        }
                    }
                }
            }]);
        });

        await api.service.run('build');
    });
}

module.exports.defaultModes = {
    'build:prerender': 'prodaction'
}