class Loader {
    constructor() {
    }

    toggleSignupBtn( status){
        const signupBtn = document.getElementById("signupBtn");
        if(status){
            signupBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Wait...';
        } else {
            signupBtn.innerHTML = 'Sign Up';
        }
    }

    toggleResendBtn(status){
        const resendBtn = document.getElementById('resendBtn');
        if(status) {
            resendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';
        } else {
            resendBtn.innerHTML = 'Send Success';
        }
    }

    toggleLoginBtn(status) {
        const loginBtn = document.getElementById('loginBtn');
        if(status) {
            loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
        } else {
            loginBtn.innerHTML = 'Login';
        }
    }

    toggleVerifyBtn( status) {
        const verifyBtn = document.getElementById('verificationBtn');

        if(status) {
            verifyBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Checking...';
        } else {
            verifyBtn.innerHTML = 'Try Agin';
        }
    }

    toggleSetupBtn( status) {
        const setupBtn = document.getElementById('setupBtn');

        if(status) {
            setupBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        } else {
            setupBtn.innerHTML = 'Setup Profile';
        }
    }

    togglePageLoader(status) {
        const mainContainer = document.getElementById('mainContainer');
        const loadingContainer = document.getElementById('loadingContainer');
        if(status){
            mainContainer.style.display = 'none';
            loadingContainer.innerHTML = '<div class="spinner-border text-primary" role="status" id="loadingSpinner"></div><span class="text-primary loading-text">Loading...</span>';
        } else {
            loadingContainer.style.display = 'none';
            mainContainer.style.display = 'block';
        }
    }
}
export default Loader;