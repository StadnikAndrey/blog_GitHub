module.exports = {
    filenameHashing: false,
    productionSourceMap: false,
    devServer: {
        proxy: {
            "/api/": {
                target: "http://blog",
                secure: false,
                changeOrigin: true
            }
        }
    },
    css: {
        extract: true
    }
};