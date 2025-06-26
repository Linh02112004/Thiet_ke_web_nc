<h3>Thông tin chi tiết</h3>
<p><strong>Mô tả:</strong> {!! nl2br(e($event->description)) !!}</p>
<p><strong>Tổ chức:</strong> {{ $event->organizer }}</p>
<p><strong>Tên người phụ trách:</strong> {{ $event->organizer_name }}</p>
<p><strong>Số điện thoại:</strong> {{ $event->phone }}</p>
<p><strong>Địa điểm sự kiện:</strong> {{ $event->location }}</p>
<p><strong>Mục tiêu quyên góp:</strong> {{ number_format($event->goal) }} VND</p>
<p><strong>Số tiền đã quyên góp:</strong> {{ number_format($event->amount_raised) }} VND</p>
