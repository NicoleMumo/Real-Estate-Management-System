document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('registrationForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'server.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                
                if (response.status === 'success') {
                    // Show the pop-up message
                    var successPopup = document.createElement('div');
                    successPopup.innerHTML = '<div class="popup"><span>&#10003;</span> ' + response.message + '</div>';
                    successPopup.style.cssText = 'position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); background-color: #0B2A3B; color: white; padding: 20px; font-size: 18px; border-radius: 10px; z-index: 1000; text-align: center;';
                    document.body.appendChild(successPopup);

                    // Hide the pop-up after 2 seconds
                    setTimeout(function() {
                        successPopup.style.display = 'none';
                    }, 2000);
                } else {
                    alert(response.message);
                }
            } else {
                alert('An error occurred while processing your request.');
            }
        };

        xhr.send(formData);
    });
});
