import DataService from "./DataService.js";
class Redirect{
    constructor(){}

    async fetchRedirect() {
        const dataService = new DataService('../script/getRedirect');
    
        const data = {
            action: 'getRedirect',
        };
    
        const formData = dataService.createFormData(data);
    
        try {
            const response = await dataService.fetchData(formData);
            return response.url;
        } catch (error) {
            return console.error(error);
        }
    }

    async redirectPage(){
        try {
            const url = await this.fetchRedirect();
            window.location.href = url;
        } catch (error) {
            console.log(error);
        }
    }
}
export default Redirect;