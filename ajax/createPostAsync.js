async function createPost() {
    const form = document.getElementById('createPost');

    if (!form) {
        console.error('Form with ID "createPost" not found.');
        return;
    }

    try {
        const formData = new FormData(form);

        const response = await fetch('../controller/createPostAction', {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Something went wrong, please try again later');
        }

        const data = await response.json();
        handlePostSuccess(data);
    } catch (error) {
        handlePostError(error);
    }
}

function handlePostSuccess(data) {
    console.log(data);
    closePostModel();
}

function handlePostError(error) {
    console.error(error);
    alert('Something went wrong, please try again later');
}

function closePostModel() {
    const button = document.getElementById('Close_Btn');

    if (button) {
        button.click();
    } else {
        console.warn('Button with ID "Close_Btn" not found.');
    }
}

// Get a reference to the create post button element
var createPostBtn = document.getElementById('create_post_btn');

createPostBtn.addEventListener("click", function() {
    createPost();
  });