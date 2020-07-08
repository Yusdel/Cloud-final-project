import $ from 'jquery'
import {analizeFetch, deleteFetch} from './fetches'

$('document').ready(()=>{
    //show image
    let img_container = document.getElementById("#container_image_focus")
    console.log(img_container)
    createContainerAndInsertImg(img_container)
})

function createContainerAndInsertImg(img_container){
    let params = getParams(window.location.href);
    console.log(params);
    let str=`
        <div class="card" width="400px" height="600px">
            <div class="image" background="cover">
                <img src="${params[1]}">
            </div>
            <h4 class="nameImg" style="color:black;">${params[0]}</h4>
            <span class="showtag" type="button" style="color:black;" name="${params[0]}">SHOW TAGS</span>
            <span class="del" type="button" style="color:blue;" name="${params[0]}">DELETE</span>
        </div>
    `
    console.log(str)

    img_container.innerHTML=str

    //add even listener to button to pass data from url to new html page
    document.querySelector('.showtag').addEventListener('click', (e)=>{
        let param = e.target.getAttribute("name");
        //get data form
        const data_form = new FormData()
        data_form.append("image_name", param)
        analizeFetch(data_form).then( res => {
            str = ""
            console.log(res['tags'])
            res['tags'].forEach(el => {
                str+= `<p>name: ${el.name}, confidence: ${el.confidence}</p>`
            })
            
            console.log("str")
            console.log(str)
            document.getElementById("img_tags").innerHTML=str
        })

    })

    document.querySelector(".del").addEventListener('click', (e)=>{
        let param = e.target.getAttribute("name");
        const data_form = new FormData()
        data_form.append("image_name", param)

        deleteFetch(data_form).then(res => {
            //redirect to home
            window.location.assign("./home.html")
        })
    })
}

var getParams = function (url) {
    let ind = url.indexOf("=")
    let uri = url.substring(ind+1, url.length)
    ind = uri.indexOf("&")
    let nameImage = uri.substring(0, ind)
    ind = uri.indexOf("=")
    let urlImage = uri.substring(ind+1, uri.length)

	return [nameImage, urlImage];
};