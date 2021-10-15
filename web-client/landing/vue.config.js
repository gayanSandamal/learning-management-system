var path = require('path')
module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? process.env.VUE_APP_PUBLIC_PATH_CONFIG : '/',
  chainWebpack: config => {
    config
      .plugin('html')
      .tap(args => {
        args[0].title = "Akurata.lk";
        return args;
      })
  },
  devServer: {
    contentBase: path.join(__dirname, 'dist'),
    compress: true,
    port: 8081,
    proxy: {
      '/api': {
        target: process.env.NODE_ENV === 'production' ? process.env.VUE_APP_MAIN_URL : 'http://localhost/',
        ws: true,
        changeOrigin: true
      }
    }
  },
  css: {
    requireModuleExtension: false,
    sourceMap: true,
    loaderOptions: {
      // pass options to sass-loader
      sass: {
        // @/ is an alias to src/
        // so this assumes you have a file named `src/variables.scss`
        // data: `@import "@/main.scss";`
      },
      // pass Less.js Options to less-loader
      less: {
        // http://lesscss.org/usage/#less-options-strict-units `Global Variables`
        // `primary` is global variables fields name
        globalVars: {
          primary: '#fff'
        }
      }
    }
  }
};
