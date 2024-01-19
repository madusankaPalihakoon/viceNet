class TimeOut {
    constructor() {
        this.redirectToNewPage();
    }

    // Method to handle redirection with a delay
    redirectToNewPage() {
        setTimeout(function () {
            window.location.href = '../pages/login';
        }, 1800000);
    }
}
export default TimeOut;
