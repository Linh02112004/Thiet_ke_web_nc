<!-- Pop-up Thay đổi mật khẩu -->
    <div id="changePasswordModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal('changePasswordModal')">&times;</span>
            <h1>Thay đổi mật khẩu</h1>
            <form action="changePassword.php" method="POST">
                <div class="form-container">
                    <!-- Mật khẩu -->
                    <div class="form-section">
                        <label for="current_password">Mật khẩu hiện tại:</label>
                        <input type="password" id="current_password" name="current_password" required>

                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit">Thay đổi mật khẩu</button>
            </form>
        </div>
    </div>