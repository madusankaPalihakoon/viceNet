import TimeOut from "../assets/js_modules/TimeOut.js";
import Loader from "../assets/js_modules/Loader.js";
import friendsFunction from "./friendsFunction.js";

const timeOut = new TimeOut();
const loader = new Loader();
const friends = new friendsFunction();

document.addEventListener("DOMContentLoaded", async ()=> {
    loader.togglePageLoader(false);

    friends.createFriendList();
});