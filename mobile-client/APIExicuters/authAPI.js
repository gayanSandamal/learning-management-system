import baseAPIExicuter from './baseAPIExicuter'

export default {
    async login (obj, sucessCallback, errorCallback) {
        const url = 'https://qa.akurata.lk/web-api/api/login'
        return await baseAPIExicuter.post(url, obj, response => {
            sucessCallback(response)
        }, error => {
            errorCallback(error)
        })
    },

    async passwordReset (email, sucessCallback, errorCallback) {
        const obj = {
            email: email,
        }
        const url = 'https://qa.akurata.lk/web-api/api/reset-password'
        return await baseAPIExicuter.post(url, obj, sucessCallback, errorCallback)
    },

    async getUser (sucessCallback, errorCallback) {
        const url = 'https://qa.akurata.lk/web-api/api/get-account'
        return await baseAPIExicuter.tokenPost(url, sucessCallback, errorCallback)
    }
}