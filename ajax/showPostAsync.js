const mainContainer = document.getElementById('main_container');

async function getPost(batchIndex) {
    showLoadingIndicator();
    try {
        const url = '../functions/getPostFromDatabase.php?batch=' + batchIndex;
        const response = await fetch(url);
        if (!response.ok) {
        throw new Error('Network response was not ok');
        }
        const postData = await response.json();
        hideLoadingIndicator();
        return postData;
    } catch (error) {
        alert('something went wrong');
    }
}

function createPost(postData) {
    postData.forEach(postData => {
        // Create main container
        const postContainer = document.createElement('div');
        postContainer.classList.add('card', 'post-container');
        postContainer.id = 'post-container-' + postData.post_id; // Use a unique identifier, such as post ID

        // Create figure element
        const figure = document.createElement('figure');
        figure.classList.add('figure');

        // Create image element
        const image = document.createElement('img');
        image.id = 'post_img';
        image.src = '../assets/uploads/post/' + postData.post_img; // Adjust as per your server response
        image.classList.add('figure-img', 'img-fluid', 'rounded');
        image.alt = 'Post Image'; // Add a meaningful alt text

        // Create figcaption element
        const figcaption = document.createElement('figcaption');
        figcaption.classList.add('figure-caption');
        figcaption.id = 'post_caption';
        figcaption.textContent = postData.post_caption; // Adjust as per your server response

        // Append image and figcaption to figure
        figure.appendChild(image);
        figure.appendChild(figcaption);

        // Create horizontal line
        const hr = document.createElement('hr');

        // Create row div
        const rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        // Create columns
        const columns = ['like-col', 'comment-col', 'download-col'];
        columns.forEach(colClass => {
            const colDiv = document.createElement('div');
            colDiv.classList.add('col');

            // Create button based on column class
            if (colClass === 'like-col') {
                const likeButton = document.createElement('button');
                likeButton.classList.add('post-action-btn', 'like-btn');
                likeButton.setAttribute('id', 'like-btn');

                const likeImage = document.createElement('img');
                likeImage.classList.add('action-img', 'like');
                likeImage.setAttribute('id', 'like-img');
                likeImage.src = '../assets/Icon/like.png'; // Adjust as per your icon location

                const likeStatusInput = document.createElement('input');
                likeStatusInput.type = 'text';
                likeStatusInput.classList.add('like-status');
                likeStatusInput.name = 'like-status';
                likeStatusInput.setAttribute('id', 'like_status');
                likeStatusInput.value = 'dislike';

                const postIdInput = document.createElement('input');
                postIdInput.type = 'text';
                postIdInput.classList.add('post-id');
                postIdInput.name = 'post-id';
                postIdInput.setAttribute('id', 'post_id');
                postIdInput.value = postData.post_id; // Adjust as per your server response

                likeButton.appendChild(likeImage);
                likeButton.appendChild(likeStatusInput);
                likeButton.appendChild(postIdInput);

                // Add click event listener to the like button
                likeButton.addEventListener('click', function () {
                    toggleLikeButton(likeImage, likeStatusInput);
                    sendLikeStatus(likeStatusInput,postIdInput);
                });


                colDiv.appendChild(likeButton);
            } else if (colClass === 'comment-col') {
                const commentButton = document.createElement('button');
                commentButton.classList.add('post-action-btn');
                commentButton.type = 'button';
                commentButton.setAttribute('data-bs-toggle', 'modal');
                commentButton.setAttribute('data-bs-target', '#commentModal');
                commentButton.setAttribute('data-bs-whatever', '@mdo');

                const commentImage = document.createElement('img');
                commentImage.classList.add('action-img', 'like');
                commentImage.src = '../assets/Icon/comment-light.png'; // Adjust as per your icon location

                commentButton.appendChild(commentImage);
                colDiv.appendChild(commentButton);
            } else if (colClass === 'download-col') {
                const downloadButton = document.createElement('button');
                downloadButton.classList.add('post-action-btn');

                const downloadImage = document.createElement('img');
                downloadImage.classList.add('action-img');
                downloadImage.src = '../assets/Icon/download-light.png'; // Adjust as per your icon location

                downloadButton.appendChild(downloadImage);
                colDiv.appendChild(downloadButton);
            }

            // Append column to row
            rowDiv.appendChild(colDiv);
        });

        // Create modal
        const commentModal = document.createElement('div');
        commentModal.classList.add('modal', 'fade');
        commentModal.id = 'commentModal';
        commentModal.tabIndex = '-1';
        commentModal.setAttribute('aria-labelledby', 'exampleModalLabel');
        commentModal.setAttribute('aria-hidden', 'true');

        const modalDialog = document.createElement('div');
        modalDialog.classList.add('modal-dialog', 'modal-dialog-centered');

        const modalContent = document.createElement('div');
        modalContent.classList.add('modal-content');

        const modalHeader = document.createElement('div');
        modalHeader.classList.add('modal-header');

        const modalTitle = document.createElement('h1');
        modalTitle.classList.add('modal-title', 'fs-5');
        modalTitle.id = 'exampleModalLabel';
        modalTitle.textContent = 'All Comments';

        const modalCloseButton = document.createElement('button');
        modalCloseButton.type = 'button';
        modalCloseButton.classList.add('btn-close');
        modalCloseButton.setAttribute('data-bs-dismiss', 'modal');
        modalCloseButton.setAttribute('aria-label', 'Close');

        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(modalCloseButton);

        const commentContainer = document.createElement('div');
        commentContainer.classList.add('card', 'comment-container');

        const profileNameList = document.createElement('ul');
        profileNameList.classList.add('profile-name');

        const profileComment = document.createElement('p');
        profileComment.classList.add('text-break', 'profile-comment');

        commentContainer.appendChild(profileNameList);
        commentContainer.appendChild(profileComment);

        const modalBody = document.createElement('div');
        modalBody.classList.add('modal-body');

        const form = document.createElement('form');

        const formGroup = document.createElement('div');
        formGroup.classList.add('mb-3');

        const commentLabel = document.createElement('label');
        commentLabel.setAttribute('for', 'message-text');
        commentLabel.classList.add('col-form-label');
        commentLabel.textContent = 'Your Comment:';

        const commentTextarea = document.createElement('textarea');
        commentTextarea.classList.add('form-control');
        commentTextarea.id = 'message-text';

        formGroup.appendChild(commentLabel);
        formGroup.appendChild(commentTextarea);

        const modalFooter = document.createElement('div');
        modalFooter.classList.add('modal-footer');

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.classList.add('btn', 'btn-secondary');
        closeButton.setAttribute('data-bs-dismiss', 'modal');
        closeButton.textContent = 'Close';

        const submitButton = document.createElement('button');
        submitButton.type = 'submit';
        submitButton.classList.add('btn', 'btn-primary');
        submitButton.textContent = 'Submit Comment';

        modalFooter.appendChild(closeButton);
        modalFooter.appendChild(submitButton);

        form.appendChild(formGroup);

        modalBody.appendChild(form);

        modalContent.appendChild(modalHeader);
        modalContent.appendChild(commentContainer);
        modalContent.appendChild(modalBody);
        modalContent.appendChild(modalFooter);

        modalDialog.appendChild(modalContent);

        commentModal.appendChild(modalDialog);

        // Append all elements to postContainer
        postContainer.appendChild(figure);
        postContainer.appendChild(hr);
        postContainer.appendChild(rowDiv);
        postContainer.appendChild(commentModal);

        // Append postContainer to mainContainer
        mainContainer.appendChild(postContainer);
    });
}

