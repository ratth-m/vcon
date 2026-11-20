{{--
    F-PD-003 รายการส่งมอบผลิตภัณฑ์ประจำโครงการ (ฉบับที่ 2)

    $data = [
        'page' => '1', 'pages' => '1',
        'project_name' => '', 'production_no' => '',
        'prepared_by' => '', 'prepared_date' => null,
        'checked_by' => '',  'checked_date' => null,
        'approved_by' => '', 'approved_date' => null,
    ];
    $items = [
        ['code' => '', 'size' => '', 'days' => [1 => qty, ..., 7 => qty], 'total' => '', 'remark' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-003 ฉบับที่ : 2')
@section('title', 'F-PD-003 รายการส่งมอบผลิตภัณฑ์ประจำโครงการ')
@section('font-size', '15')

@php
    $data = $data ?? [];
    $items = collect($items ?? []);
    $minRows = $minRows ?? 29;
    $rows = $items->pad(max($minRows, $items->count()), null);
@endphp

@section('content')
<table class="box-thick">
    <tr>
        <td colspan="4" class="title" style="padding-top:4px;">รายการส่งมอบผลิตภัณฑ์ประจำโครงการ</td>
    </tr>
    <tr>
        <td colspan="3" class="small">บริษัท วงศ์ชัย จำกัด</td>
        <td class="right small">หน้าที่ : {{ $data['page'] ?? '......' }}/{{ $data['pages'] ?? '......' }}</td>
    </tr>
</table>
<table class="grid" style="border:1.5px solid #000; border-top:none;">
    <tr>
        <td style="width:38%; border-bottom:none;"><table class="ln"><tr><td class="l">ชื่อโครงการ :</td><td class="v">{{ $data['project_name'] ?? '' }}</td></tr></table></td>
        <td style="width:20%; border-bottom:none;"><table class="ln"><tr><td class="l">ผู้จัดทำ</td><td class="vc">{{ $data['prepared_by'] ?? '' }}</td></tr></table></td>
        <td style="width:21%; border-bottom:none;"><table class="ln"><tr><td class="l">ผู้ตรวจสอบ</td><td class="vc">{{ $data['checked_by'] ?? '' }}</td></tr></table></td>
        <td style="width:21%; border-bottom:none;"><table class="ln"><tr><td class="l">ผู้อนุมัติ</td><td class="vc">{{ $data['approved_by'] ?? '' }}</td></tr></table></td>
    </tr>
    <tr>
        <td style="border-top:none;"><table class="ln"><tr><td class="l">Production No. :</td><td class="v">{{ $data['production_no'] ?? '' }}</td></tr></table></td>
        <td class="center" style="border-top:none;">@include('pdf.partials.date', ['value' => $data['prepared_date'] ?? null])</td>
        <td class="center" style="border-top:none;">@include('pdf.partials.date', ['value' => $data['checked_date'] ?? null])</td>
        <td class="center" style="border-top:none;">@include('pdf.partials.date', ['value' => $data['approved_date'] ?? null])</td>
    </tr>
</table>

<table class="grid" style="border:1.5px solid #000; border-top:none;">
    <thead>
        <tr>
            <th rowspan="2" style="width:5%;">ลำ<br>ดับ</th>
            <th rowspan="2" style="width:14%;">รหัส</th>
            <th rowspan="2" style="width:15%;">ขนาด (ม)<br>กว้าง x ยาว</th>
            <th colspan="7">ลำดับวันในการจัดส่ง</th>
            <th rowspan="2" style="width:9%;">ยอดรวม<br>แผ่น</th>
            <th rowspan="2" style="width:20%;">หมายเหตุ</th>
        </tr>
        <tr>
            @for ($d = 1; $d <= 7; $d++)
                <th style="width:4.4%;">{{ $d }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $i => $row)
            <tr class="row-h">
                <td class="center">{{ $row ? $i + 1 : '' }}</td>
                <td>{{ $row['code'] ?? '' }}</td>
                <td class="center">{{ $row['size'] ?? '' }}</td>
                @for ($d = 1; $d <= 7; $d++)
                    <td class="center">{{ $row['days'][$d] ?? '' }}</td>
                @endfor
                <td class="center">{{ $row['total'] ?? '' }}</td>
                <td>{{ $row['remark'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
