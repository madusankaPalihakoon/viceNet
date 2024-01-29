import DataService from '../assets/js_modules/DataService.js';
import Story from "./Story.js";
import Post from "./Post.js";

const post = new Post();
const story = new Story();

class homeFunction {
    constructor(){}

    getStoryCard() {
        return story.createStory([]);
    }

    showHiddenForm(show,hidden) {
        const showContainer = document.getElementById(show);
        const hiddenContainer = document.getElementById(hidden);

        if(showContainer.style.display === 'none') {
            hiddenContainer.style.display = 'none';
            showContainer.style.display = 'block';
        } else {
            showContainer.style.display = 'none';
        }
    }

    async createUploadContainer() {
        const uploadContainers = [];

        const menuBtnContainer = document.createElement('div');
        menuBtnContainer.className = 'upload-select-btn';

        const buttonColumn = document.createElement('div');
        buttonColumn.className = 'col w-100';

        const showPostBtn = document.createElement('button');
        showPostBtn.className = 'btn btn-success';
        showPostBtn.type = 'button';
        showPostBtn.id = 'show-post-btn';
        showPostBtn.textContent = 'Create a Post';

        const showStoryBtn = document.createElement('button');
        showStoryBtn.className = 'btn btn-success';
        showStoryBtn.type = 'button';
        showStoryBtn.id = 'show-story-btn';
        showStoryBtn.textContent = 'Create a Story';

        buttonColumn.appendChild(showPostBtn);
        buttonColumn.appendChild(showStoryBtn);

        menuBtnContainer.append(buttonColumn);

        uploadContainers.push(menuBtnContainer);

        // ! form container

        const formContainer = document.createElement('div');
        formContainer.className = 'card-body';

        // ! post form

        const postFormContainer = document.createElement('div');
        postFormContainer.id = 'post-hidden';
        postFormContainer.style.display = 'none';

        const postFormHead = document.createElement('h5');
        postFormHead.className = 'text-primary';
        postFormHead.textContent = 'Create a Post';

        const hr1 = document.createElement('hr');

        const createPostForm = document.createElement('form');
        createPostForm.id = 'createPost';
        createPostForm.setAttribute('enctype','multipart/form-data');
        createPostForm.setAttribute('action','');

        const formGroup1 = document.createElement('div');
        formGroup1.className = 'form-group';

        const formGroup1Headline = document.createElement('h6');
        formGroup1Headline.setAttribute('for','postUpload');
        formGroup1Headline.textContent = 'Select Image';

        const selectPostImg = document.createElement('input');
        selectPostImg.type = 'file';
        selectPostImg.className = 'form-control';
        selectPostImg.id = 'postUpload';
        selectPostImg.name = 'postImg';

        const formGroup2 = document.createElement('div');
        formGroup2.className = 'form-group';

        const formGroup2Headline = document.createElement('h6');
        formGroup2Headline.setAttribute('for','postDescription');
        formGroup2Headline.textContent = 'Image Caption';

        const postImgCaption = document.createElement('input');
        postImgCaption.type = 'text';
        postImgCaption.className = 'form-control';
        postImgCaption.id = 'postDescription';
        postImgCaption.name = 'postText';

        const postFormSubmitBtn = document.createElement('button');
        postFormSubmitBtn.type = 'submit';
        postFormSubmitBtn.className = 'btn btn-primary';
        postFormSubmitBtn.id = 'uploadBtn';
        postFormSubmitBtn.textContent = 'Upload Post';

        createPostForm.append(formGroup1,formGroup1Headline,selectPostImg,formGroup2,formGroup2Headline,postImgCaption,postFormSubmitBtn);

        postFormContainer.append(postFormHead,hr1,createPostForm)

        uploadContainers.push(postFormContainer);

        // ! story form

        const storyFormContainer = document.createElement('div');
        storyFormContainer.id = 'story-hidden';
        storyFormContainer.style.display = 'none';

        const storyFormHead = document.createElement('h5');
        storyFormHead.className = 'text-primary';
        storyFormHead.textContent = 'Create a Story';

        const hr2 = document.createElement('hr');

        const createStoryForm = document.createElement('form');
        createStoryForm.id = 'createStory';
        createStoryForm.setAttribute('enctype','multipart/form-data');
        createStoryForm.setAttribute('action','');

        const formGroup3 = document.createElement('div');
        formGroup3.className = 'form-group';

        const formGroup3Headline = document.createElement('h6');
        formGroup3Headline.setAttribute('for','storyUpload');
        formGroup3Headline.textContent = 'Select Image';

        const selectStoryImg = document.createElement('input');
        selectStoryImg.type = 'file';
        selectStoryImg.className = 'form-control';
        selectStoryImg.id = 'storyUpload';
        selectStoryImg.name = 'storyImg';

        const formGroup4 = document.createElement('div');
        formGroup4.className = 'form-group';

        const formGroup4Headline = document.createElement('h6');
        formGroup4Headline.setAttribute('for','storyDescription');
        formGroup4Headline.textContent = 'Image Caption';

        const storyImgCaption = document.createElement('input');
        storyImgCaption.type = 'text';
        storyImgCaption.className = 'form-control';
        storyImgCaption.id = 'storyDescription';
        storyImgCaption.name = 'storyText';

        const storyFormSubmitBtn = document.createElement('button');
        storyFormSubmitBtn.type = 'submit';
        storyFormSubmitBtn.className = 'btn btn-primary';
        storyFormSubmitBtn.id = 'uploadBtn';
        storyFormSubmitBtn.textContent = 'Upload Story';

        createStoryForm.append(formGroup3,formGroup3Headline,selectStoryImg,formGroup4,formGroup4Headline,storyImgCaption,storyFormSubmitBtn);

        storyFormContainer.append(storyFormHead,hr2,createStoryForm);

        uploadContainers.push(storyFormContainer);

        showPostBtn.addEventListener('click',()=>{
            this.showHiddenForm('post-hidden','story-hidden');
        });

        showStoryBtn.addEventListener('click',()=>{
            this.showHiddenForm('story-hidden','post-hidden');
        });

        createPostForm.addEventListener('submit', (event) => {
            event.preventDefault();
            post.uploadPost(); //! upload post
        });

        createStoryForm.addEventListener('submit', (event) => {
            event.preventDefault();
            story.uploadStory(); //! upload story
        });

        return uploadContainers;
    }

    async getPostContainers() {
        return post.createPostContainers('getPost');
    }
}
export default homeFunction;