//https://joakim.beng.se/blog/posts/a-javascript-router-in-20-lines.html
//https://www.learnwithjason.dev/blog/get-form-values-as-json/

import $ from 'jquery'
import {login, signup} from '../src/fetches'
/**
 * Retrieves input data from a form and returns it as a JSON object.
 * @param  {HTMLFormControlsCollection} elements  the form elements
 * @return {Object}                               form data as an object literal
 */
const formToJSON = elements => [].reduce.call(elements, (data, element) => {
    data[element.name] = element.value;
    return data;
  }, {});

$('document').ready(()=>{
    //initialize page
    //pulisco la sessio storage
    sessionStorage.clear()


    //login
    $('#login_form').on('submit', function(event){
        event.preventDefault()
        const data_form = new FormData()
        data_form.append("email", $("#email").val())
        data_form.append("password", $("#password").val())

        // const data = formToJSON($(this)[0].elements)
        // console.log("data: ")
        // console.log(data)

        login(data_form).then(response => {
            if("access_token" in response){
                //save token on session storage
                sessionStorage.setItem('access_token', response['access_token'])
                //redirect to user home page
                window.location.assign('./views/home.html')
            }
            else
                alert(response["message"])
        })
    })

    //register
    $('#register_form').on('submit', function(event){
        event.preventDefault()
        const data_form = new FormData()
        data_form.append("name", $("#name").val())
        data_form.append("email", $("#email").val())
        data_form.append("password", $("#password").val())
        data_form.append("password_confirmation", $("#password_confirmation").val())

        signup(data_form).then(response => {
            //check if response return with errors message
            let err = response['errors']
            console.log(err)
            
            //get FIRST key error message and show it
            if(err){alert(err[Object.keys(err)])}
            else{
                alert("Please confirm your email")
                window.location.assign('../index.html')
            }
            // console.log("signup response")
            // console.log(response)
        })
    })
})
