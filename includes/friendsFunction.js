import DataService from '../assets/js_modules/DataService.js';
const getFriend = new DataService('../script/getFriendData');

class friendsFunction{
    constructor() {}

    async getFriendData() {
        const friendForm = new FormData();
    
        const actionData = {
            action: 'getFriend',
        };
    
        friendForm.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        try {
            const response = await getFriend.fetchData(friendForm);
            return response;
        } catch (error) {
            console.error("Error fetching story:", error);
            throw error;
        }
    }

    async createFriendList() {
        let friendData;

        try {
            friendData = await this.getFriendData();
            console.log(friendData);
        } catch (error) {
            friendData = [];
            console.log(error);
        }
    }
}
export default friendsFunction;