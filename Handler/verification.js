import Validate from "../assets/js_modules/Validate.js";
import DataService from "../assets/js_modules/DataService.js";
import displayMessage from "../assets/js_modules/displayMessage.js";
import Redirect from "../assets/js_modules/Redirect.js";
import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";

const timeOut = new TimeOut();
const loader = new Loader();

document.addEventListener("DOMContentLoaded",()=> {

    const sendResendData = new DataService('../controller/verificationController');
    const displayMsg = new displayMessage();
    const redirect = new Redirect();
    const formValidate = new Validate();

    const verificationFrom = document.getElementById("verificationForm");

    verificationFrom.addEventListener('submit', (event) => {
        event.preventDefault();
        loader.toggleVerifyBtn(true);

        if (formValidate.validateVerificationForm()){

            const verificationData = new FormData(verificationFrom);

            const actionData = {
                action: 'verify',
            };

            verificationData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

            sendResendData.fetchData(verificationData)
            .then(response=> {
                if(response.status){
                    redirect.redirectPage();
                } else {
                    displayMsg.displayMessage(null);
                }
            })
            .catch(error => console.error(error))
            .finally(()=>{
                loader.toggleVerifyBtn(false);
            });
        }
        
    });
});