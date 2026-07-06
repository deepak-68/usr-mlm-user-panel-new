<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Document Preview' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        .page-content {
            padding: 50px 15px;
        }

        .card {
            max-width: 900px;
            margin: auto;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .card-header {
            background: #0d6efd;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .card-header h2,
        .card-header h4 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            padding: 35px;
            line-height: 1.8;
            color: #444;
        }

        .card-body h1,
        .card-body h2,
        .card-body h3,
        .card-body h4,
        .card-body h5 {
            margin-top: 30px;
            margin-bottom: 15px;
            color: #222;
            font-weight: 600;
        }

        .card-body p {
            margin-bottom: 15px;
        }

        .card-body ul,
        .card-body ol {
            margin-bottom: 20px;
            padding-left: 25px;
        }

        .card-body table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .card-body table th,
        .card-body table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .card-body table th {
            background: #f8f9fa;
        }

        .card-body a {
            color: #0d6efd;
            text-decoration: none;
        }

        .card-body a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            color: #777;
            padding: 20px 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 20px;
            }

            .page-content {
                padding: 20px 10px;
            }
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="page-content">
        <div class="container">

            <div class="card">
                <div class="card-header">
                    <h2>{{ $title ?? 'Document' }}</h2>
                </div>

                <div class="card-body">
                    {!! $content !!}
                </div>
            </div>

            <footer>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>