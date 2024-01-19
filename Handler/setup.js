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
    const sendSetupData = new DataService('../controller/setupController');
    const displayError = new displayMessage();
    const redirect = new Redirect();

    const setupForm = document.getElementById("setupForm");

    setupForm.addEventListener('submit', (event) => {
        event.preventDefault();
        
        displayError.clearMessage(); // Clear old error messages
        loader.toggleSetupBtn(true);

        if (validateForm.validateSetupForm()){
            const setupData = new FormData(setupForm);

            const actionData = {
                action: 'setup',
            };

            setupData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

            sendSetupData.fetchData(setupData)
                .then(response=> {
                    if (response.status){
                        redirect.redirectPage();
                    } else {
                        displayError.displayMessage(null);
                    }
                })
                .catch(error => console.error(error))
                .finally(()=>{
                    loader.toggleSetupBtn(false);
                })
        }
    });
});
