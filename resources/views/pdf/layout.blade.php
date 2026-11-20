<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Document')</title>
    @php
        // ขนาดตัวอักษรพื้นฐาน (pt) กำหนดจาก view ลูกด้วย @section('font-size', '15')
        $__base = (float) ($__env->yieldContent('font-size') ?: 16);
        $pt = fn ($n) => round($n, 1).'pt';
    @endphp
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/THSarabunNew.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url('{{ storage_path('fonts/THSarabunNew-Bold.ttf') }}') format('truetype');
        }

        @page {
            size: A4 @yield('orientation', 'portrait');
            margin: @yield('margin', '10mm 12mm 12mm 12mm');
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'THSarabunNew', 'DejaVu Sans', sans-serif;
            font-size: {{ $pt($__base) }};
            line-height: 0.95;
            color: #000;
            margin: 0;
        }

        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: middle; padding: 1px 3px; font-weight: normal; }

        .grid td, .grid th { border: 0.7px solid #000; }
        .grid th { text-align: center; }
        .box { border: 1px solid #000; }
        .box-thick { border: 1.5px solid #000; }
        .no-border td, .no-border th { border: none; }

        .title { font-size: {{ $pt($__base + 4) }}; font-weight: bold; text-align: center; }
        .bold { font-weight: bold; }
        .normal { font-weight: normal; }
        .center { text-align: center; }
        .right { text-align: right; }
        .left { text-align: left; }
        .small { font-size: {{ $pt($__base - 1.5) }}; }
        .large { font-size: {{ $pt($__base + 1.5) }}; }
        .xsmall { font-size: {{ $pt($__base - 3.5) }}; }
        .shade { background: #e6e6e6; }

        /* เส้นประสำหรับกรอกข้อมูล */
        .fill {
            display: inline-block;
            border-bottom: 0.8px dotted #000;
            min-height: 14px;
            padding: 0 2px;
            vertical-align: bottom;
            text-align: center;
        }
        .fill-left { text-align: left; }

        /* บรรทัด label + ช่องกรอก: <table class="ln"><tr><td class="l">ชื่อ</td><td class="v">ค่า</td></tr></table> */
        table.ln { width: 100%; border-collapse: collapse; }
        table.ln td { padding: 0 2px; border: none; vertical-align: bottom; }
        table.ln td.l { white-space: nowrap; width: 1%; padding-right: 3px; }
        table.ln td.v { border-bottom: 0.8px dotted #000; }
        table.ln td.vc { border-bottom: 0.8px dotted #000; text-align: center; }
        table.ln td.gap { width: 8px; }
        .line {
            border-bottom: 0.8px dotted #000;
            height: @yield('line-height', '6.8mm');
            padding: 0 2px;
            overflow: hidden;
        }

        /* checkbox */
        .cb {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            line-height: 9px;
            text-align: center;
            vertical-align: middle;
            margin: 0 3px 0 6px;
        }

        .form-code {
            position: fixed;
            bottom: -7mm;
            right: 0;
            text-align: right;
            font-size: {{ $pt(13) }};
            margin-top: 6px;
        }
        .page-break { page-break-after: always; }
        .row-h td { height: @yield('row-height', '24px'); padding-top: 0; padding-bottom: 0; }
        .row-h td.fit { font-size: {{ $pt($__base - 1.5) }}; }

        @yield('styles')
    </style>
</head>
<body>
@hasSection('form-code')
<div class="form-code">@yield('form-code')</div>
@endif
@yield('content')
</body>
</html>