function showLoadingIndicator() {
    const loadingIndicator = document.getElementById('loading_screen');

    loadingIndicator.style.display = "block";
}

function hideLoadingIndicator() {
    const loadingIndicator = document.getElementById('loading_screen');

    loadingIndicator.style.display = "none";
}

function toggleLikeButton(likeImage, likeStatusInput) {
    // Change the like image source and like status input value
    if (likeStatusInput.value === 'dislike') {
        likeImage.src = '../assets/Icon/liked.png'; // Adjust as per your liked icon location
        likeStatusInput.value = 'like';
    } else {
        likeImage.src = '../assets/Icon/like.png'; // Adjust as per your like icon location
        likeStatusInput.value = 'dislike';
    }
}

async function sendLikeStatus(likeStatusInput, postIdInput) {
    try {
        // Extract values from input fields
        const likeStatus = likeStatusInput.value;
        const postID = postIdInput.value;

        // Construct the URL with parameters
        const url = `../controller/likeAction.php?likeStatus=${likeStatus}&postID=${postID}`;

        // Make the GET request
        const response = await fetch(url);

        // Check if the response status is OK
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        // Parse the JSON data from the response
        const postData = await response.json();

        // Log or handle the received data
        console.log(postData);
    } catch (error) {
        // Handle errors, show a user-friendly message or perform other actions
        console.error('Error:', error);
        alert('Something went wrong. Please try again.');
    }
}


getPost(0)
    .then(postData => {
        // Handle postData
        postDetails = postData[0].data;
        console.log(postDetails);
        createPost(postDetails);
    })
    .catch(error => {
        // Handle errors
        console.error('Error in getPostData:', error);
    });
