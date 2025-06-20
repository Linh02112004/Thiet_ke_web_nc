<!-- Pop-up Tạo sự kiện -->
    <div id="createEventModal" class="modal" style="display: none;">
        <div class="modal-content">
        <span class="close">&times;</span>
            <h1>Tạo sự kiện</h1>
            <form action="{{ route('organization.createEvent') }}" method="POST">
                @csrf
                <div class="form-container">
                    <!-- Thông tin Sự kiện -->
                    <div class="form-section">
                        <h2>Thông tin Sự kiện</h2>
                        <label for="event_name">Tên sự kiện:</label>
                        <input type="text" id="event_name" name="event_name" required>

                        <label for="location">Địa điểm hỗ trợ:</label>
                        <input type="text" id="location" name="location" required>

                        <label for="goal">Mục tiêu quyên góp:</label>
                        <input type="number" id="goal" name="goal" required>
                        
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>

                    <!-- Thông tin Người phụ trách -->
                    <div class="form-section">
                        <h2>Thông tin Người phụ trách</h2>
                        <label for="organizer_name">Họ tên:</label>
                        <input type="text" id="organizer_name" name="organizer_name" required>

                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" id="phone" name="phone" required>

                        <label for="bank_account">Số tài khoản:</label>
                        <input type="text" id="bank_account" name="bank_account" required>

                        <label for="bank_name">Ngân hàng thụ hưởng:</label>
                        <select id="bank_name" name="bank_name" required>
                            <?php 
                            $bank_codes = [
                                "BIDV" => "BIDV",
                                "Vietcombank" => "VCB",
                                "Techcombank" => "TCB",
                                "Agribank" => "VBA",
                                "ACB" => "ACB",
                                "MB Bank" => "MB",
                                "VPBank" => "VPB"
                            ];
                            $selected_bank = $user['bank_name'] ?? '';
                            foreach ($bank_codes as $name => $code): 
                            ?>
                                <option value="<?= htmlspecialchars($name) ?>" <?= ($selected_bank == $name) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button type="submit">Tạo Sự kiện</button>
            </form>
        </div>
    </div>