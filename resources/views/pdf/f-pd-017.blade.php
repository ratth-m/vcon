{{--
    F-PD-017 ใบประเมินผลการปฏิบัติงาน (ฉบับที่ 2) — 2 หน้า

    $data = [
        'date' => null, 'contract_no' => '', 'unit' => 'sampran' | 'lamlukka' | 'banbueng',
        'project_name' => '', 'location' => '',
        'field_section_head' => '', 'field_dept_head' => '', 'sales_rep' => '',
        // แถวยอดรวม (key ตามคอลัมน์ของ $items) และ % ชำรุดคิดจากยอดส่งงาน
        'totals' => ['order' => '', 'lane' => '', ...],
        'damage_pct' => ['dmg_prod' => '', 'dmg_stock' => '', 'dmg_transport' => '', 'dmg_install' => '', 'dmg_site_stock' => ''],
        'spare_area' => '', 'spare_pct' => '', 'used_area' => '', 'used_pct' => '', 'returned_area' => '', 'returned_pct' => '',
        'recorder' => '', 'recorder_date' => null, 'checker' => '', 'checker_date' => null,
        // หน้า 2
        'md_result' => 'excellent' | 'good' | 'fair' | 'improve', 'md_comment' => '',
    ];
    $items = [   // สูงสุด 6 แถว
        ['type' => '', 'wire' => '', 'length' => '', 'order' => '', 'lane' => '', 'modified' => '',
         'dmg_prod' => '', 'dmg_stock' => '', 'prod_total' => '', 'transport' => '',
         'dmg_transport' => '', 'dmg_install' => '', 'dmg_site_stock' => '', 'delivered' => ''],
    ];
    $ratios = [
        'products' => ['ชนิด A', 'ชนิด B', '', ''],   // หัวคอลัมน์แยกชนิดผลิตภัณฑ์ (4 ช่อง)
        'order' => ['', '', '', '', 'total'],          // ยอดสั่ง①/ยอดส่งงาน⑥
        'production' => [...],                         // ยอดผลิต④/ยอดส่งงาน⑥
        'transport' => [...],                          // ยอดขนส่ง⑤/ยอดส่งงาน⑥
    ];
    $logistics = [   // [ยอดประเมิน, ยอดใช้จริง, ผลต่าง, คิดเป็น%]
        'trucks' => ['', '', '', ''], 'cranes' => [...], 'tag_trucks' => [...], 'hoists' => [...], 'install_days' => [...],
    ];
    $evaluations = [   // หน้า 2 — key ดูใน $evalGroups ด้านล่าง
        'prod_section' => ['target' => '', 'target_pct' => '', 'result_pct' => '', 'passed' => true|false|null, 'comment' => ''],
    ];
--}}
@extends('pdf.layout')

@section('form-code', 'F-PD-017 ฉบับที่ : 2')
@section('title', 'F-PD-017 ใบประเมินผลการปฏิบัติงาน')
@section('font-size', '13.5')
@section('row-height', '21px')
@section('line-height', '5.6mm')

@php
    $data = $data ?? [];
    $items = collect($items ?? [])->take(6)->pad(6, null);
    $ratios = $ratios ?? [];
    $logistics = $logistics ?? [];
    $evaluations = $evaluations ?? [];
    $unit = $data['unit'] ?? null;
    $c = fn ($n) => '<span class="circ">'.['', '&#9312;', '&#9313;', '&#9314;', '&#9315;', '&#9316;', '&#9317;'][$n].'</span>';
    $cols = ['order', 'lane', 'modified', 'dmg_prod', 'dmg_stock', 'prod_total', 'transport', 'dmg_transport', 'dmg_install', 'dmg_site_stock', 'delivered'];
    $pctCols = ['dmg_prod', 'dmg_stock', 'dmg_transport', 'dmg_install', 'dmg_site_stock'];
    $evalGroups = [
        'ผลิตภัณฑ์' => [
            'prod_section' => 'หัวหน้าส่วนผลิต :',
            'transport_section' => 'หัวหน้าส่วนขนส่ง :',
            'cutting_section' => 'หัวหน้าส่วนตัดแผ่นและดัดแปลง :',
            'plant_dept' => 'หัวหน้าแผนกปฏิบัติการโรงงาน :',
            'plant_manager' => 'ผู้จัดการฝ่ายโรงงาน :',
            'product_manager' => 'ผู้จัดการผลิตภัณฑ์ :',
        ],
        'ปฏิบัติการ' => [
            'field_section' => 'หัวหน้าส่วนปฏิบัติการสนาม :',
            'field_dept' => 'หัวหน้าแผนกปฏิบัติการสนาม :',
            'field_manager' => 'ผู้จัดการฝ่ายปฏิบัติการสนาม :',
            'operation_manager' => 'ผู้จัดการปฏิบัติการ :',
        ],
    ];
    $logRows = [
        'trucks' => '1. จำนวนรถขนส่ง &nbsp;&nbsp; (เที่ยว)',
        'cranes' => '2. จำนวนเครนที่ใช้ยก (ครั้ง)',
        'tag_trucks' => '3. จำนวนรถแต็กที่ใช้ &nbsp;(ครั้ง)',
        'hoists' => '4. จำนวนรอกที่ใช้ &nbsp;&nbsp; (ครั้ง)',
        'install_days' => '5. จำนวนครั้งที่ติดตั้ง &nbsp;(วัน)',
    ];
