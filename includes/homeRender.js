import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";
import homeFunction from "./homeFunction.js";

const timeOut = new TimeOut();
const loader = new Loader();
const home = new homeFunction();

document.addEventListener("DOMContentLoaded", async ()=> {
    loader.togglePageLoader(false);

    const postContainer = document.getElementById('postContainer');

    const pageBody = document.body;

    const uploadSelectBtn = document.getElementById('upload-select-btn');

    const storyCard = await home.getStoryCard();
    postContainer.appendChild(storyCard);

    const uploadContainers = await home.createUploadContainer();
    uploadContainers.forEach(container => {
        uploadSelectBtn.appendChild(container);
    });

    postContainer.appendChild(uploadSelectBtn);

    const postData = await home.getPostContainers();

    if (typeof postData === "object") {
        postContainer.append(postData);
    }

    const posts = postData[0];
    const comments = postData[1];

    posts.forEach(post=>{
        postContainer.appendChild(post);
    });

    comments.forEach(comment=>{
        pageBody.appendChild(comment);
    });

    // for (let index = 0; index < comments.length; index++) {
    //     comments[index].forEach(comment => {
    //         pageBody.appendChild(comment);
    //     });
    // }
});