
function togglePasswordVisibility(elementId) {
    var passwordInput = document.getElementById(elementId);
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}