@endphp

@section('styles')
    .circ { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; }
    .main th { font-size: 11.5pt; padding: 0 1px; }
    .main td { text-align: center; }
    .vtext { white-space: nowrap; }
@endsection

@section('content')
{{-- ===================== หน้า 1 ===================== --}}
<table style="border:1px solid #000;">
    <tr><td colspan="2" class="title" style="padding-top:4px;">ใบประเมินผลการปฏิบัติงาน</td></tr>
    <tr>
        <td style="padding-left:8px;">บริษัท วงศ์ชัย จำกัด</td>
        <td class="right" style="padding-right:8px;">วันที่ : @include('pdf.partials.date', ['value' => $data['date'] ?? null])</td>
    </tr>
</table>
<div style="border:1px solid #000; border-top:none; padding:6px 8px 4px 8px;">
    <table class="ln"><tr>
        <td class="l">สัญญาเลขที่ :</td><td class="vc" style="width:40mm;">{{ $data['contract_no'] ?? '' }}</td>
        <td style="width:30mm;"></td>
        <td class="l">หน่วยงาน</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'sampran']) สามพราน</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'lamlukka']) ลำลูกกา</td>
        <td class="l">@include('pdf.partials.cb', ['checked' => $unit === 'banbueng']) บ้านบึง</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l">โครงการ &nbsp;&nbsp;:</td><td class="v" style="width:45%;">{{ $data['project_name'] ?? '' }}</td>
        <td class="l">&nbsp; ที่ตั้ง :</td><td class="v">{{ $data['location'] ?? '' }}</td>
    </tr></table>
    <table class="ln"><tr>
        <td class="l">หัวหน้าส่วนปฏิบัติการสนาม :</td><td class="vc">{{ $data['field_section_head'] ?? '' }}</td>
        <td class="l">&nbsp; หัวหน้าแผนกปฏิบัติการสนาม :</td><td class="vc">{{ $data['field_dept_head'] ?? '' }}</td>
        <td class="l">&nbsp; ผู้แทนขาย :</td><td class="vc">{{ $data['sales_rep'] ?? '' }}</td>
    </tr></table>
</div>

