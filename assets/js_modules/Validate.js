import displayMessage from "./displayMessage.js";
import Loader from "./Loader.js";

const loader = new Loader();
const displayError = new displayMessage();

class Validate{
    constructor(){}

    validateSignupForm(){
        

        const fields = [
            'inputName',
            'inputEmail',
            'inputUsername',
            'selectCountry',
            'inputPhone',
            'inputBirthday',
            'inputPassword',
            'inputConfirmPassword',
        ];


        if(fields.every(filed => document.getElementById(filed).value.trim() !== '' )){
            const termCheckBox = document.getElementById('checkTerm');

            if(!termCheckBox.checked) {
                displayError.displayMessage('Please agree to the Term and Condition before Sign up!');
                loader.toggleSignupBtn(false);
                return false;
            }

            const password = document.getElementById('inputPassword').value.trim();
            const confirmPassword = document.getElementById('inputConfirmPassword').value.trim();

            if (password !== confirmPassword) {
                displayError.displayMessage('Password and Confirm Password do not match. Please try again!');
                loader.toggleSignupBtn(false);
                return false;
            }

            return true;
        }

        displayError.displayMessage('Please fill in all required fields!');
        loader.toggleSignupBtn(false);
        return false;
    }

    validateVerificationForm(){
        const verificationCode = document.getElementById('verificationCode').value.trim();

        if ( verificationCode !== '') {
            return true;
        }

        displayError.displayMessage("Verification fields are empty. Please fill in all required fields and try again.");
        loader.toggleVerifyBtn(false);
        return false;
    }

    validateSetupForm(){
        const selectProfilePic = document.getElementById('selectProfilePic').files.length;
        const selectCoverPic = document.getElementById('selectCoverPic').files.length;
        const inputHomeTown = document.getElementById('homeTown').value.trim();
        const inputSelectBirthday = document.getElementById('selectBirthday').value.trim();

        if (selectProfilePic === 0 || selectCoverPic === 0 || inputHomeTown === '' || inputSelectBirthday === '') {
            displayError.displayMessage("Please fill in the required fields to update your profile, or you can skip this step for now.");
            loader.toggleSetupBtn(false);
            return false;
        }

        return true;
    }
}
export default Validate;