<!-- Pop-up Đăng nhập -->
<div id="loginModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('loginModal')">&times;</span>
        <h2>Đăng nhập</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <select name="loginRole" id="loginRole" onchange="updateLoginFields()">
                <option value="admin">Quản trị viên</option>
                <option value="organization">Tổ chức</option>
                <option value="donor">Người quyên góp</option>
            </select>
            <div id="loginFields">
                <input type="email" name="loginIdentity" placeholder="Email" required>
                <input type="password" name="loginPassword" placeholder="Mật khẩu" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</div>

<!-- Pop-up Đăng ký -->
<div id="registerModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('registerModal')">&times;</span>
        <h2>Đăng ký</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <select name="registerRole" id="registerRole" onchange="updateRegisterFields()">
                <option value="organization">Tổ chức</option>
                <option value="donor">Người quyên góp</option>
            </select>
            <div id="registerFields"></div>
            <button type="submit">Đăng ký</button>
        </form>
    </div>
</div>
