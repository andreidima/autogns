<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Manopere</title>
    <style>
        /* html {
            margin: 0px 0px;
        } */
        /** Define the margins of your page **/
        @page {
            margin: 0px 0px;
        }

        /* header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 0px;
        } */

        body {
            font-family: DejaVu Sans, sans-serif;
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
            margin-top: 20px;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 2cm;
        }

        * {
            /* padding: 0; */
            text-indent: 0;
        }

        table{
            border-collapse:collapse;
            margin: 0px;
            padding: 5px;
            margin-top: 0px;
            border-style: solid;
            border-width: 0px;
            width: 100%;
            word-wrap:break-word;
        }

        th, td {
            padding: 1px 5px;
            border-width: 1px;
            border-style: solid;

        }
        tr {
            border-style: solid;
            border-width: 0px;
        }
        hr {
            display: block;
            margin-top: 0.5em;
            margin-bottom: 0.5em;
            margin-left: auto;
            margin-right: auto;
            border-style: inset;
            border-width: 0.5px;
        }
    </style>
</head>

<body>
    {{-- <header> --}}
        {{-- <img src="{{ asset('images/contract-header.jpg') }}" width="800px"> --}}
    {{-- </header> --}}

    <main>

        {{-- <div style="page-break-after: always"> --}}
        <div>
            {{-- <div style="text-align:center">
                <img src="{{ asset('imagini/autogns-logo-01-2048x482.png') }}" style="height: 100px;">
            </div> --}}

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; width:50%">
                        <img src="{{ asset('imagini/autogns-logo-01-2048x482.png') }}" style="height: 70px;">
                    </td>
                    <td style="border-width:0px; text-align:center;">
                        <h1 style="margin: 0">Manopere</h1>
                        Data: {{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}
                    </td>
                </tr>
            </table>

            <br>
            <br>

            <table style="width:100%; margin-left: auto; margin-right: auto;">
                <thead>
                    <tr>
                        <th>Mecanic</th>
                        <th>Programare</th>
                        <th>Manopera</th>
                        <th>Preț</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manopere->sortBy('mecanic.name')->groupBy('mecanic_id') as $manopereGrupateDupaMecanic)
                        @foreach ($manopereGrupateDupaMecanic as $manopera)
                            <tr>
                                @if ($loop->first)
                                    <td rowspan="{{ $manopereGrupateDupaMecanic->count() }}">
                                        <b>{{ $manopera->mecanic->name ?? '' }}</b>
                                    </td>
                                @endif
                                <td>
                                    {{ $manopera->programare->masina ?? '' }} {{ $manopera->programare->nr_auto ?? '' }}
                                </td>
                                <td>
                                    {{ $manopera->denumire }}
                                </td>
                                <td style="text-align:right;">
                                    {{ $manopera->pret }}
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="3" style="text-align:right; border-bottom:0px">
                                    <b>Total</b>
                                </td>
                                <td style="text-align:right;">
                                    <b>{{ $manopereGrupateDupaMecanic->sum('pret') }}</b>
                                </td>
                            </tr>
                            <tr>
                                @if (!$loop->last)
                                    <td colspan="4"  style="border-top:0px">
                                @else
                                    <td colspan="4"  style="border-top:0px; border-bottom:0px">
                                @endif
                                        &nbsp;
                                    </td>
                            </tr>

                    @endforeach
                            <tr>
                                <td colspan="3" style="text-align:right; border-top:0px">
                                    <b>Total general</b>
                                </td>
                                <td style="text-align:right;">
                                    <b>{{ $manopere->sum('pret') }}</b>
                                </td>
                            </tr>
                </tbody>
            </table>

            <br>


            </div>


        {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
        <script type="text/php">
            if (isset($pdf)) {
                $text = "Pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>


    </main>
</body>

</html>
