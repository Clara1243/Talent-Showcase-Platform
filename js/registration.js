document.addEventListener("DOMContentLoaded", function () {
    const profileInput = document.getElementById("profile");
    const previewImg = document.getElementById("profile-preview");

    profileInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImg.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
});

document.getElementById('profileForm').addEventListener('submit', function (e) {
    const password = document.getElementById('password').value;

    // pwd at least 8 characters, include uppercase, lowercase, and numbers
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!passwordPattern.test(password)) {
        alert('Password must be at least 8 characters long and include uppercase, lowercase letters, and numbers.');
        e.preventDefault(); 
    }
});

document.getElementById('password').addEventListener('change', function (e) {
    const password = document.getElementById('password').value;

    // pwd at least 8 characters, include uppercase, lowercase, and numbers
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

    if (!passwordPattern.test(password)) {
        document.getElementById('password').className = 'invalid';
    }
    else{
        document.getElementById('password').className = 'valid';
    }
});

