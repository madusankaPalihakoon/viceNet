import DataService from '../assets/js_modules/DataService.js';

class friendsFunction{
    constructor() {}

    async getFriendData() {
        const friendForm = new FormData();
        const getFriend = new DataService('../script/getFriendData');
    
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

    async sendRequestData(id) {
        const requestForm = new FormData();
        const sendRequest = new DataService('../controller/friendController');
    
        const actionData = {
            action: 'sendRequest',
            id: id,
        };
    
        requestForm.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);
        requestForm.append(Object.keys(actionData)[1], actionData[Object.keys(actionData)[1]]);

        try {
            const response = await sendRequest.fetchData(requestForm);
            console.log(response);
        } catch (error) {
            console.error(error);
        }
    }

    async createFriendList() {
        let friendData;

        try {
            friendData = await this.getFriendData();
        } catch (error) {
            friendData = [];
            console.log(error);
        }

        if (friendData.length > 0) {
            let friends = [];
            friendData.forEach(friend => {
                const column = document.createElement('div');
                column.className = 'col';

                const friendListDiv = document.createElement('div');
                friendListDiv.className = 'friendList';

                const profileImg = document.createElement('img');
                profileImg.className = 'profile-pic-sm';
                profileImg.src = friend.profilePath + '/' + friend.ProfileImg;

                const nameSpan = document.createElement('span');

                const spanLink = document.createElement('a');
                spanLink.href = 'profile' + '?' + friend.ProfileID;

                const reqBtn = document.createElement('button');
                reqBtn.className = 'btn btn-primary';
                reqBtn.id = 'reqBtn';
                reqBtn.textContent = 'Send Request';

                // Set some text content for the link
                spanLink.textContent = friend.Name;

                reqBtn.addEventListener('click', async () => {
                    await this.sendRequestData(friend.ProfileID);
                });

                friendListDiv.appendChild(profileImg);
                nameSpan.appendChild(spanLink);
                friendListDiv.appendChild(nameSpan);
                friendListDiv.appendChild(reqBtn);

                column.appendChild(friendListDiv);

                friends.push(column);
            });

            return friends;

        } else {
            return '';
        }
    }
}
export default friendsFunction;

{/* <div class="col" >
        <div class="friendList">
            <img class="profile-pic-sm" src="https://via.placeholder.com/50" alt="" srcset="">
            <span><a href="">full name</a></span>
        </div>
    </div> */}