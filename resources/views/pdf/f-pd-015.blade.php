{{--
    F-PD-015 ใบส่งงาน (ฉบับที่ 1) — ส่วนที่ 1 + ส่วนที่ 2

    $data = [
        'page' => '', 'pages' => '',
        'hollow_core' => bool, 'other_product' => '',     // ☐ พื้นคอนกรีตรูกลวง ☐ ............
        'doc_no' => '', 'date' => null, 'customer_name' => '', 'attention' => '',
        'project_name' => '', 'location' => "บรรทัด 1\nบรรทัด 2",
        'total' => '',                                  // ยอดรวม (ส่วนที่ 1)
        'detail_page_from' => '', 'detail_page_to' => '',
        'has_drawing' => bool, 'drawing_sheets' => '', 'other_doc' => '',
        'remark' => "หลายบรรทัด",                        // หมายเหตุ (ส่วนที่ 2)
        'prepared_by' => '', 'prepared_date' => null, 'checked_by' => '', 'checked_date' => null,
        'customer_approver' => '', 'customer_approver_date' => null,
        'customer_signer2' => '', 'customer_signer2_date' => null,
    ];
    // ส่วนที่ 1
    $items = [
        ['code' => '', 'thickness' => '', 'design_load' => '', 'strand' => '', 'qty' => '', 'unit' => ''],
    ];
    // ส่วนที่ 2 (ไม่ส่งมา = แสดงฟอร์มเปล่า, ส่ง false = ไม่แสดงส่วนที่ 2)
    $details = [
        // ซ้าย (1) และ ขวา (2)
        ['code' => '', 'thickness' => '', 'design_load' => '', 'strand' => '', 'unit' => 'ม2',
         'total' => '', 'rows' => [['code' => '', 'size' => '', 'qty' => '', 'amount' => '']]],
        [...],
    ];
    'grand_total' => ''  // ใส่ใน $data: ยอดรวม (1)+(2)
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-015 ฉบับที่ : 1')
@section('title', 'F-PD-015 ใบส่งงาน')
@section('font-size', '15')
@section('row-height', '23px')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $rows = $items->pad(max(19, $items->count()), null);
    $details = $details ?? [];
    $showPart2 = $details !== false;
    $details = collect($details ?: [])->pad(2, [])->values();
    $remarkLines = collect(preg_split('/\r\n|\r|\n/', (string) ($data['remark'] ?? '')))->filter(fn ($l) => $l !== '')->values();
    $remarkFirst = $remarkLines->shift() ?? '';
    $locationLines = collect(preg_split('/\r\n|\r|\n/', (string) ($data['location'] ?? '')))->pad(2, '');
@endphp

@section('styles')
    .hdr td { vertical-align: bottom; }
    .row-h td { font-size: 12.5pt; }
    .total-line { border-bottom: 3px double #000; display: inline-block; width: 22mm; text-align: center; }
@endsection

@section('content')
{{-- ===================== ส่วนที่ 1 ===================== --}}
<table style="border:1px solid #000;" class="hdr">
    <tr><td colspan="4" class="title" style="padding-top:14px;">ใบส่งงาน</td></tr>
    <tr>
        <td class="bold" style="width:28%;">บริษัท วงศ์ชัย จำกัด</td>
        <td style="width:24%;">@include('pdf.partials.cb', ['checked' => ! empty($data['hollow_core'])]) พื้นคอนกรีตรูกลวง</td>
        <td style="width:28%;">
            <table class="ln"><tr><td class="l">@include('pdf.partials.cb', ['checked' => ! empty($data['other_product'])])</td><td class="v">{{ $data['other_product'] ?? '' }}</td></tr></table>
        </td>
        <td class="right bold">หน้าที่ <span class="normal">{{ $data['page'] ?? '.....' }} / {{ $data['pages'] ?? '......' }}</span></td>
    </tr>
</table>
<table style="border:1px solid #000; border-top:none;">
    <tr>
        <td style="width:50%; border-right:1px solid #000; padding:12px 6px 2px 6px; vertical-align:top;">
            <table class="ln"><tr>
                <td class="l bold">เลขที่</td><td class="vc">{{ $data['doc_no'] ?? '' }}</td>
                <td class="l bold">&nbsp; วันที่</td><td class="l">@include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
            </tr></table>
            <table class="ln"><tr><td class="l bold">ชื่อลูกค้า</td><td class="v">{{ $data['customer_name'] ?? '' }}</td></tr></table>
            <table class="ln"><tr><td class="l bold">เรียน</td><td class="v">{{ $data['attention'] ?? '' }}</td></tr></table>
        </td>
        <td style="width:50%; padding:12px 6px 2px 6px; vertical-align:top;">
            <table class="ln"><tr><td class="l bold">โครงการ</td><td class="v">{{ $data['project_name'] ?? '' }}</td></tr></table>
            <table class="ln"><tr><td class="l bold">สถานที่</td><td class="v">{{ $locationLines[0] }}</td></tr></table>
            <div class="line">{{ $locationLines[1] }}&nbsp;</div>
        </td>
    </tr>
</table>
<table class="grid" style="border:1px solid #000; border-top:none;">
    <tr>
        <th style="width:12%; border-top:none;">รหัสสินค้า</th>
        <th style="width:11%; border-top:none;">ความหนา<br>(ซม.)</th>
        <th style="width:22%; border-top:none;">น้ำหนักออกแบบ<br>(กก. / ม.<sup>2</sup>)</th>
        <th style="width:31%; border-top:none;">จำนวน / ขนาด<br><span class="small">Strand / Wire</span></th>
        <th style="width:10%; border-top:none;">จำนวน<br>รวม</th>
        <th style="width:10%; border-top:none;">หน่วย</th>
    </tr>
    @foreach ($rows as $row)
        <tr class="row-h">
            <td class="center">{{ $row['code'] ?? '' }}</td>
            <td class="center">{{ $row['thickness'] ?? '' }}</td>
            <td class="center">{{ $row['design_load'] ?? '' }}</td>
            <td class="center">{{ $row['strand'] ?? '' }}</td>
            <td class="center">{{ $row['qty'] ?? '' }}</td>
            <td class="center">{{ $row['unit'] ?? '' }}</td>
        </tr>
    @endforeach
</table>
<table>
    <tr>
        <td style="width:72%;" class="right">ยอดรวม</td>
        <td style="padding-left:8mm;"><span class="total-line">{{ $data['total'] ?? '' }}&nbsp;</span></td>
    </tr>
</table>
<table class="ln" style="width:auto; margin-left:6mm;"><tr>
    <td class="l">รายละเอียดของผลิตภัณฑ์แต่ละชนิดให้ดูในหน้า</td><td class="vc" style="width:14mm;">{{ $data['detail_page_from'] ?? '' }}</td>
    <td class="l">ถึงหน้า</td><td class="vc" style="width:14mm;">{{ $data['detail_page_to'] ?? '' }}</td>
</tr></table>
<table class="ln" style="margin:2px 0 6px 6mm; width:97%;"><tr>
    <td class="l">เอกสารประกอบ &nbsp;&nbsp; @include('pdf.partials.cb', ['checked' => ! empty($data['has_drawing'])]) แบบแปลนจำนวน</td>
    <td class="vc" style="width:14mm;">{{ $data['drawing_sheets'] ?? '' }}</td>
    <td class="l">แผ่น &nbsp;&nbsp; @include('pdf.partials.cb', ['checked' => ! empty($data['other_doc'])])</td>
    <td class="v">{{ $data['other_doc'] ?? '' }}</td>
</tr></table>

@include('pdf.partials.f-pd-015-sign')

{{-- ===================== ส่วนที่ 2 ===================== --}}
@if ($showPart2)
    <div class="page-break"></div>
    <div class="right small">(ส่วนที่ 2)</div>
    <div style="border:1px solid #000; padding:10px 8px 2px 8px;">
        <table class="ln"><tr>
            <td class="l bold">เลขที่</td><td class="vc" style="width:26mm;">{{ $data['doc_no'] ?? '' }}</td>
            <td class="l bold">&nbsp; วันที่</td><td class="l">@include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
            <td></td>
            <td class="l bold">หน้าที่</td><td class="l">{{ $data['page'] ?? '.....' }} / {{ $data['pages'] ?? '......' }}</td>
        </tr></table>
        <table class="ln" style="width:60%;"><tr><td class="l bold">โครงการ</td><td class="v">{{ $data['project_name'] ?? '' }}</td></tr></table>
    </div>
    <table style="border:1px solid #000; border-top:none;">
        <tr>
            @foreach ($details as $side => $g)
                <td style="width:48%; padding:2px 4px; vertical-align:top; {{ $side === 0 ? 'border-right:1px solid #000;' : 'border-left:1px solid #000;' }}">
                    <table class="small">
                        <tr>
                            <td class="center" style="padding:0;">รหัสสินค้า</td>
                            <td class="center" style="padding:0;">ความหนา</td>
                            <td class="center" style="padding:0;">น้ำหนักออกแบบ<br>(กก. / ม.<sup>2</sup>)</td>
                            <td class="center" style="padding:0;">จำนวน / ขนาด<br>(wire / strand)</td>
                        </tr>
                        <tr>
                            <td class="center" style="border-bottom:0.8px dotted #000;">{{ $g['code'] ?? '' }}&nbsp;</td>
                            <td class="center" style="border-bottom:0.8px dotted #000;">{{ $g['thickness'] ?? '' }}</td>
                            <td class="center" style="border-bottom:0.8px dotted #000;">{{ $g['design_load'] ?? '' }}</td>
                            <td class="center" style="border-bottom:0.8px dotted #000;">{{ $g['strand'] ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                @if ($side === 0)
                    <td style="width:4%;"></td>
                @endif
            @endforeach
        </tr>
    </table>

    <table style="margin-top:8px;">
        <tr>
            @foreach ($details as $side => $g)
                @php $gRows = collect($g['rows'] ?? [])->pad(max(17, count($g['rows'] ?? [])), null); @endphp
                <td style="width:48%; padding:0; vertical-align:top;">
                    <table class="grid">
                        <tr>
                            <th style="width:20%;">code</th>
                            <th style="width:44%;">ขนาด (เมตร)</th>
                            <th style="width:18%;">จำนวน<br><span class="xsmall">(แผ่น /ตัว)</span></th>
                            <th style="width:18%;">ปริมาณ<br>( {{ $g['unit'] ?? str_repeat(' ', 6) }} )</th>
                        </tr>
                        @foreach ($gRows as $r)
                            <tr class="row-h">
                                <td class="center">{{ $r['code'] ?? '' }}</td>
                                <td class="center">{{ $r['size'] ?? '' }}</td>
                                <td class="center">{{ $r['qty'] ?? '' }}</td>
                                <td class="center">{{ $r['amount'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <table class="ln" style="width:60%; margin-left:auto; margin-right:{{ $side === 0 ? '20%' : '0' }};"><tr>
                        <td class="l">ยอดรวม ({{ $side + 1 }})</td><td class="vc">{{ $g['total'] ?? '' }}</td>
                    </tr></table>
                </td>
                @if ($side === 0)
                    <td style="width:4%;"></td>
                @endif
            @endforeach
        </tr>
    </table>
    <div class="right">ยอดรวม (1)+ (2) <span class="total-line">{{ $data['grand_total'] ?? '' }}&nbsp;</span></div>

    <table class="ln"><tr><td class="l">หมายเหตุ :</td><td class="v">{{ $remarkFirst }}</td></tr></table>
    <div class="line">{{ $remarkLines->implode(' ') }}&nbsp;</div>
    <div class="small" style="padding-left:6mm; margin-bottom:2px;">กรณีที่ลูกค้ารับของที่โรงงานหรือติดตั้งเอง ให้ระบุเลขที่ใบส่งของในช่องหมายเหตุ</div>

    @include('pdf.partials.f-pd-015-sign')
@endif
@endsection
