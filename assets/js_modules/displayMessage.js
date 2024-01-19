import DataService from "./DataService.js";
class displayMessage {
    constructor() {
    }

    async fetchMessage() {
        const dataService = new DataService('../script/getMessage');
    
        const data = {
            action: 'getMessage',
        };
    
        const formData = dataService.createFormData(data);
    
        try {
            const response = await dataService.fetchData(formData);
            return response.message;
        } catch (error) {
            return console.error(error);
        }
    }    

    async displayMessage( message) {
        const messageContainer = document.getElementById("message-container");
        if(message === null){
            try {
                const message = await this.fetchMessage();
                messageContainer.innerHTML = message;
            } catch (error) {
                console.log(error);
            }
        } else {
            messageContainer.innerHTML = message;
        }
    }

    async alertMessage(message){
        if(message === null){
            try {
                const message = await this.fetchMessage();
                alert(message);
            } catch (error) {
                console.log(error);
            }
        } else {
            alert(message)
        }
    }

    clearMessage(){
        const messageContainer = document.getElementById("message-container");
        messageContainer.innerHTML = "";
    }
}
export default displayMessage;