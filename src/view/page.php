<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        th {cursor: pointer}
        th.sort-asc:before{
            content:" \2193"
        }
        th.sort-desc:before{
            content:" \2191"
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Task 1</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-link disabled" data-step="0" href="#">Raport Nadpłaty / Excess Payments</a>
            <a class="nav-link disabled" data-step="1" href="#">Raport Niedopłaty / Underpayment</a>
            <a class="nav-link disabled" data-step="2" href="#">Raport Nierozliczone faktury po terminie płatnośći / Outstanding invoices after payment due date</a>
        </div>
        </div>
    </div>
    </nav>

    <div class="spinner-wrapper position-absolute w-100 h-100 d-flex flex-column align-items-center bg-white justify-content-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="report-data" class="m-5">
    </div>
    <script>
        window.onload = function () {
            async function getData(url, cb) {
                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        throw new Error(`Response status: ${response.status}`);
                    }

                    const json = await response.json();
                    cb(json);
                } catch (error) {
                    console.error(error.message);
                }
            }

            const showSpinner = (state) => {
                const spinner = document.getElementsByClassName("spinner-wrapper")[0];
                spinner.style.display = state ? 'flex' : 'none';
                if (state){
                    spinner.classList.add("d-flex");
                } else {
                    spinner.classList.remove("d-flex");
                }
            }

            fetch("<?= link_to_route('db_create') ?>")
            .then(() => {
                return fetch("<?= link_to_route('db_seed') ?>");
            })
            .then(() => {
                showSpinner(false);
                for (const link of document.getElementsByClassName("nav-link")){
                    link.classList.remove("disabled");
                }
            });

            const steps = [
                {
                    "title": "Raport Nadpłaty / Excess Payments report",
                    "action": "<?= link_to_route('report_excess') ?>",
                    "sort_by": {}
                },
                {
                    "title": "Raport Niedopłaty / Underpayment Report",
                    "action": "<?= link_to_route('report_underpayment') ?>",
                    "sort_by": {}
                },
                {
                    "title": "Raport Nierozliczone faktury po terminie płatnośći / Report Outstanding invoices after payment due date",
                    "action": "<?= link_to_route('report_outstanding') ?>",
                    "sort_by": {}
                }
            ];

            document.getElementsByTagName("body")[0].addEventListener("click", function (event) {
                event.target.setAttribute('disabled', 'disabled');

                if (event.target.classList.contains("nav-link")
                    && typeof event.target.dataset.step === 'string'
                ) {
                    const currentStep = parseInt(event.target.dataset.step);
                    const onResponse = function(json){
                        console.log(json);
                        const metadata = steps[currentStep];
                        metadata['id'] = currentStep;
                        showReport(metadata, json.data);
                        showSpinner(false);
                    }
                    showSpinner(true);
                    console.log(steps[currentStep]);
                    const url = new URL(steps[currentStep]['action']);
                    for (let i in steps[currentStep]["sort_by"]){
                        url.searchParams.set("sort_by", i);
                        url.searchParams.set("sort_dir", steps[currentStep]["sort_by"][i]);
                    }

                    getData(url, onResponse);
                }
            });

            function showReport(metadata, data){
                const newTitle = document.createElement("h4");
                newTitle.textContent = metadata['title'];

                const newTable = document.createElement("table");
                newTable.classList.add("table");
                newTable.classList.add("table-bordered");
                newTable.dataset.step = metadata["id"];

                const newHeading = document.createElement("tr");
                for (columnName in data[0]){
                    const newColumn = document.createElement("th");
                    newColumn.textContent = columnName;
                    newHeading.appendChild(newColumn);
                }
                newTable.appendChild(newHeading);

                for (row of data){
                    const newRow = document.createElement("tr");
                    for (columnName in row){
                        const newColumn = document.createElement("td");
                        newColumn.textContent = row[columnName];
                        newRow.appendChild(newColumn);
                    }
                    newTable.appendChild(newRow);
                }

                const target = document.getElementById('report-data');
                target.innerHTML = '';
                target.appendChild(newTitle);
                target.appendChild(newTable);
            }

            const divReport = document.getElementById("report-data");
            divReport.addEventListener("click", (event) => {
                const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
                const comparer = (idx, asc) => (a, b) => ((v1, v2) => 
                v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
    )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

            if (event.target.tagName === 'TH') {
                const th = event.target;
                const table = th.closest('table');
                const reportId = table.dataset.step;
                const field = th.textContent;
                if (steps[reportId]['sort_by'][field] === 'ASC') {
                    steps[reportId]['sort_by'][field] = 'DESC';
                } else {
                    steps[reportId]['sort_by'] = {};
                    steps[reportId]['sort_by'][field] = 'ASC';
                }

                const className = th.classList.contains('sort-asc') ? 'sort-desc' : 'sort-asc';
                for (const el of th.parentNode.children){
                    el.classList.remove('sort-asc'); 
                    el.classList.remove('sort-desc');
                };
                th.classList.add(className);
                document.getElementsByClassName("nav-link")[reportId].click();

                /*
                const table = th.closest('table');
                Array.from(table.querySelectorAll('tr:nth-child(n+2)'))
                    .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                    .forEach(tr => table.appendChild(tr));
                */
            }
            });
        }
    </script>
</body>
</html>