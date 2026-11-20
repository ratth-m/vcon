{{-- บล็อกรายละเอียดผลิตภัณฑ์ของ F-SA-005 --}}
@php $d = $d ?? []; @endphp
<div style="border:1px solid #000; border-top:none; padding:6px 6px 2px 6px; height:{{ $h ?? '50mm' }};">
    <table class="det">
        <tr>
            <td style="width:22%;">Span</td>
            <td class="vv" style="width:16%;">{{ $d['span'] ?? '' }}</td>
            <td style="width:8%;">m.</td>
            <td style="width:13%;">ผลิตภัณฑ์</td>
            <td class="vv" style="width:13%;">{{ $d['product'] ?? '' }}</td>
            <td style="width:11%;">Topping</td>
            <td class="vv" style="width:9%;">{{ $d['topping'] ?? '' }}</td>
            <td style="width:8%;">cm.</td>
        </tr>
        <tr>
            <td>Clear Span</td>
            <td class="vv">{{ $d['clear_span'] ?? '' }}</td>
            <td>m.</td>
            <td>Load</td>
            <td class="vv" colspan="3">{{ $d['load'] ?? '' }}</td>
            <td>kg./m<sup>2</sup></td>
        </tr>
        <tr>
            <td>ความกว้างของผลิตภัณฑ์</td>
            <td class="vv">{{ $d['width'] ?? '' }}</td>
            <td>cm.</td>
            <td>เสริมลวด</td>
            <td class="vv" colspan="4">{{ $d['wire'] ?? '' }}</td>
        </tr>
        <tr>
            <td>พื้นที่</td>
            <td class="vv" colspan="7">{{ $d['area'] ?? '' }}</td>
        </tr>
        <tr>
            <td style="vertical-align:top;">อื่นๆ</td>
            <td colspan="7" style="white-space:pre-line; vertical-align:top;">{{ $d['other'] ?? '' }}</td>
        </tr>
    </table>
</div>
<div style="border:1px solid #000; border-top:none; padding:0 6px 2px 6px;">
    <table class="det"><tr>
        <td style="width:70%;"></td>
        <td class="bold" style="width:8%;">รหัส</td>
        <td class="vv">{{ $d['code'] ?? '' }}</td>
    </tr></table>
</div>
