
/**
 * login
 * @param [FormData] email
 * @param [FormData] password
 * @return [boolean]
 */

export function login(data){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/login", {
            method: 'POST', 
            headers: {
                'Accept' : 'application/json'
            },
            body: data
        }).then( response => {
            resolve(response.json())
        })
    })
}

/**
 * register
 * @param [FormData] email
 * @param [FormData] password
 * @return [boolean]
 */

export function signup(data){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/signup", {
            method: 'POST', 
            headers: {
                'Accept' : 'application/json'
            },
            body: data
        }).then( response => resolve(response.json()))
    })
}

/**
 * get all urls photo
 * @param void
 * @return [array]
 */

export function getAllUrlFetch(){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/list/blobs", {
            method: 'GET', 
            headers: {
                'Accept' : 'application/json',
                "Authorization": "Bearer " + sessionStorage.getItem('access_token')
            }
        }).then( response => resolve(response.json()))
    })
}

/**
 * logout
 * @param void
 * @return void
 * @requires control about exist access_token on localstorage
 */

export function logoutFetch(){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/logout", {
            method: 'GET', 
            headers: {
                'Accept' : 'application/json',
                "Authorization": "Bearer " + sessionStorage.getItem('access_token')
            }
        }).then( response => resolve(response.json()))
    })
}

/**
 * upload
 * @param [file] image
 * @param [string] file_name
 * @return void
 */

export function uploadFetch(data){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/upload", {
            method: 'POST', 
            headers: {
                'Accept' : 'application/json',
                "Authorization": "Bearer " + sessionStorage.getItem('access_token')
            },
            body: data
        }).then( response => resolve(response.json()))
    })
}

/**
 * analize image
 * @param [string] image_name
 * @return JSON
 */

export function analizeFetch(data){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/blob/analize/image", {
            method: 'POST', 
            headers: {
                'Accept' : 'application/json',
                "Authorization": "Bearer " + sessionStorage.getItem('access_token')
            },
            body: data
        }).then( response => resolve(response.json()))
    })
}

/**
 * delete image
 * @param [string] image_name
 * @return JSON
 */

export function deleteFetch(data){
    return new Promise (resolve => { 
        fetch("http://localhost:8000/api/auth/blob/delete/image", {
            method: 'POST', 
            headers: {
                'Accept' : 'application/json',
                "Authorization": "Bearer " + sessionStorage.getItem('access_token')
            },
            body: data
        }).then( response => resolve(response.json()))
    })
}