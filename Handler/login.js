import DataService from "../assets/js_modules/DataService.js";
import displayMessage from "../assets/js_modules/displayMessage.js";
import Redirect from "../assets/js_modules/Redirect.js";
import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";

const timeOut = new TimeOut();
const loader = new Loader();

document.addEventListener("DOMContentLoaded",()=> {

    const sendLoginData = new DataService('../controller/loginController');
    const displayError = new displayMessage();
    const redirect = new Redirect();

    const loginForm = document.getElementById("loginForm");

    loginForm.addEventListener('submit', (event) => {
        event.preventDefault();
        loader.toggleLoginBtn(true);

        const loginData = new FormData(loginForm);

        const actionData = {
            action: 'login',
        };

        loginData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        sendLoginData.fetchData(loginData)
            .then(response=> {
                if(response.status){
                    redirect.redirectPage();
                } else {
                    displayError.displayMessage(null);
                }
            })
            .catch(error => console.error(error))
            .finally(()=>{
                loader.toggleLoginBtn(false);
            })
    });

});