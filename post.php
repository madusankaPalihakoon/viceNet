async function createCommentModal() {
    // Create modal structure
    const modal = $('<div class="modal fade" id="commentModel" tabindex="-1" role="dialog" aria-hidden="true"></div>');
    const modalDialog = $('<div class="modal-dialog modal-dialog-centered" role="document"></div>');
    const modalContent = $('<div class="modal-content"></div>');

    // Modal header
    const modalHeader = $('<div class="modal-header"></div>');
    modalHeader.append('<h6 class="modal-title" id="exampleModalLongTitle">All Comments</h6>');
    modalHeader.append('<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background-color: transparent; border:none;"><span aria-hidden="true">&times;</span></button>');

    // Modal body
    const modalBody = $('<div class="modal-body"></div>');
    const loadCommentsContainer = $('<div id="loadComments" class="col friends-info" style="border: 1px solid gray; border-radius: 0.5rem"></div>');
    const smProfile = $('<div class="col sm-profile"></div>');
    smProfile.append('<img class="profile-sm-pic" src="https://via.placeholder.com/30/30" alt="...">');
    smProfile.append('<span><a class="comment-pro-name" href="">sanjaya madusanka</a></span>');
    loadCommentsContainer.append(smProfile);
    loadCommentsContainer.append('<p id="comments"> comment </p>');
    loadCommentsContainer.append('<hr>');
    const commentForm = $('<form></form>');
    commentForm.append('<div class="form-group"><input type="text" class="form-control" id="comment-text" placeholder="Enter Your Comment Here"></div>');
    modalBody.append(loadCommentsContainer, commentForm);

    // Modal footer
    const modalFooter = $('<div class="modal-footer"></div>');
    modalFooter.append('<button type="button" class="btn btn-secondary" data-dismiss="modal" id="comment-close-btn">Close</button>');
    modalFooter.append('<button type="submit" id="commentSubmit" class="btn btn-primary">Submit Comment</button>');
    modalFooter.append('<input type="hidden" name="">');

    // Assemble modal
    modalContent.append(modalHeader, modalBody, modalFooter);
    modalDialog.append(modalContent);
    modal.append(modalDialog);

    // Append modal to the body
    $('body').append(modal);
}