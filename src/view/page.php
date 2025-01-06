<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="m-5">
    <div class="mx-auto p-2" style="width: 40rem;">
        <h1>Task 1</h1>
        <ol>
            <li><a href="<?= link_to_route('db_create') ?>">Create tables</a></li>
            <li><a href="<?= link_to_route('db_seed') ?>">Seed database</a></li>
            <li><a href="<?= link_to_route('report_excess') ?>">Raport Nadpłaty / Excess Payments report</a></li>
            <li><a href="<?= link_to_route('report_underpayment') ?>">Raport Niedopłaty / Underpayment Report</a></li>
            <li><a href="<?= link_to_route('report_outstanding') ?>">Raport Nierozliczone faktury po terminie płatnośći / Report Outstanding invoices after payment due date</a></li>
        </ol>
    </div>
</body>
</html>
