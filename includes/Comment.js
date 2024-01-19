import DataService from "../assets/js_modules/DataService.js";
const sendComment = new DataService('../controller/postController');
class Comment {
    constructor() {}

    createModalHeader( profileNameValue) {
        const modalHeader = document.createElement('div');
        modalHeader.classList.add('modal-header');
    
        const modalTitle = document.createElement('h6');
        modalTitle.classList.add('modal-title');
        modalTitle.id = 'exampleModalLongTitle';
        modalTitle.textContent = 'All Comments For ' + profileNameValue+'"s Post';
    
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.classList.add('close');
        closeButton.setAttribute('data-dismiss', 'modal');
        closeButton.setAttribute('aria-label', 'Close');
        closeButton.style.backgroundColor = 'transparent';
        closeButton.style.border = 'none';
    
        const closeIcon = document.createElement('span');
        closeIcon.setAttribute('aria-hidden', 'true');
        closeIcon.innerHTML = '&times;';
    
        closeButton.appendChild(closeIcon);
    
        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(closeButton);
    
        return modalHeader;
    }
    
    createModalBody(postId) {
        const modalBody = document.createElement('div');
        modalBody.classList.add('modal-body');
    
        const loadComments = document.createElement('div');
        loadComments.id = 'loadComments';
        loadComments.classList.add('col', 'friends-info');
        loadComments.style.border = '1px solid gray';
        loadComments.style.borderRadius = '0.5rem';
    
        const profileInfo = document.createElement('div');
        profileInfo.classList.add('col', 'sm-profile');
    
        const profileImg = document.createElement('img');
        profileImg.classList.add('profile-sm-pic');
        profileImg.src = 'https://via.placeholder.com/30/30';
        profileImg.alt = '...';
    
        const profileNameLink = document.createElement('a');
        profileNameLink.classList.add('comment-pro-name');
        profileNameLink.href = '#';
        profileNameLink.textContent = "madusanka";
    
        const commentText = document.createElement('p');
        commentText.id = 'comments';
        commentText.textContent = 'comment';
    
        const hr = document.createElement('hr');
    
        profileInfo.appendChild(profileImg);
        profileInfo.appendChild(profileNameLink);
    
        loadComments.appendChild(profileInfo);
        loadComments.appendChild(commentText);
        loadComments.appendChild(hr);
    
        const commentForm = document.createElement('form');
        commentForm.id = ('commentForm'+postId);
    
        const formGroup = document.createElement('div');
        formGroup.classList.add('form-group');
    
        const commentInput = document.createElement('input');
        commentInput.type = 'text';
        commentInput.classList.add('form-control');
        commentInput.id = 'comment-text';
        commentInput.setAttribute('name','comment-text');
        commentInput.placeholder = 'Enter Your Comment Here';
    
        formGroup.appendChild(commentInput);
        commentForm.appendChild(formGroup);
    
        modalBody.appendChild(loadComments);
        modalBody.appendChild(commentForm);
    
        return modalBody;
    }
    
    createModalFooter(postId) {
        const modalFooter = document.createElement('div');
        modalFooter.classList.add('modal-footer');
    
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.classList.add('btn', 'btn-secondary');
        closeButton.setAttribute('data-dismiss', 'modal');
        closeButton.id = 'comment-close-btn';
        closeButton.textContent = 'Close';
    
        const saveButton = document.createElement('button');
        saveButton.type = 'submit';
        saveButton.classList.add('btn', 'btn-primary');
        saveButton.textContent = 'Submit Comment';

        // Add event listener to the saveButton
        saveButton.addEventListener('click', () => this.handleSaveChanges(postId));
    
        modalFooter.appendChild(closeButton);
        modalFooter.appendChild(saveButton);
    
        return modalFooter;
    }

    // Event handler for the "Save changes" button
    handleSaveChanges(postId) {
        const commentForm = document.getElementById('commentForm' + postId);
        
        const commentData = new FormData(commentForm);

        const actionData = {
            action: 'submitComment',
            postId: postId,
        };

        commentData.append(Object.keys(actionData)[0], actionData[Object.keys(actionData)[0]]);
        commentData.append(Object.keys(actionData)[1], actionData[Object.keys(actionData)[1]]);

        sendComment.fetchData(commentData)
            .then(response=> {console.log(response);})
            .catch(error => console.error(error));
    }    
    
    createCommentModal( commentData) {
        const modals = [];
        if (commentData.postID && commentData.profileName) {
            const modal = document.createElement('div');
            modal.classList.add('modal', 'fade');
            modal.id = 'commentModel'+commentData.postID;
            modal.tabIndex = '-1';
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-hidden', 'true');
        
            const modalDialog = document.createElement('div');
            modalDialog.classList.add('modal-dialog', 'modal-dialog-centered');
            modalDialog.setAttribute('role', 'document');
        
            const modalContent = document.createElement('div');
            modalContent.classList.add('modal-content');
        
            modalContent.appendChild(this.createModalHeader( commentData.profileName));
            modalContent.appendChild(this.createModalBody(commentData.postID));
            modalContent.appendChild(this.createModalFooter(commentData.postID));
        
            modalDialog.appendChild(modalContent);
            modal.appendChild(modalDialog);
        
            modals.push(modal);
            return modals;
        } else {
            return '';
        }
    }
}
export default Comment;