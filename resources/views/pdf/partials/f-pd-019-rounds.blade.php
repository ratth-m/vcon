{{-- ตารางครั้งที่ติดตั้งของ F-PD-019 --}}
<table class="grid rounds" style="border:1px solid #000; {{ $topBorder ? '' : 'border-top:none;' }}">
    <tr>
        <th rowspan="2" style="width:6%; height:auto;">ครั้งที่</th>
        <th rowspan="2" style="width:9%; height:auto;">ชั้น / โซน</th>
        <th rowspan="2" style="width:8%; height:auto;">พื้นที่</th>
        <th colspan="4" style="height:auto;">วันที่</th>
        <th rowspan="2" style="width:11%; height:auto;">ผลการติดตั้ง</th>
        <th rowspan="2" style="height:auto;">หมายเหตุ</th>
    </tr>
    <tr>
        <th style="width:10%; height:auto;">แผนงานที่<br>อนุมัติ</th>
        <th style="width:10%; height:auto;">แผนงานที่<br>ขอเลื่อน</th>
        <th style="width:10%; height:auto;">แผนงานที่<br>ทำได้</th>
        <th style="width:10%; height:auto;">ยืนยัน 48<br>ชั่วโมง</th>
    </tr>
    @foreach ($list as $i => $r)
        <tr>
            <td class="center" style="vertical-align:top;">{{ $withNumbers || $r ? $start + $i : '' }}</td>
            <td class="center">{{ $r['zone'] ?? '' }}</td>
            <td class="center">{{ $r['area'] ?? '' }}</td>
            @foreach (['approved_date', 'postponed_date', 'actual_date'] as $k)
                <td class="center">{{ ! empty($r[$k]) ? \Illuminate\Support\Carbon::parse($r[$k])->format('d/m/Y') : '' }}</td>
            @endforeach
            <td class="center">{{ $r['confirm_48h'] ?? '' }}</td>
            <td class="res">
                @include('pdf.partials.cb', ['checked' => ($r['done'] ?? null) === true]) เสร็จ<br>
                @include('pdf.partials.cb', ['checked' => ($r['done'] ?? null) === false]) ไม่เสร็จ
            </td>
            <td>{{ $r['remark'] ?? '' }}</td>
        </tr>
    @endforeach
</table>
