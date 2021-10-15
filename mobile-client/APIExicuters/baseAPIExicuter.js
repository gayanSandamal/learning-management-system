import { getUsername, getToken } from './../APIExicuters/secureStore'
export default {
    async get(url, token, sucessCallback, errorCallback) {
        await fetch(url, {
            method: "GET",
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
        })
        .then((response) => {
            if (!response.ok) {
                throw response.json()
            }
            return response.json()
        })
        .then( json => {
            sucessCallback(json)
        })
        .catch(err => {
            err.then(re => {
                errorCallback(re)
            }).catch(er => {
                errorCallback(er)
            })
        })
    },

    async tokenPost(url, sucessCallback, errorCallback) {
        const usernamePromise = await getUsername()
        const tokenPromise = await getToken()
        Promise.all([usernamePromise, tokenPromise]).then(() => {
            const object = {
                username: usernamePromise,
                token: tokenPromise
            }
            fetch(url, {
                method: "POST",
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(object)
            })
            .then((response) => {
                if (!response.ok) {
                    throw response.json()
                }
                return response.json()
            })
            .then(json => {
                sucessCallback(json)
            })
            .catch(err => {
                err.then(re => {
                    errorCallback(re)
                }).catch(er => {
                    errorCallback(er)
                })
            })
        })
            
    },

    async post(url, data, sucessCallback, errorCallback) {
        await fetch(url, {
            method: "POST",
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then((response) => {
            if (!response.ok) {
                throw response.json()
            }
            return response.json()})

        .then( json => {
            sucessCallback(json)
        })
        .catch(err => {
            err.then(re => {
                errorCallback(re)
            }).catch(er => {
                errorCallback(er)
            })
        })
    }
}