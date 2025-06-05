document.addEventListener("DOMContentLoaded", function () {
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    const loginBtn = document.getElementById("loginBtn");
    const registerBtn = document.getElementById("registerBtn");

    loginBtn.addEventListener("click", function () {
        loginModal.style.display = "block";
    });
    registerBtn.addEventListener("click", function () {
        registerModal.style.display = "block";
        updateRegisterFields();
    });

    window.addEventListener("click", function (event) {
        if (event.target === loginModal || event.target === registerModal) {
            closeModal();
        }
    });
});

function closeModal(id) {
    document.getElementById(id).style.display = "none";
}

function updateLoginFields() {
    let role = document.getElementById("loginRole").value;
    let loginFields = document.getElementById("loginFields");
    if (role === "donor") {
        loginFields.innerHTML = `
        <input type="text" name="loginIdentity" placeholder="Số điện thoại hoặc Email" required>
        <input type="password" name="loginPassword" placeholder="Mật khẩu" required>`;
    } else {
        loginFields.innerHTML = `
        <input type="email" name="loginIdentity" placeholder="Email" required>
        <input type="password" name="loginPassword" placeholder="Mật khẩu" required>`;
    }
}

function updateRegisterFields() {
    let role = document.getElementById("registerRole").value;
    let registerFields = document.getElementById("registerFields");
    if (role === "organization") {
        registerFields.innerHTML = `
        <input type="text" name="orgName" placeholder="Tên tổ chức" required>
        <input type="email" name="registerEmail" placeholder="Email" required>
        <input type="password" name="registerPassword" placeholder="Mật khẩu" required>`;
    } else {
        registerFields.innerHTML = `
        <input type="text" name="fullName" placeholder="Họ và Tên" required>
        <input type="tel" name="phone" placeholder="Số điện thoại" required>
        <input type="email" name="registerEmail" placeholder="Email" required>
        <input type="password" name="registerPassword" placeholder="Mật khẩu" required>`;
    }
}
