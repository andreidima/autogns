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
            margin-left: 2cm;
            margin-right: 2cm;
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

            <table style="">
                <tr valign="" style="">
                    <td style="border-width:0px; text-align:center;">
                        <h2 style="margin: 0">AutoGNS - Manopere</h2>
                        Data: {{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}
                    </td>
                </tr>
            </table>

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
                                <td>
                                    {{ $manopera->pret }}
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td colspan="3" style="text-align:right; border-bottom:0px">
                                    <b>Total</b>
                                </td>
                                <td style="border-bottom:0px">
                                    <b>{{ $manopereGrupateDupaMecanic->sum('pret') }}</b>
                                </td>
                            </tr>
                            @if (!$loop->last)
                                <tr>
                                    <td colspan="4"  style="border-top:0px">
                                        &nbsp;
                                        {{-- <br>
                                        &nbsp; --}}
                                    </td>
                                </tr>
                            @endif
                    @endforeach
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
