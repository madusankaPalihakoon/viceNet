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

    const resendForm = document.getElementById("resendForm");

    resendForm.addEventListener('submit', (event) => {
        event.preventDefault();
        loader.toggleResendBtn(true); // toggle resend button to sending

        const resendData = new FormData();

        const actionData = {
            action: 'resend',
        };

        resendData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);
        
        sendResendData.fetchData(resendData)
            .then(response=> {
                displayMsg.displayMessage(null);
            })
            .catch(error => console.error(error))
            .finally(()=>{
                loader.toggleResendBtn(false);
            })
    });
});