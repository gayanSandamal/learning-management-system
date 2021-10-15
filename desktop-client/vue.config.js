var path = require('path')
module.exports = {
  pluginOptions: {
    electronBuilder: {
      nodeIntegration: true
    }
  },
  devServer: {
    contentBase: path.join(__dirname, 'dist'),
    compress: true,
    port: 8080,
    proxy: {
      '/api': {
        target: process.env.NODE_ENV === 'production' ? process.env.VUE_APP_MAIN_URL : 'http://localhost/',
        ws: true,
        changeOrigin: false
      }
    }
  }
}