const mainContainer = document.getElementById('main_container');

async function getPost(batchIndex) {
    showLoadingIndicator();
    try {
        const url = `../functions/getPostFromDatabase.php?batch=${batchIndex}`;
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const postData = await response.json();
        hideLoadingIndicator();
        return postData;
    } catch (error) {
        console.error('Error in getPost:', error);
        alert('Something went wrong');
    }
}

function createPostContainer(postData) {
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
                sendLikeStatus(likeStatusInput, postIdInput);
            });

            colDiv.appendChild(likeButton);
        } else if (colClass === 'comment-col') {
            const commentButton = document.createElement('button');
            commentButton.type = 'button';
            commentButton.classList.add('post-action-btn');
            commentButton.setAttribute("data-bs-toggle","modal");
            commentButton.setAttribute("data-bs-target","#comment" + postData.post_id);
            commentButton.setAttribute("data-bs-whatever","@mdo");



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

    // Append all elements to postContainer
    postContainer.appendChild(figure);
    postContainer.appendChild(hr);
    postContainer.appendChild(rowDiv);

    return postContainer;
}

function createCommentModel(postData) {
    // Create modal container
    var modalContainer = document.createElement('div');
    modalContainer.className = 'modal fade';
    modalContainer.id = 'comment' + postData.post_id;
    modalContainer.tabIndex = '-1';
    modalContainer.setAttribute('aria-labelledby', 'commentModalLabel');
    modalContainer.setAttribute('aria-hidden', 'true');

    // Create modal dialog
    var modalDialog = document.createElement('div');
    modalDialog.className = 'modal-dialog';

    // Create modal content
    var modalContent = document.createElement('div');
    modalContent.className = 'modal-content';

    // Create modal header
    var modalHeader = document.createElement('div');
    modalHeader.className = 'modal-header';

    // Create modal title
    var modalTitle = document.createElement('h3');
    modalTitle.className = 'modal-title text-secondary';
    modalTitle.id = 'commentModalLabel';
    modalTitle.textContent = 'All Comments for Post ID ' + postData.post_id;

    // Create close button
    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'btn-close';
    closeButton.setAttribute('data-bs-dismiss', 'modal');
    closeButton.setAttribute('aria-label', 'Close');
    closeButton.id = 'Close_Btn';

    // Append title and close button to the header
    modalHeader.appendChild(modalTitle);
    modalHeader.appendChild(closeButton);

    // Append modal header to modal content
    modalContent.appendChild(modalHeader);

    // Create modal body
    var modalBody = document.createElement('div');
    modalBody.className = 'modal-body';

    // Create comment viewer
    var commentViewer = document.createElement('div');
    commentViewer.className = 'comment text-secondary';

    // Create user info
    var userInfo = document.createElement('div');
    userInfo.className = 'user-info';

    // Create user avatar
    var userAvatar = document.createElement('img');
    userAvatar.src = 'user-avatar.jpg';
    userAvatar.alt = 'User Avatar';

    // Create username
    var username = document.createElement('span');
    username.className = 'username';
    username.textContent = 'JohnDoe';

    // Create comment text
    var commentText = document.createElement('p');
    commentText.className = 'comment-text';
    commentText.textContent = 'This is a great comment! Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

    // Create timestamp
    var timestamp = document.createElement('div');
    timestamp.className = 'timestamp';
    timestamp.textContent = 'Posted 2 hours ago';

    // Append user info, comment text, and timestamp to the comment viewer
    userInfo.appendChild(userAvatar);
    userInfo.appendChild(username);
    commentViewer.appendChild(userInfo);
    commentViewer.appendChild(commentText);
    commentViewer.appendChild(timestamp);

    // Append commentViewer to modal body
    modalBody.appendChild(commentViewer);

    // Create commentForm div
    var commentFormDiv = document.createElement('div');
    commentFormDiv.setAttribute('id', 'comment_form');

    // Create create post form
    var createPostForm = document.createElement('form');
    createPostForm.action = '';
    createPostForm.method = 'post';
    createPostForm.id = 'createComment';

    var formGroup = document.createElement('div');
    formGroup.className = 'mb-3';

    // Create comment input div
    var createCommentDiv = document.createElement('div');
    createCommentDiv.className = 'mb-3';

    // Text field label
    var captionLabel = document.createElement('label');
    captionLabel.htmlFor = 'message-text';
    captionLabel.className = 'col-form-label text-secondary';
    captionLabel.textContent = 'Write a Comment :';

    // Comment textarea
    var commentInput = document.createElement('textarea');
    commentInput.type = 'text';
    commentInput.className = 'form-control';
    commentInput.name = 'comment_text';

    var postID = document.createElement('input');
    postID.type = 'hidden';
    postID.value = postData.post_id;

    createCommentDiv.appendChild(captionLabel);
    createCommentDiv.appendChild(commentInput);
    createCommentDiv.appendChild(postID);

    // Create modal footer
    var modalFooter = document.createElement('div');
    modalFooter.className = 'modal-footer';

    // Create cancel button
    var cancelButton = document.createElement('button');
    cancelButton.type = 'button';
    cancelButton.className = 'btn btn-secondary';
    cancelButton.setAttribute('data-bs-dismiss', 'modal');
    cancelButton.textContent = 'Cancel';

    // Create submit button
    var submitButton = document.createElement('input');
    submitButton.type = 'submit';
    submitButton.className = 'btn btn-primary';
    submitButton.setAttribute('id', 'comment_submit');
    submitButton.textContent = 'Submit Post';

    modalFooter.appendChild(cancelButton);
    modalFooter.appendChild(submitButton);

    formGroup.appendChild(createCommentDiv);
    formGroup.appendChild(modalFooter);

    // Append formGroup to modal body
    modalBody.appendChild(formGroup);

    // Append commentFormDiv to modal body
    modalBody.appendChild(commentFormDiv);

    // Append modal body to modal content
    modalContent.appendChild(modalBody);

    // Append modal content to modal dialog
    modalDialog.appendChild(modalContent);

    // Append modal dialog to modal container
    modalContainer.appendChild(modalDialog);

    submitButton.addEventListener('click', function() {
        const commentText = commentInput.value;
        const post_id = postID.value;
        
        sendComment(commentText,post_id);
    })

    return modalContainer;
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

async function sendComment(commentText, post_id) {
    try {
        // Construct the URL with query parameters
        const url = `../controller/commentAction.php?comment=${encodeURIComponent(commentText)}&id=${encodeURIComponent(post_id)}`;

        // Make the GET request
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', // Adjust the content type if needed
            },
        });

        // Check if the response status is OK
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Parse the JSON data from the response
        const responseData = await response.json();

        // Log or handle the received data
        console.log(responseData);
    } catch (error) {
        // Handle errors, show a user-friendly message or perform other actions
        console.error('Error in comment:', error);
        alert('Something went wrong. Please try again.');
    }
}


getPost(0)
    .then(postData => {
        const postDetails = postData[0].data;
        console.log(postDetails);

        postDetails.forEach(postDetails => {
            // Create post container
            const postContainer = createPostContainer(postDetails);

            // Create comment modal
            const commentModal = createCommentModel(postDetails);

            // Append postContainer and commentModal to mainContainer
            mainContainer.appendChild(postContainer);
            mainContainer.appendChild(commentModal);
        })
    })
    .catch(error => {
        console.error('Error in getPostData:', error);
    });
