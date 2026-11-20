{{--
    F-PD-006 ใบขออนุมัติ Shop Drawing (ฉบับที่ 2)

    $data = [
        'doc_no' => '', 'date' => null,
        'project_name' => '', 'shop_drawing_no' => '', 'customer_name' => '', 'attention' => '',
        'purpose' => 'comment' | 'approval' | 'info' | 'other', 'purpose_other' => '',
        'attachments' => "หลายบรรทัด\nขึ้นบรรทัดใหม่ได้",
        'prepared_by' => '', 'prepared_date' => null, 'approved_by' => '', 'approved_date' => null,
        // ส่วนของลูกค้า
        'result' => 'approved' | 'approved_with_remark' | 'rejected' | 'other', 'result_other' => '',
        'customer_remark' => '', 'customer_signer' => '', 'customer_date' => null,
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-006 ฉบับที่ : 2')
@section('title', 'F-PD-006 ใบขออนุมัติ Shop Drawing')

@php
    $data = $data ?? [];
    $lines = fn ($text, $min) => collect(preg_split('/\r\n|\r|\n/', (string) ($text ?? '')))
        ->filter(fn ($l) => $l !== '')->values()->pad($min, '');
    $purpose = $data['purpose'] ?? null;
    $result = $data['result'] ?? null;
@endphp

@section('styles')
    .sec { border: 1px solid #000; padding: 4px 10px 6px 10px; }
    .opts td { padding: 3px 0; }
@endsection

@section('content')
<div class="sec" style="border-bottom:none;">
    <div class="title">ใบขออนุมัติ Shop Drawing</div>
    <table>
        <tr>
            <td class="small" style="width:50%;">บริษัท วงศ์ชัย จำกัด</td>
            <td style="width:50%;">
                <table class="ln"><tr><td style="width:20%;"></td><td class="l">เลขที่ขอหนังสือที่ออก</td><td class="vc">{{ $data['doc_no'] ?? '' }}</td></tr></table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="right">วันที่ @include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
        </tr>
    </table>
</div>

<div class="sec" style="border-bottom:none;">
    <div class="right bold">(ส่วนของบริษัทฯ)</div>
    <table class="ln"><tr>
        <td class="l">ชื่อโครงการ</td><td class="v" style="width:55%;">{{ $data['project_name'] ?? '' }}</td>
        <td class="l">&nbsp;Shop Drawing เลขที่</td><td class="v">{{ $data['shop_drawing_no'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr><td class="l">ชื่อลูกค้า</td><td class="v">{{ $data['customer_name'] ?? '' }}</td></tr></table>
    <table class="ln"><tr><td class="l">เรียน</td><td class="v">{{ $data['attention'] ?? '' }}</td></tr></table>

    <table class="opts" style="margin-left:12mm; width:auto;">
        <tr>
            <td style="width:40mm;">@include('pdf.partials.cb', ['checked' => $purpose === 'comment']) เพื่อขอความเห็น</td>
            <td style="width:38mm;">@include('pdf.partials.cb', ['checked' => $purpose === 'approval']) เพื่อขออนุมัติ</td>
            <td>@include('pdf.partials.cb', ['checked' => $purpose === 'info']) เพื่อทราบ</td>
        </tr>
    </table>
    <table class="ln" style="margin-left:12mm; width:auto;"><tr>
        <td class="l">@include('pdf.partials.cb', ['checked' => $purpose === 'other']) อื่นๆ</td>
        <td class="v" style="width:150mm;">{{ $data['purpose_other'] ?? '' }}</td>
    </tr></table>

    <div style="margin-top:4px;">รายละเอียดของเอกสารที่แนบ</div>
    @foreach ($lines($data['attachments'] ?? '', 7) as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach

    <table style="margin-top:6px;">
        <tr>
            <td style="width:50%;">
                <table class="ln"><tr><td class="l">ผู้จัดทำ</td><td class="vc">{{ $data['prepared_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['prepared_date'] ?? null])</td></tr></table>
            </td>
            <td style="width:50%;">
                <table class="ln"><tr><td class="l">ผู้อนุมัติ</td><td class="vc">{{ $data['approved_by'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['approved_date'] ?? null])</td></tr></table>
            </td>
        </tr>
        <tr>
            <td class="center" style="padding-top:6px;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
            <td class="center" style="padding-top:6px;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
        </tr>
        <tr>
            <td class="center" style="padding-top:4px;">หัวหน้าส่วนปฏิบัติการสนาม</td>
            <td class="center" style="padding-top:4px;">หัวหน้าแผนกปฏิบัติการสนาม</td>
        </tr>
    </table>
</div>

<div class="sec">
    <table>
        <tr>
            <td style="padding:0;">สรุปผลการพิจารณา</td>
            <td class="right bold" style="padding:0;">(ส่วนของลูกค้า)</td>
        </tr>
    </table>
    <table class="opts" style="margin-left:12mm; width:auto;">
        <tr>
            <td style="width:52mm;">@include('pdf.partials.cb', ['checked' => $result === 'approved']) อนุมัติตามรูปแบบที่แนบ</td>
            <td style="width:44mm;">@include('pdf.partials.cb', ['checked' => $result === 'approved_with_remark']) อนุมัติตามหมายเหตุ</td>
            <td>@include('pdf.partials.cb', ['checked' => $result === 'rejected']) ไม่อนุมัติ</td>
        </tr>
    </table>
    <table class="ln" style="margin-left:12mm; width:auto;"><tr>
        <td class="l">@include('pdf.partials.cb', ['checked' => $result === 'other']) อื่นๆ</td>
        <td class="v" style="width:150mm;">{{ $data['result_other'] ?? '' }}</td>
    </tr></table>

    <div style="margin-top:4px;">หมายเหตุ</div>
    @foreach ($lines($data['customer_remark'] ?? '', 4) as $line)
        <div class="line">{{ $line }}&nbsp;</div>
    @endforeach

    <table style="margin-top:12px;">
        <tr>
            <td style="width:40%;"></td>
            <td style="width:35%;"><table class="ln"><tr><td class="vc">{{ $data['customer_signer'] ?? '' }}</td></tr></table></td>
            <td class="center" style="width:25%;">@include('pdf.partials.date', ['value' => $data['customer_date'] ?? null])</td>
        </tr>
        <tr>
            <td></td>
            <td class="center" style="padding-top:6px;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
            <td></td>
        </tr>
    </table>
    <div style="margin-top:4px; margin-bottom:4px;">กรุณาเขียนชื่อและนามสกุลตัวบรรจงในวงเล็บ</div>
</div>
@endsection
