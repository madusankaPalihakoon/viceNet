import displayMessage from "../assets/js_modules/displayMessage.js";
import DataService from "../assets/js_modules/DataService.js";
import Comment from "./Comment.js";
const showMessage = new displayMessage();

class Post {
    constructor() {}

    uploadPost() {
        const postUpload = document.getElementById('postUpload').value.trim();
        const createPostForm = document.getElementById('createPost');

        if (postUpload === '') {
            showMessage.alertMessage('Please Select Post Image before Submit!');
        }

        const uploadPost = new DataService('../controller/uploadController');

        const postData = new FormData(createPostForm);

        const actionData = {
            action: 'uploadPost'
        };

        postData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        uploadPost.fetchData(postData)
            .then(response=> {console.log(response);})
            .catch(error => console.error(error));
    }

    async getPost(action) {
        const getPost = new DataService('../script/getHomeData');
        const postForm = new FormData();
    
        const actionData = {
            action: action,
        };
    
        postForm.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);

        try {
            const response = await getPost.fetchData(postForm);
            return response;
        } catch (error) {
            console.error("Error fetching story:", error);
            throw error;
        }
    }

    async updateLikeStatus(postId){
        const updateLike = new DataService('../controller/postController');

        const actionData = {
            action: 'updateLike',
            postID: postId,
        };

        const likeForm = updateLike.createFormData(actionData);

        try{
            const response = await updateLike.fetchData(likeForm);
            console.log(response);
        } catch (error) {
            console.error(error);
        }
    }

    createLikeButton(UserLikeStatus) {
        let likeStatus;
        if(UserLikeStatus === '0' || UserLikeStatus === null || UserLikeStatus === ''){
            likeStatus = false;
        } else {
            likeStatus = true;
        }

        const likeButton = document.createElement('button');
        likeButton.type = 'button';
        likeButton.classList.add('btn', 'like-btn');
        likeButton.id = 'like-btn';
    
        const likeIcon = document.createElement('i');
        likeIcon.classList.add('bi', 'bi-heart-fill', likeStatus ? 'liked' : 'like');
    
        likeButton.appendChild(likeIcon);
    
        return likeButton;
    }

    async createPostContainers(action) {
        let postData;

        try {
            postData = await this.getPost(action);
        } catch (error) {
            postData = [];
            console.log(error);
        }

        const posts = [];
        let comments;
        // const postContainer = document.getElementById('postContainer');

        if (postData.length > 0) {
            postData.forEach(post => {
                const {
                    Name: profileNameValue,
                    ProfileImg: ProfileImg,
                    profilePath,
                    PostImg: postImg,
                    postImgPath,
                    PostID: postId,
                    LikeStatus: UserLikeStatus
                } = post;

                const PostContainer = document.createElement('div');
                PostContainer.classList.add('container', 'post-container');
        
                const postProfileContainer = document.createElement('div');
                postProfileContainer.classList.add('row', 'post-header');
        
                const postProfile = document.createElement('div');
                postProfile.classList.add('post-profile');
        
                const postProfileImg = document.createElement('img');
                postProfileImg.id = 'post-profile-img';
                postProfileImg.src = `${profilePath}/${ProfileImg}`;
                postProfileImg.alt = 'profile';
        
                const profileNameLink = document.createElement('a');
                profileNameLink.classList.add('comment-pro-name');
                profileNameLink.href = '#';
                profileNameLink.textContent = profileNameValue;
        
                const profileName = document.createElement('span');
                profileName.classList.add('post-profile-name');
                profileName.appendChild(profileNameLink);
        
                postProfile.appendChild(postProfileImg);
                postProfile.appendChild(profileName);
                postProfileContainer.appendChild(postProfile);
        
                const postBody = document.createElement('div');
                postBody.classList.add('post-body');
        
                const postImgElement = document.createElement('img');
                postImgElement.id = 'post-img';
                postImgElement.src = `${postImgPath}/${postImg}`;
                postImgElement.alt = `post${postId}`;
        
                postBody.appendChild(postImgElement);
        
                const postFooter = document.createElement('div');
                postFooter.classList.add('container', 'post-footer');
        
                const likeSection = document.createElement('div');
                likeSection.classList.add('container-post-action', 'like-section');
        
                const likeButton = this.createLikeButton(UserLikeStatus);
                likeButton.addEventListener('click', async () => {
                    likeButton.querySelector('i').classList.toggle('liked');
                    await this.updateLikeStatus(postId);
                });
        
                likeSection.appendChild(likeButton);
        
                const commentSection = document.createElement('div');
                commentSection.classList.add('container-post-action', 'comment-section');
        
                const commentButton = document.createElement('button');
                commentButton.type = 'button';
                commentButton.classList.add('btn', 'comment-btn');
                commentButton.setAttribute('data-toggle', 'modal');
                commentButton.setAttribute('data-target', '#commentModel'+ postId);
                commentButton.innerHTML = '<i class="bi bi-chat-fill"></i>';
        
                commentSection.appendChild(commentButton);
        
                const downloadSection = document.createElement('div');
                downloadSection.classList.add('container-post-action', 'download-section');
        
                const downloadButton = document.createElement('button');
                downloadButton.type = 'button';
                downloadButton.classList.add('btn', 'download-btn');
                downloadButton.innerHTML = '<i class="bi bi-cloud-arrow-down-fill"></i>';
        
                downloadSection.appendChild(downloadButton);
        
                postFooter.appendChild(likeSection);
                postFooter.appendChild(commentSection);
                postFooter.appendChild(downloadSection);
        
                PostContainer.appendChild(postProfileContainer);
                PostContainer.appendChild(postBody);
                PostContainer.appendChild(postFooter);

                const comment = new Comment();

                const commentData = {'postID':postId,
                                    'profileName':profileNameValue};

                const commentModel = comment.createCommentModal(commentData);

                posts.push(PostContainer);
                comments = commentModel;
            });
        } else {
            var divElement = document.createElement('div');
            divElement.style.marginTop = '50px';

            var hr1 = document.createElement('hr');

            var ulElement = document.createElement('ul');
            ulElement.textContent = 'No Post Available';

            divElement.append(hr1,ulElement);
            return divElement;
        }

        return [posts,comments];
    }
}
export default Post;