<table class="grid main" style="border:1px solid #000; border-top:none;">
    <tr>
        <th rowspan="2" style="width:4%;">ลำ<br>ดับ</th>
        <th colspan="3">รายการ</th>
        <th rowspan="2" style="width:7%;">ยอดสั่ง<br>(ม<sup>2</sup>)<br>{!! $c(1) !!}</th>
        <th colspan="5">การผลิต</th>
        <th colspan="4">ขนส่ง</th>
        <th rowspan="2" style="width:6%;">ส่งงาน<br>{!! $c(6) !!}</th>
    </tr>
    <tr>
        <th style="width:6%;">ชนิด</th>
        <th style="width:6%;">ลวด</th>
        <th style="width:7%;">ยาว</th>
        <th style="width:6%;">ในเลน<br>{!! $c(2) !!}</th>
        <th style="width:6%;">ดัดแปลง<br>{!! $c(3) !!}</th>
        <th style="width:6%;">ชำรุด<br>ผลิต</th>
        <th style="width:6%;">ชำรุด<br>Stock</th>
        <th style="width:7%;">{!! $c(2) !!}+{!! $c(3) !!}<br>รวม(ม<sup>2</sup>)<br>{!! $c(4) !!}</th>
        <th style="width:6%;">ขนส่ง<br>{!! $c(5) !!}</th>
        <th style="width:6%;">ชำรุด<br>ขนส่ง</th>
        <th style="width:6%;">ชำรุด<br>ติดตั้ง</th>
        <th style="width:7%;">ชำรุด<br>stock<br>หน้างาน</th>
    </tr>
    @foreach ($items as $i => $row)
        <tr class="row-h">
            <td>{{ $i + 1 }}</td>
            <td>{{ $row['type'] ?? '' }}</td>
            <td>{{ $row['wire'] ?? '' }}</td>
            <td>{{ $row['length'] ?? '' }}</td>
            @foreach ($cols as $col)
                <td>{{ $row[$col] ?? '' }}</td>
            @endforeach
        </tr>
    @endforeach
    <tr class="row-h">
        <td colspan="4" style="border-left:none; border-bottom:none; text-align:right;">ยอดรวม</td>
        @foreach ($cols as $col)
            <td>{{ $data['totals'][$col] ?? '' }}</td>
        @endforeach
    </tr>
    <tr class="row-h">
        <td colspan="7" style="border:none; text-align:right; padding-right:8px;">ยอดชำรุดต่างๆ คิดจากยอดส่งงาน</td>
        @foreach (['dmg_prod', 'dmg_stock'] as $col)
            <td style="text-align:right;">{{ $data['damage_pct'][$col] ?? '' }} %</td>
        @endforeach
        <td colspan="2" style="border-top:none; border-bottom:none;"></td>
        @foreach (['dmg_transport', 'dmg_install', 'dmg_site_stock'] as $col)
            <td style="text-align:right;">{{ $data['damage_pct'][$col] ?? '' }} %</td>
        @endforeach
        <td style="border:none;"></td>
    </tr>
</table>

<table class="ln" style="width:auto; margin:8px auto 10px auto;"><tr>
    <td class="l">แผ่นเผื่อจำนวน</td><td class="vc" style="width:18mm;">{{ $data['spare_area'] ?? '' }}</td><td class="l">ม<sup>2</sup> &nbsp;คิดเป็น</td>
    <td class="vc" style="width:12mm;">{{ $data['spare_pct'] ?? '' }}</td><td class="l">% &nbsp;ใช้งาน</td>
    <td class="vc" style="width:18mm;">{{ $data['used_area'] ?? '' }}</td><td class="l">ม<sup>2</sup></td>
    <td class="vc" style="width:14mm;">{{ $data['used_pct'] ?? '' }}</td><td class="l">% &nbsp;ส่งกลับ</td>
    <td class="vc" style="width:18mm;">{{ $data['returned_area'] ?? '' }}</td><td class="l">ม<sup>2</sup></td>
    <td class="vc" style="width:14mm;">{{ $data['returned_pct'] ?? '' }}</td><td class="l">%</td>
</tr></table>

<table class="grid" style="border:1px solid #000;">
    <tr>
        <th rowspan="2" style="width:28%;">อัตราส่วน</th>
        <th colspan="4">แยกชนิดผลิตภัณฑ์</th>
        <th rowspan="2" style="width:16%;">รวมทุกชนิด</th>
    </tr>
    <tr>
        @for ($k = 0; $k < 4; $k++)
            <th style="width:14%; height:20px;">{{ $ratios['products'][$k] ?? '' }}</th>
        @endfor
    </tr>
    @foreach ([
        'order' => '1. ยอดสั่ง'.$c(1).'/ยอดส่งงาน'.$c(6),
        'production' => '2.ยอดผลิต'.$c(4).'/ยอดส่งงาน'.$c(6),
        'transport' => '3. ยอดขนส่ง'.$c(5).' /ยอดส่งงาน'.$c(6),
    ] as $key => $label)
        <tr class="row-h">
            <td>{!! $label !!}</td>
            @for ($k = 0; $k < 5; $k++)
                <td class="right">{{ $ratios[$key][$k] ?? '' }} %</td>
            @endfor
        </tr>
    @endforeach
</table>

