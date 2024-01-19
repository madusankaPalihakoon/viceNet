import Validate from "../assets/js_modules/Validate.js";
import DataService from "../assets/js_modules/DataService.js";
import displayMessage from "../assets/js_modules/displayMessage.js";
import Redirect from "../assets/js_modules/Redirect.js";
import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";

const timeOut = new TimeOut();
const loader = new Loader();

document.addEventListener("DOMContentLoaded",()=> {

    const validateForm = new Validate();
    const sendSignupData = new DataService('../controller/signupController');
    const displayError = new displayMessage();
    const redirect = new Redirect();

    const signupForm = document.getElementById("signupForm");

    signupForm.addEventListener('submit', (event) => {
        event.preventDefault();
        loader.toggleSignupBtn(true); // toggle sign up button to loading 

        if (validateForm.validateSignupForm()) {
            const signupData = new FormData(signupForm);

            const actionData = {
                action: 'signup',
            };
    
            signupData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]); 

            sendSignupData.fetchData(signupData)
            .then(response=> {
                if(response.status){
                    redirect.redirectPage();
                } else {
                    displayError.displayMessage(null);
                }
            })
            .catch(error => console.error(error))
            .finally(()=>{
                loader.toggleSignupBtn(false);
            })
        }
    });
});