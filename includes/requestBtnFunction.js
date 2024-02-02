import RequestBtn from "./RequestBtn.js";

const buttons = [
    {'send': 'btn btn-primary'},
    {'sent': 'btn btn-success'},
    {'received': 'btn btn-secondary'},
    {'friend': 'btn btn-info'},
];

const text = [
    {'send': 'Send Request'},
    {'sent': 'Pending'},
    {'received': 'Response'},
    {'friend': 'friend'},
];

const requestBtn = new RequestBtn(buttons, text);

const sendButton = requestBtn.createSendBtn();
if (sendButton) document.body.appendChild(sendButton);

function toggleRequestBtn(btnText){
    switch (btnText) {
        case 'Send Request':
            return requestBtn.createSentBtn();
            break;

        case 'Pending':
            return requestBtn.createSendBtn();
            break

        case 'Response':
            return requestBtn.createFriendBtn();
            break

        case 'friend':
            return requestBtn.createSentBtn();
            break
    
        default:
            return requestBtn.createSentBtn();
            break;
    }
}

const reqBtn = document.getElementById('reqBtn');

reqBtn.addEventListener('click',()=>{
    const btnText = reqBtn.textContent;
    console.log(btnText);

    reqBtn.remove();
    document.body.appendChild(toggleRequestBtn(btnText));
});