<div class="bold" style="margin:10px 0 2px 12mm;">ข้อมูลงานขนส่งและติดตั้ง</div>
<table class="grid" style="border:1px solid #000;">
    <tr>
        <th style="width:36%;">รายละเอียด</th>
        <th style="width:16%;">ยอดประเมิน<br>{!! $c(1) !!}</th>
        <th style="width:16%;">ยอดใช้จริง<br>{!! $c(2) !!}</th>
        <th style="width:16%;">ผลต่าง<br>{!! $c(2) !!}-{!! $c(1) !!}</th>
        <th style="width:16%;">คิดเป็น<br>{!! $c(3) !!}/{!! $c(1) !!} (%)</th>
    </tr>
    @foreach ($logRows as $key => $label)
        <tr class="row-h">
            <td>{!! $label !!}</td>
            @for ($k = 0; $k < 4; $k++)
                <td class="center">{{ $logistics[$key][$k] ?? '' }}</td>
            @endfor
        </tr>
    @endforeach
</table>

<table style="margin-top:22px;">
    <tr>
        <td style="width:50%; padding:0 8px 0 12mm;">
            <table class="ln"><tr><td class="l">ผู้บันทึก</td><td class="vc">{{ $data['recorder'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['recorder_date'] ?? null])</td></tr></table>
        </td>
        <td style="width:50%; padding:0;">
            <table class="ln"><tr><td class="l">ผู้ตรวจสอบ</td><td class="vc">{{ $data['checker'] ?? '' }}</td><td class="gap"></td><td class="l">@include('pdf.partials.date', ['value' => $data['checker_date'] ?? null])</td></tr></table>
        </td>
    </tr>
    <tr>
        <td style="padding:6px 0 0 30mm;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
        <td style="padding:6px 0 0 18mm;">( {!! str_repeat('&nbsp;', 40) !!} )</td>
    </tr>
</table>

{{-- ===================== หน้า 2 ===================== --}}
<div class="page-break"></div>
<div class="bold" style="margin-top:4mm;">ประเมินการปฏิบัติงานและความเห็นของแต่ละหน่วยงาน</div>
@foreach ($evalGroups as $groupName => $roles)
    <div class="bold" style="{{ $loop->first ? '' : 'margin-top:14px;' }}">{{ $groupName }}</div>
    @foreach ($roles as $key => $label)
        @php
            $ev = $evaluations[$key] ?? [];
            $commentLines = collect(preg_split('/\r\n|\r|\n/', (string) ($ev['comment'] ?? '')))->filter(fn ($l) => $l !== '')->values()->pad(2, '');
        @endphp
        <table class="ln"><tr>
            <td class="l" style="width:40mm;">{{ $label }}</td>
            <td class="l">เป้าหมาย</td><td class="v">{{ $ev['target'] ?? '' }}</td>
            <td class="gap"></td>
            <td class="vc" style="width:12mm;">{{ $ev['target_pct'] ?? '' }}</td><td class="l">% ผล</td>
            <td class="vc" style="width:12mm;">{{ $ev['result_pct'] ?? '' }}</td><td class="l">% ผลการประเมิน</td>
            <td class="l">@include('pdf.partials.cb', ['checked' => ($ev['passed'] ?? null) === true]) ผ่าน</td>
            <td class="l">@include('pdf.partials.cb', ['checked' => ($ev['passed'] ?? null) === false]) ไม่ผ่าน</td>
        </tr></table>
        @foreach ($commentLines as $line)
            <div class="line">{{ $line }}&nbsp;</div>
        @endforeach
    @endforeach
@endforeach

@php
    $md = $data['md_result'] ?? null;
    $mdLines = collect(preg_split('/\r\n|\r|\n/', (string) ($data['md_comment'] ?? '')))->filter(fn ($l) => $l !== '')->values()->pad(2, '');
@endphp
<table class="ln" style="margin-top:18px;"><tr>
    <td class="l">กรรมการผู้จัดการ &nbsp;: &nbsp;สรุปผลการประเมิน</td>
    <td class="l">@include('pdf.partials.cb', ['checked' => $md === 'excellent']) ดีมาก</td>
    <td class="l">@include('pdf.partials.cb', ['checked' => $md === 'good']) ดี</td>
    <td class="l">@include('pdf.partials.cb', ['checked' => $md === 'fair']) พอใช้</td>
    <td class="l">@include('pdf.partials.cb', ['checked' => $md === 'improve']) ควรปรับปรุง</td>
    <td></td>
</tr></table>
@foreach ($mdLines as $line)
    <div class="line">{{ $line }}&nbsp;</div>
@endforeach
@endsection
