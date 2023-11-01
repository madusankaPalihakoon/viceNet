{/* <script>
document.addEventListener("DOMContentLoaded", function () {
    var form = document.querySelector(".login_form");

    // Attach a submit event listener to the form
    form.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission

        // Collect form data
        var formData = new FormData(this);

        // Send the data to the server using AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../controller/profileSetupAction", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the server's response (if needed)
                console.log(xhr.responseText);

                // Check for a successful response (modify this condition as needed)
                if (xhr.responseText === "Data saved successfully") {
                    // Reset the form after successful submission
                    alert('hari');
                    form.reset();
                }
            }
        };
        xhr.send(formData);
    });
});
</script> */}