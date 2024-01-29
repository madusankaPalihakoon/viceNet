import DataService from "../assets/js_modules/DataService.js";
import Post from "./Post.js";

class profileFunction{
    constructor(){
        this.getData = new DataService('../script/getProfileData');
    }

    createActionBtn( href, text) {
        const actionBtn = document.createElement('a');
        actionBtn.className = 'btn btn-primary';
        actionBtn.href = href;
        actionBtn.setAttribute('role', 'button');
        actionBtn.textContent = text;
    
        return actionBtn;
    }

    createBioInfoDiv(bioInfo){
        const bioInfoDiv = document.createElement('div');
        bioInfoDiv.className = 'row bio-info';

        const divHeadline = document.createElement('h3');
        divHeadline.textContent = 'About';
        bioInfoDiv.appendChild(divHeadline);

        bioInfo.forEach(info => {
            const infoUl = document.createElement('ul');
            infoUl.textContent = info.label + ' : ' + info.data;
            bioInfoDiv.appendChild(infoUl);
        });

        return bioInfoDiv;
    }

    createImgThumbnailDiv(thumbnailData) {
        const picInfo = document.createElement('div');
        picInfo.className = 'col pic-info';
        picInfo.id = 'pic-info';

        const divHeadline = document.createElement('h3');
        divHeadline.textContent = 'Picture';
        picInfo.appendChild(divHeadline);

        thumbnailData.forEach(function(img, index) {
            const imgThumbnail = document.createElement('img');
            imgThumbnail.className = 'img-thumbnail';
            imgThumbnail.src = img.imgPath + '/' + img.imgName;
            picInfo.appendChild(imgThumbnail);

            if ((index + 1) % 2 === 0) {
                const br = document.createElement('br');
                picInfo.appendChild(br);
            }
        });

        return picInfo;
    }

    async getProfileData(action) {
        const profileData = new FormData();
        const actionData = { action: action };
        profileData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        try {
            const response = await this.getData.fetchData(profileData);
            return response;
            // console.log(response);
        } catch (error) {
            console.error(error);
            throw error;
        }
    }

    async getPostContainer() {
        try {
            const data = await this.getProfileData('profileData');
    
            if (data.length > 0) {
                const profileData = data[0];
                const thumbnailData = data.slice(1);

                return this.createProfile(profileData, thumbnailData);
            } else {
                console.error('No profile data available.');
                return document.createElement('div');
            }
        } catch (error) {
            console.error('Error fetching profile data:', error);
            throw error;
        }
    }    

    async createProfile(profileData,thumbnailData) {

        const profileDiv = [];

        var CoverImg = profileData.CoverImg;
        var CoverImgPath = profileData.CoverImgPath;
        var ProfileImg = profileData.ProfileImg;
        var ProfileImgPath = profileData.ProfileImgPath;
        var ProfileName = profileData.Name;

        const bioInfo = [
            {label: 'Home Town', data: profileData.Home + ', City In ' + profileData.country},
            {label: 'Birthday', data: profileData.Birthday},
            {label: 'Mobile', data: profileData.Mobile},
            {label: 'Relationship Status', data: profileData.RelationshipStatus},
        ];

        const profileContainer = document.createElement('div');
        profileContainer.className = 'row profile-container';

        const coverContainer =document.createElement('div');
        coverContainer.className = 'col cover-pic';

        const coverImgHTML = document.createElement('img');
        coverImgHTML.className = 'cover-pic-img';
        coverImgHTML.id = 'coverPic';
        coverImgHTML.src = CoverImgPath + '/' + CoverImg;

        const coverEditBtn = document.createElement('div');
        coverEditBtn.className = 'col cover-edit';
        coverEditBtn.innerHTML = '<button class="cover-edit-btn" type="button">Edit Cover</button>';

        const ProfileImgContainer = document.createElement('div');
        ProfileImgContainer.className = 'col profile-pic';

        const ProfileImgHTML = document.createElement('img');
        ProfileImgHTML.className = 'profile-pic-img';
        ProfileImgHTML.id = 'profilePic';
        ProfileImgHTML.src = ProfileImgPath + '/' + ProfileImg;

        const profileEditBtn = document.createElement('div');
        profileEditBtn.className = 'col profile-edit';
        profileEditBtn.innerHTML = '<button class="profile-edit-btn" type="button">Edit Profile</button>';

        coverContainer.appendChild(coverImgHTML);
        coverContainer.appendChild(coverEditBtn);
        ProfileImgContainer.appendChild(ProfileImgHTML);
        ProfileImgContainer.appendChild(profileEditBtn);
    
        coverContainer.appendChild(ProfileImgContainer);
    
        profileContainer.appendChild(coverContainer);
    
        profileDiv.push(profileContainer);
        // return profileContainer;

        const ProfileInfoContainer = document.createElement('div');
        ProfileInfoContainer.className = 'row profile-info-container';

        const profileNameHead = document.createElement('h2');
        profileNameHead.id = 'profileName';
        profileNameHead.textContent = ProfileName;

        const profileActionBtn = this.createActionBtn('#','Edit Profile');

        const bioInfoDiv = this.createBioInfoDiv(bioInfo);

        const bioActionBtn = this.createActionBtn('#','Edit About');

        const hr1 = document.createElement('hr');

        const thumbnailDiv = this.createImgThumbnailDiv(thumbnailData);

        const picActionBtn = this.createActionBtn('#','Show All Picture');

        const hr2 = document.createElement('hr');

        // const friendDiv = this.createFriendDiv();

        ProfileInfoContainer.appendChild(profileNameHead);
        ProfileInfoContainer.appendChild(profileActionBtn);

        ProfileInfoContainer.appendChild(bioInfoDiv);

        ProfileInfoContainer.appendChild(bioActionBtn);

        ProfileInfoContainer.appendChild(hr1);

        ProfileInfoContainer.appendChild(thumbnailDiv);

        ProfileInfoContainer.appendChild(picActionBtn);

        ProfileInfoContainer.appendChild(hr2);

        profileDiv.push(ProfileInfoContainer);

        return profileDiv;
    }

    async getUsersPost() {
        const post = new Post();
        return await post.createPostContainers('userPost');
    }
}
export default profileFunction;