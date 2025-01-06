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
        <div class="card" style="background-color:light-blue">
            <div class="card-body">
            <h2 class="card-title">404</h2>
            <p class="card-text">We failed to find the page for '<?= request_uri() ?>'</p>
            <a href="/" class="btn btn-primary">Go to the main page</a>
        </div>
    </div>
</body>
</html>