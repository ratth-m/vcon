{{--
    F-SA-005 ใบรายละเอียดผลิตภัณฑ์ (ฉบับที่ 4)
    หน้า 1 = ข้อมูลสัญญา + รายละเอียด 2 รายการ, หน้า 2 ขึ้นไป = รายละเอียดหน้าละ 3 รายการ

    $data = [
        'factory' => '',                                   // โรงงาน วงศ์ชัย :
        'doc_type' => 'contract' | 'po',                   // ☐ สัญญา ☐ เอกสารยืนยันการสั่งซื้อ
        'doc_no' => '', 'doc_date' => null,
        'customer_name' => '', 'project_name' => '', 'contact_name' => '', 'phone' => '',
        'usage_plan' => '',
        'attachments' => ['contract', 'estimate', 'map', 'drawing', 'other'],   // เอกสารประกอบที่ติ๊ก
        'transport_by' => 'company' | 'customer',
        'install_by' => 'company' | 'customer' | 'other',
        'billing' => '',                                   // วางบิล - รับเช็ค
        'sales_rep' => '', 'manager' => '', 'sales_manager' => '', 'operation_manager' => '',
    ];
    $details = [
        ['span' => '', 'product' => '', 'topping' => '', 'clear_span' => '', 'load' => '',
         'width' => '', 'wire' => '', 'area' => '', 'other' => '', 'code' => ''],
    ];
    $showPage2 = true;   // แสดงหน้า 2 แม้รายละเอียดไม่เกิน 2 รายการ
--}}
@extends('pdf.layout')

@section('form-code', 'F-SA-005 ฉบับที่ : 4')
@section('title', 'F-SA-005 ใบรายละเอียดผลิตภัณฑ์')
@section('font-size', '14')

@php
    $data = $data ?? [];
    $details = collect($details ?? [])->values();
    $firstDetails = $details->take(2)->pad(2, []);
    $rest = $details->slice(2)->values();
    $restPages = $rest->isEmpty()
        ? (($showPage2 ?? true) ? collect([collect()]) : collect())
        : $rest->chunk(3);
    $att = $data['attachments'] ?? [];
    $cb = fn ($checked) => view('pdf.partials.cb', ['checked' => $checked])->render();
    $note = 'ในกรณีที่ความยาว Clear Span ยาวกว่าที่ระบุไว้ในรายละเอียด ให้แจ้งมายังฝ่ายขายสำนักงานใหญ่ เพื่อแก้ไขและจัดทำรายละเอียดเพิ่มเติมลงไปใหม่';
@endphp

