<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Directory Data</title>
    <style>
        @page {
            margin: 25px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #798b95;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .cover-page {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border: 1px solid #333;
            box-sizing: border-box;
        }

        .cover-inner {
            position: absolute;
            top: 4px;
            bottom: 4px;
            left: 4px;
            right: 4px;
            text-align: center;
            box-sizing: border-box;
            padding: 20px;
        }

        .cover-updated {
            text-align: right;
            color: #2e7d32;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 40px;
        }

        .cover-title-box {
            background-color: #9cd5e8;
            padding: 30px;
            margin: 0 -20px 40px -20px;
            /* Extend over the padding */
        }

        .cover-title-box h1 {
            color: #1a4f76;
            margin: 0;
            font-size: 32px;
            font-family: 'Times New Roman', Times, serif;
            letter-spacing: 2px;
        }

        .cover-year {
            color: #1a4f76;
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 150px;
        }

        .cover-logo img {
            width: 250px;
            height: auto;
        }

        .cover-footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-weight: bold;
            line-height: 1.5;
        }

        .cover-footer .dept {
            font-size: 16px;
        }

        .cover-footer .address {
            font-size: 14px;
        }

        .cover-footer .website {
            font-size: 14px;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="cover-page">
        <div class="cover-inner">
            <div class="cover-updated">
                Last updated on {{ \Carbon\Carbon::parse($lastUpdatedOn)->format('d.m.Y') }}
            </div>

            <div class="cover-title-box">
                <h1>TELEPHONE DIRECTORY</h1>
            </div>

            <div class="cover-year">
                {{ date('Y') }}
            </div>

            <div class="cover-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="CPCB Logo" />
                @else
                    <h2>CPCB</h2>
                @endif
            </div>

            <div class="cover-footer">
                <div class="dept">CENTRAL POLLUTION CONTROL BOARD</div>
                <div class="address">'Parivesh Bhawan', East Arjun Nagar, Shahdara, Delhi &ndash; 110032 (INDIA)</div>
                <div class="website">Website: www.cpcb.gov.in</div>
            </div>
        </div>
    </div>

    <div style="page-break-before: always;">
        <table>
            <thead>
                <tr>
                    <th>S No.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Division</th>
                    <th>Ext.</th>
                    <th>Office No.</th>
                    <th>Email ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($directories as $index => $row)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $row->name ?: '-' }}</td>
                        <td>{{ $row->designation ?: '-' }}</td>
                        <td>{{ $row->division ? $row->division->title : '-' }}</td>
                        <td>{{ $row->ext_number ?: '-' }}</td>
                        <td>{{ $row->office_ph_no ?: '-' }}</td>
                        <td>{{ $row->email ? str_replace(['@', '.'], ['[at]', '[dot]'], $row->email) : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>