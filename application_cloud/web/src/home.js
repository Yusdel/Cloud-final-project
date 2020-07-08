import $ from 'jquery'
import {getAllUrlFetch, logoutFetch, uploadFetch} from './fetches'

$('document').ready(()=>{
    //Logout on click action
    $('#logout').click(()=>{
        console.log("hello")
        logout()
    })

    //toggle upload form
    $('#addPhoto').click(function(){
        $('#upload_form').toggle()
    })

    //populate home with user photos
    let img_container = document.getElementById("img_container")
    let str = ""
    populateHomeWithPhoto(img_container, str)

    //submit form upload image
    $('#upload_form').on('submit', function(event){
        event.preventDefault()

        //get data form
        const data_form = new FormData()
        console.log($("#image").val())
        data_form.append("image", $('#image')[0].files[0], $("#image").val())
        data_form.append("image_name", $("#image_name").val())

        uploadFetch(data_form).then( res => {
            window.location.assign("./home.html")
            console.log(res)
        })
    })
})

//GET private urls images from container on Azure Blob Storage
function populateHomeWithPhoto (img_container, str){
    getAllUrlFetch().then(response => {
        str = ""     
        response.forEach(element => {
            str+=`
                <div class="card" width="400px" height="600px">
                    <div class="image" background="cover">
                        <img src="${element.url}">
                    </div>
                    <h4 class="nameImg">${element.name}</h4>
                    <div class="fab" name="${element.name}" urlImg="${element.url}">&#43;</div>
                </div>
            `
        })

        img_container.innerHTML=str
        
    }).then(()=>{
        //add even listener to button to pass data from url to new html page
        document.querySelectorAll('.fab').forEach(function(item){
            item.addEventListener('click', (e)=>{
                let url = "./focusImage.html?name="+e.target.getAttribute("name")+"&url="+e.target.getAttribute("urlImg")
                window.location.assign(url)
            })
        })
    })
}

//GET logout cancel data session on browser and on server
function logout (){
    logoutFetch().then(res => {
        //redirect to user home page
        window.location.assign('../index.html')
    })
}

//Move to focusImage.html and create url to pass name image
function createUrlWithParams(){

}