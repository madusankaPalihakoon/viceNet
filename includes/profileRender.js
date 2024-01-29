import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";
import profileFunction from "./profileFunction.js";

const timeOut = new TimeOut();
const loader = new Loader();
const profile = new profileFunction();

document.addEventListener("DOMContentLoaded", async ()=> {
    loader.togglePageLoader(true);

    try {
        const profileContainer = document.getElementById('profile_container');
        const pageBody = document.body;
    
        const profileCon = await profile.getPostContainer();
    
        profileCon.forEach(function(container) {
            profileContainer.append(container);
        });

        const usersPostData = await profile.getUsersPost();

        const posts = usersPostData[0];
        const comments = usersPostData[1];

        posts.forEach(post =>{
            profileContainer.appendChild(post);
        });

        comments.forEach(comment =>{
            pageBody.appendChild(comment);
        });

    } catch (error) {
        loader.togglePageLoader(true);
        console.error(error);
    } finally {
        loader.togglePageLoader(false);
    }    
});