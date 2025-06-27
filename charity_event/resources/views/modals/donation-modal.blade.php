<div id="donationModal" class="modal" style="display: none;"
    data-account="{{ $event->bank_account }}"
    data-bank-code="{{ $bankCode }}"
    data-event-id="{{ $event->id }}"
    data-confirm-route="{{ route('donor.confirmDonation') }}"
    data-csrf="{{ csrf_token() }}">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Nhập số tiền muốn quyên góp</h2>
        <div class="donation-input-group">
            <input type="number" id="donationAmount" placeholder="Nhập số tiền (VNĐ)" min="1000">
            <button class="btn btn-donate" onclick="generateVietQR()">Tạo QR</button>
        </div>
        <br>
        <div class="qr-container">
            <img id="qrcode" />
        </div>
        <button id="confirmBtn" class="btn btn-donate" onclick="confirmDonation()" style="display: none;">Xác nhận</button>
    </div>
</div>