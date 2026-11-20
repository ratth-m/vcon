{{--
    F-PD-016 ใบควบคุมเอกสารส่งงาน (ฉบับที่ 1) — A4 แนวนอน
    เรียกใช้: Pdf::loadView('pdf.f-pd-016', [...])->setPaper('a4', 'landscape')

    $data = [
        'unit' => 'sampran' | 'lamlukka' | 'sriracha',
        'page' => '', 'pages' => '',
    ];
    // วันที่: string/Carbon (แสดงเป็น d/m/y) หรือข้อความอื่น เช่น '-' จะแสดงตามที่ส่งมา
    $items = [
        ['doc_no' => '321 / 26', 'project_name' => '', 'total' => '',
         'prepared_date' => null, 'reviewed_date' => null, 'customer_approved_date' => null,
         'received_date' => null, 'field_checked_date' => null, 'sent_admin_date' => null, 'remark' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-016 ฉบับที่ : 1')
@section('title', 'F-PD-016 ใบควบคุมเอกสารส่งงาน')
@section('orientation', 'landscape')
@section('margin', '8mm 10mm 12mm 10mm')
@section('font-size', '13.5')
@section('row-height', '27px')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $rows = $items->pad(max($minRows ?? 20, $items->count()), null);
    $unit = $data['unit'] ?? null;
    $date = function ($v) {
        if ($v instanceof \DateTimeInterface) {
            return $v->format('d/m/y');
        }
        if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) {
            return \Illuminate\Support\Carbon::parse($v)->format('d/m/y');
        }

        return $v ?? '';
    };
    $dateCols = ['prepared_date', 'reviewed_date', 'customer_approved_date', 'received_date', 'field_checked_date', 'sent_admin_date'];
@endphp

@section('styles')
    .row-h td { font-size: 12.5pt; }
    thead th { line-height: 1.05; }
@endsection

@section('content')
<table class="grid" style="border:1px solid #000;">
    <thead>
        <tr>
            <td colspan="10" style="border-bottom:none; padding:2px 6px;">
                <div class="title">ใบควบคุมเอกสารส่งงาน</div>
                <table class="no-border">
                    <tr>
                        <td style="width:30%; padding:0;">บริษัท วงศ์ชัย จำกัด</td>
                        <td style="padding:0;" class="right">หน่วยงาน &nbsp;</td>
                        <td style="padding:0; width:12%;">@include('pdf.partials.cb', ['checked' => $unit === 'sampran']) สามพราน</td>
                        <td style="padding:0; width:12%;">@include('pdf.partials.cb', ['checked' => $unit === 'lamlukka']) ลำลูกกา</td>
                        <td style="padding:0; width:12%;">@include('pdf.partials.cb', ['checked' => $unit === 'sriracha']) ศรีราชา</td>
                        <td style="padding:0; width:14%;" class="right">หน้าที่ {{ $data['page'] ?? '......' }} / {{ $data['pages'] ?? '......' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <th style="width:8%;">เลขที่<br>ใบส่งงาน</th>
            <th style="width:33%;">ชื่อโครงการ</th>
            <th style="width:7%;">ยอดรวม</th>
            <th style="width:7%;">วันที่<br>จัดทำ</th>
            <th style="width:7%;">วันที่<br>ทวนสอบ</th>
            <th style="width:7%;">วันที่<br>ลูกค้า<br>อนุมัติ</th>
            <th style="width:7%;">วันที่<br>รับ<br>ใบส่งงาน</th>
            <th style="width:8%;">วันที่แผนก<br>ปฏิบัติการ<br>สนามตรวจ</th>
            <th style="width:8%;">วันที่ส่ง<br>แผนกธุรการ<br>โรงงาน</th>
            <th>หมายเหตุ</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr class="row-h">
                <td class="center">{{ $row['doc_no'] ?? '' }}</td>
                <td>{{ $row['project_name'] ?? '' }}</td>
                <td class="right">{{ $row['total'] ?? '' }}</td>
                @foreach ($dateCols as $k)
                    <td class="center">{{ $date($row[$k] ?? null) }}</td>
                @endforeach
                <td>{{ $row['remark'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
