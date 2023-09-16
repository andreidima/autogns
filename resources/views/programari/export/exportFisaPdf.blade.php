<!DOCTYPE  html>
<html lang="ro">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Programare</title>
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
            font-size: 12px;
            margin-top: 10px;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 10px;
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
            padding: 0px 5px;
            border-width: 0px;
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
                    <td style="width:60%; border-width:0px;">
                        <img src="{{ asset('imagini/autogns-logo-01-2048x482.png') }}" style="height: 70px;">
                    </td>
                    <td style="width:40%; border-width:0px; text-align:center;">
                        SC Gn Systems INC SRL
                        <br>
                        Focșani, Vrancea
                        <br>
                        Str. Verde DN 2 - E 85
                    </td>
                    <td>
                        <img src="data:image/png;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->generate(url('/mecanici/pontaje-mecanici/citireQr/' . $programare->id))) }} ">
                    </td>
                </tr>
            </table>

            <h2 style="margin:0px; text-align: center">
                RECEPȚIE AUTO
            </h2>

            <table>
                <tr>
                    <td colspan="2" style="border-width:0px; text-align:center">
                        Nr. înmatriculare:
                        <b>{{ $programare->nr_auto }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="width:68%; padding-right:100px; border-width:0px;">
                        Marca/tip: {{ $programare->masina }}
                        <br>
                        Proprietar: {{ $programare->client }}
                        <br>
                        Delegat: ......................................................
                    </td>
                    <td style="width:32%; border-width:0px;">
                        Data: {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') : '' }}
                        <br>
                        Nr. Tel: {{ $programare->telefon }}
                        <br>
                        Km: .............................
                    </td>
                </tr>
            </table>

            1. Lucrări solicitate de client:
            <div style="padding:0px 2px; border:3px solid black; font-size:14px">
                <b>{{ $programare->lucrare }}</b>
            </div>

            2. Constatare atelier:
            <div style="padding:0px 2px; min-height:200px; border:3px solid black; font-size:14px">
                @foreach ($programare->manopere as $manopera)
                    {{ $manopera->constatare_atelier }}
                    <br>
                @endforeach
            </div>

            3. Observații:
            <br>
            Clientul este de acord cu înlocuirea pieselor și a manoperei prin semnarea Fișei de recepție
            <div style="height: 60px; border:3px solid black;"></div>

            Proces verbal de predare a autovehiculului:
            <table>
                <tr>
                    <td style="background-color:rgb(224, 223, 223)">
                        Starea mașinii:
                    </td>
                    <td>
                        OK
                    </td>
                    <td>
                        NU
                    </td>
                    <td>
                        Explicație
                    </td>
                    <td rowspan="6" style="text-align:right">
                        <img src="{{ asset('imagini/diagrama-masina.jpg') }}" style="width: 160px;">
                    </td>
                </tr>
                <tr>
                    <td>
                        - caroserie
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        ......................................
                    </td>
                </tr>
                <tr>
                    <td>
                        - prabriz, lunetă și geamuri
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        ......................................
                    </td>
                </tr>
                <tr>
                    <td>
                        - faruri, stopuri
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        ......................................
                    </td>
                </tr>
                <tr>
                    <td>
                        - anvelope
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        ......................................
                    </td>
                </tr>
                <tr>
                    <td>
                        - altele
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        <div style="width:15px; height:15px; border:2px solid black;"></div>
                    </td>
                    <td>
                        ......................................
                    </td>
                </tr>
            </table>

            <hr style="border:1px solid black; margin:0px;">

            <table>
                <tr>
                    <td colspan="4">
                        <span style="padding:0px 5px; background-color: rgb(224, 223, 223)">Lipsă:</span>
                        .....................................................................................................................................
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Capace roți
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Antenă
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Trusă medicală
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Trusă scule
                    </td>
                </tr>
                <tr>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Cric
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Roată de rezervă
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Triunghi reflectorizant
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Radio-cd
                    </td>
                </tr>
            </table>

            <hr style="border:1px solid black; margin:0px;">

            <table>
                <tr>
                    <td>
                        <span style="padding:0px 5px; background-color: rgb(224, 223, 223)">De înlocuit:</span>
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Lamelă ștergătoare parbriz
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Baterie telecomandă
                    </td>
                </tr>
            </table>

            <hr style="border:1px solid black; margin:0px;">

            <table>
                <tr>
                    <td>
                        <span style="padding:0px 5px; background-color: rgb(224, 223, 223)">Umplere:</span>
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Lichid parbriz
                    </td>
                </tr>
            </table>

            <hr style="border:1px solid black; margin:0px;">

            <table>
                <tr>
                    <td>
                        <span style="padding:0px 5px; background-color: rgb(224, 223, 223)">Rezervor:</span>
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Plin
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        3/4
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        1/4
                    </td>
                    <td>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        Rezervă
                    </td>
                </tr>
            </table>

            <hr style="border:1px solid black; margin:0px;">

            <table>
                <tr>
                    <td>
                        <span style="padding:0px 5px; background-color: rgb(224, 223, 223)">Prezentarea pieselor înlocuite:</span>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span style="margin-right: 10px;">Da</span>
                        <span style="width:12px; height:12px; margin-right:5px; border:2px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <span style="margin-right: 10px;">Nu</span>
                    </td>
                </tr>
            </table>

            <div style="page-break-inside: avoid;">
                Notă Unitatea noastră nu răspunde pentru obiectele personale lăsate în interiorul autovehicolului.

                <table>
                    <tr>
                        <td style="text-align:center">
                            Am preluat autoturismul
                            <br>
                            RESPONSABIL RECEPȚIE AUTO
                            <br>
                            <br>
                            _____________________________
                        </td>
                        <td style="text-align:center">
                            Am predat autoturismul
                            <br>
                            CLIENT (DELEGAT)
                            <br>
                            <br>
                            _____________________________
                        </td>
                    </tr>
                </table>
            </div>




        </div>


        {{-- Here's the magic. This MUST be inside body tag. Page count / total, centered at bottom of page --}}
        {{-- <script type="text/php">
            if (isset($pdf)) {
                $text = "Pagina {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("helvetica");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width) / 2;
                $y = $pdf->get_height() - 20;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script> --}}


    </main>
</body>

</html>
