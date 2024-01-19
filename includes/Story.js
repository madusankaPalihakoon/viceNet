import displayMessage from "../assets/js_modules/displayMessage.js";
import DataService from "../assets/js_modules/DataService.js";
const showMessage = new displayMessage();

class Story {
    constructor() {
    }

    uploadStory() {
        const storyUpload = document.getElementById('storyUpload').value.trim();
        const createStoryForm = document.getElementById('createStory');

        if(storyUpload === '') {
            showMessage.alertMessage('Please Select Story Image before Submit!');
        }

        const uploadStory = new DataService('../controller/uploadController');

        const storyData = new FormData(createStoryForm);

        const actionData = {
            action: 'uploadStory'
        };

        storyData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        uploadStory.fetchData(storyData)
            .then(response=> {console.log(response);})
            .catch(error => console.error(error));
    }

    async getStory() {
        const getStory = new DataService('../script/getHomeData');
        const storyForm = new FormData();
    
        const actionData = {
            action: 'getStory',
        };
    
        storyForm.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        try {
            const response = await getStory.fetchData(storyForm);
            return response;
        } catch (error) {
            console.error("Error fetching story:", error);
            throw error;
        }
    }    

    async createStory() {
        let storyData;

        try {
            // Wait for the getStory() promise to resolve
            storyData = await this.getStory();
        } catch (error) {
            // Handle error if needed
            storyData = [];
            console.error(error);
        }

        const storyCard = document.createElement('div');
        storyCard.setAttribute('class','card');
        storyCard.id = 'stories-container';

        const storyRow = document.createElement('div');
        storyRow.className = 'row';
        storyRow.id = 'story-row';

        if (storyData.length > 0) {
            storyData.forEach(story => {
                const storyImgName = story.StoryImg;
                const storyImgPath = story.storyImgPath;
                const storyText = story.StoryText;

                const stories = document.createElement('div');
                stories.id = 'stories';
                stories.setAttribute('class', 'col-md-4 mb-3');
        
                const card = document.createElement('div');
                card.setAttribute('class', 'card');
                stories.appendChild(card);
        
                const storyImg = document.createElement('img');
                storyImg.id = 'stories-img';
                storyImg.setAttribute('class', 'card-img-top');
                storyImg.setAttribute('src', storyImgPath + '/' + storyImgName);
                card.appendChild(storyImg);
        
                const cardBody = document.createElement('div');
                cardBody.setAttribute('class', "card-body");
                card.appendChild(cardBody);
        
                const storyTitle = document.createElement('h5');
                storyTitle.setAttribute('class', "card-title");
                storyTitle.textContent = story.text;
                cardBody.appendChild(storyTitle);
        
                const storyViewBtn = document.createElement('a');
                storyViewBtn.setAttribute('class', "btn btn-primary");
                storyViewBtn.textContent = 'View';
                cardBody.appendChild(storyViewBtn);
        
                storyRow.appendChild(stories);
            });

            storyCard.appendChild(storyRow);
            return storyCard;

        } else {
            const stories = document.createElement('div');
            stories.id = 'stories';
            stories.setAttribute('class', 'col-md-4 mb-3');

            const card = document.createElement('div');
            card.setAttribute('class', 'card');
            stories.appendChild(card);

            const storyImg = document.createElement('img');
            storyImg.id = 'stories-img';
            storyImg.setAttribute('class', 'card-img-top');
            storyImg.setAttribute('src', 'https://via.placeholder.com/180');
            card.appendChild(storyImg);

            const cardBody = document.createElement('div');
            cardBody.setAttribute('class', "card-body");
            cardBody.id = 'stories-details';
            card.appendChild(cardBody);

            const storyTitle = document.createElement('h5');
            storyTitle.setAttribute('class', "card-title");
            storyTitle.textContent = 'No Story yet';
            cardBody.appendChild(storyTitle);

            const storyViewBtn = document.createElement('a');
            storyViewBtn.setAttribute('class', "btn btn-primary");
            storyViewBtn.textContent = 'Create +';
            cardBody.appendChild(storyViewBtn);

            storyRow.appendChild(stories);
            storyCard.appendChild(storyRow);
            return storyCard;
        }
    }
}
export default Story;