@section('styles')
    .det td { padding: 1px 2px; vertical-align: bottom; }
    .det td.vv { border-bottom: 0.8px dotted #000; }
    .info td { padding: 1px 2px; vertical-align: bottom; }
    .info td.vv { border-bottom: 0.8px dotted #000; }
@endsection

@section('content')
{{-- ===================== หน้า 1 ===================== --}}
<div style="border:1.5px solid #000; padding:2px 6px;">
    <table>
        <tr>
            <td style="width:25%;"></td>
            <td class="title" style="width:50%;">ใบรายละเอียดผลิตภัณฑ์</td>
            <td class="right bold large" style="width:25%;">หน้าที่ 1</td>
        </tr>
    </table>
    <table class="info" style="margin-top:6px;">
        <tr>
            <td class="bold large" style="width:55%;">บริษัท วงศ์ชัย จำกัด</td>
            <td class="bold large" style="width:22%;">โรงงาน วงศ์ชัย :</td>
            <td>{{ $data['factory'] ?? '' }}</td>
        </tr>
    </table>
</div>
<div style="border:1.5px solid #000; border-top:none; padding:2px 6px 6px 6px;">
    <table class="info">
        <tr>
            <td style="width:23%;">{!! $cb(($data['doc_type'] ?? null) === 'contract') !!} สัญญา</td>
            <td style="width:30%;">{!! $cb(($data['doc_type'] ?? null) === 'po') !!} เอกสารยืนยันการสั่งซื้อ</td>
            <td style="width:7%;">เลขที่</td>
            <td class="vv" style="width:14%;">{{ $data['doc_no'] ?? '' }}</td>
            <td style="width:9%;">&nbsp;ลงวันที่</td>
            <td class="vv">{{ ! empty($data['doc_date']) ? \Illuminate\Support\Carbon::parse($data['doc_date'])->format('d/m/Y') : '' }}</td>
        </tr>
    </table>
    <table class="info">
        <tr>
            <td style="width:16%;">ชื่อลูกค้า</td><td class="vv" style="width:30%;">{{ $data['customer_name'] ?? '' }}</td>
            <td style="width:12%;">&nbsp;โครงการ</td><td class="vv">{{ $data['project_name'] ?? '' }}</td>
        </tr>
        <tr>
            <td>ผู้ติดต่อ</td><td class="vv">{{ $data['contact_name'] ?? '' }}</td>
            <td>&nbsp;โทรศัพท์</td><td class="vv">{{ $data['phone'] ?? '' }}</td>
        </tr>
        <tr>
            <td>แผนการใช้</td><td class="vv" colspan="3">{{ $data['usage_plan'] ?? '' }}</td>
        </tr>
    </table>
    <table class="info">
        <tr>
            <td style="width:16%;">เอกสารประกอบ</td>
            <td style="width:22%;">{!! $cb(in_array('contract', $att)) !!} สัญญา / เสนอราคา</td>
            <td style="width:27%;">{!! $cb(in_array('estimate', $att)) !!} ใบประเมินขนส่ง / ติดตั้ง</td>
            <td style="width:12%;">{!! $cb(in_array('map', $att)) !!} แผนที่</td>
            <td style="width:11%;">{!! $cb(in_array('drawing', $att)) !!} แบบ</td>
            <td>{!! $cb(in_array('other', $att)) !!} อื่นๆ</td>
        </tr>
    </table>
    <div class="bold">ข้อตกลงกับผู้ซื้อ</div>
    <table class="info">
        <tr><td style="width:16%;">ขนส่งโดย</td><td>{!! $cb(($data['transport_by'] ?? null) === 'company') !!} บริษัท</td></tr>
        <tr><td></td><td>{!! $cb(($data['transport_by'] ?? null) === 'customer') !!} ลูกค้า</td></tr>
        <tr><td>ติดตั้งโดย</td><td>{!! $cb(($data['install_by'] ?? null) === 'company') !!} บริษัท</td></tr>
        <tr><td></td><td>{!! $cb(($data['install_by'] ?? null) === 'customer') !!} ลูกค้า</td></tr>
        <tr><td></td><td>{!! $cb(($data['install_by'] ?? null) === 'other') !!} อื่นๆ</td></tr>
    </table>
    <table class="info" style="margin-top:4px;">
        <tr><td style="width:16%;">วางบิล - รับเช็ค</td><td class="vv">{{ $data['billing'] ?? '' }}</td></tr>
    </table>
    <table class="info" style="margin-top:4px;">
        <tr>
            <td style="width:11%;">ผู้แทนขาย</td><td class="vv" style="width:20%;">{{ $data['sales_rep'] ?? '' }}</td>
            <td style="width:10%;">&nbsp;ผู้จัดการ</td><td class="vv" style="width:20%;">{{ $data['manager'] ?? '' }}</td>
            <td style="width:17%;">&nbsp;ผู้จัดการปฏิบัติการ</td><td class="vv">{{ $data['operation_manager'] ?? '' }}</td>
        </tr>
    </table>
</div>
<div class="bold center large" style="border:1.5px solid #000; border-top:none; padding:2px;">รายละเอียด</div>
@foreach ($firstDetails as $d)
    @include('pdf.partials.f-sa-005-detail', ['d' => $d, 'h' => '44mm'])
@endforeach
<div style="margin-top:4px;"><span class="bold">หมายเหตุ</span> &nbsp;{{ $note }} <span class="bold">ห้าม : ทำรายละเอียดเองโดยเด็ดขาด</span></div>

{{-- ===================== หน้า 2+ ===================== --}}
@foreach ($restPages as $p => $chunk)
    <div class="page-break"></div>
    <div style="border:1px solid #000; padding:0 6px;">
        <table>
            <tr>
                <td style="width:25%;"></td>
                <td class="bold center large" style="width:50%;">ใบรายละเอียดผลิตภัณฑ์</td>
                <td class="right bold large" style="width:25%;">หน้า {{ $p + 2 }}</td>
            </tr>
        </table>
        <table class="info">
            <tr>
                <td class="bold" style="width:55%;">บริษัท วงศ์ชัย จำกัด</td>
                <td class="bold" style="width:20%;">โรงงาน วงศ์ชัย :</td>
                <td>{{ $data['factory'] ?? '' }}</td>
            </tr>
        </table>
    </div>
    <div style="border:1px solid #000; border-top:none; padding:0 6px 2px 6px;">
        <table class="info">
            <tr>
                <td style="width:12%;">ชื่อลูกค้า</td><td class="vv" style="width:30%;">{{ $data['customer_name'] ?? '' }}</td>
                <td style="width:10%;">&nbsp;โครงการ</td><td class="vv" colspan="3">{{ $data['project_name'] ?? '' }}</td>
            </tr>
            <tr>
                <td>ผู้แทนขาย</td><td class="vv">{{ $data['sales_rep'] ?? '' }}</td>
                <td colspan="4" style="padding:0;">
                    <table class="info"><tr>
                        <td style="width:24%;">&nbsp;ผู้จัดการฝ่ายขาย</td><td class="vv" style="width:26%;">{{ $data['sales_manager'] ?? '' }}</td>
                        <td style="width:26%;">&nbsp;ผู้จัดการปฏิบัติการ</td><td class="vv">{{ $data['operation_manager'] ?? '' }}</td>
                    </tr></table>
                </td>
            </tr>
        </table>
    </div>
    <div class="bold center" style="border:1px solid #000; border-top:none; padding:1px;">รายละเอียด</div>
    @foreach (collect($chunk)->values()->pad(3, []) as $d)
        @include('pdf.partials.f-sa-005-detail', ['d' => $d, 'h' => '56mm'])
    @endforeach
    <div class="small" style="margin-top:2px;"><span class="bold">หมายเหตุ :</span> {{ $note }} <span class="bold">ห้าม ! ทำรายละเอียดเองโดยเด็ดขาด</span></div>
@endforeach
@endsection
