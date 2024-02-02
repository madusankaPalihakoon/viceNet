class RequestBtn {
    constructor(buttons, text) {
        this.buttons = buttons;
        this.text = text;
    }

    createBtn(type) {
        const buttonInfo = this.buttons.find(btn => btn[type]);
        const textInfo = this.text.find(txt => txt[type]);

        if (!buttonInfo || !textInfo) {
            console.error(`Button or text not found for type: ${type}`);
            return null;
        }

        const btn = document.createElement('button');
        btn.id = 'reqBtn';
        btn.className = buttonInfo[type];
        btn.textContent = textInfo[type];
        return btn;
    }

    createSendBtn() {
        return this.createBtn('send');
    }

    createSentBtn() {
        return this.createBtn('sent');
    }

    createReceivedBtn() {
        return this.createBtn('received');
    }

    createFriendBtn() {
        return this.createBtn('friend');
    }
}
export default RequestBtn;
