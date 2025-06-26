<h3>Danh sách quyên góp</h3>
<table border="1">
    <tr>
        <th>STT</th>
        <th>Họ và Tên</th>
        <th>Số tiền</th>
        <th>Thời gian</th>
    </tr>
    @foreach ($donations as $index => $donation)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $donation->donor_name }}</td>
            <td>{{ number_format($donation->amount) }} VND</td>
            <td>{{ $donation->donated_at }}</td>
        </tr>
    @endforeach
</table>