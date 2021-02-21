module.exports = {
    filenameHashing: false,
    productionSourceMap: false,
    devServer: {
        proxy: {
            "/api/": {
                target: "http://blogonvue.zzz.com.ua",
                secure: false,
                changeOrigin: true
            }
        }
    },
    css: {
        extract: true
    }
};