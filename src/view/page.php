<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        th {
            cursor: pointer
        }

        th.sort-asc:before {
            content: " \2193"
        }

        th.sort-desc:before {
            content: " \2191"
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Task 1</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link disabled" data-step="0" href="#">Raport Nadpłaty / Excess Payments</a>
                    <a class="nav-link disabled" data-step="1" href="#">Raport Niedopłaty / Underpayment</a>
                    <a class="nav-link disabled" data-step="2" href="#">Raport Nierozliczone faktury po terminie
                        płatnośći / Outstanding invoices after payment due date</a>
                </div>
            </div>
        </div>
    </nav>

    <div
        class="spinner-wrapper position-absolute w-100 h-100 d-flex flex-column align-items-center bg-white justify-content-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="report-data" class="m-5">
    </div>
    <script>
        window.onload = function () {
            const steps = [
                {
                    "title": "Raport Nadpłaty / Excess Payments report",
                    "action": "<?= link_to_route('report_excess') ?>",
                    "sort_by": {},
                    "filter_by": {}
                },
                {
                    "title": "Raport Niedopłaty / Underpayment Report",
                    "action": "<?= link_to_route('report_underpayment') ?>",
                    "sort_by": {},
                    "filter_by": {}
                },
                {
                    "title": "Raport Nierozliczone faktury po terminie płatnośći / Report Outstanding invoices after payment due date",
                    "action": "<?= link_to_route('report_outstanding') ?>",
                    "sort_by": {},
                    "filter_by": {}
                }
            ];

            fetch("<?= link_to_route('db_create') ?>")
                .then(() => {
                    return fetch("<?= link_to_route('db_seed') ?>");
                })
                .then(() => {
                    showSpinner(false);
                    document.getElementsByClassName("nav-link")[0].click();
                });

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
                if (state) {
                    spinner.classList.add("d-flex");
                    for (const link of document.getElementsByClassName("nav-link")) {
                        link.classList.add("disabled");
                    }
                } else {
                    spinner.classList.remove("d-flex");
                    for (const link of document.getElementsByClassName("nav-link")) {
                        link.classList.remove("disabled");
                    }
                }
            }

            const applyFilter = (event) => {
                if (event.key === "Enter"
                    && event.target.dataset.filter
                    && event.target.dataset.step
                ) {
                    const reportId = event.target.dataset.step;
                    steps[reportId]['filter_by'][event.target.dataset.filter] = event.target.value;
                    document.getElementsByClassName("nav-link")[reportId].click();
                }
            }

            const applySort = (event) => {
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
                for (const el of th.parentNode.children) {
                    el.classList.remove('sort-asc', 'sort-desc');
                };
                th.classList.add(className);
                document.getElementsByClassName("nav-link")[reportId].click();
            }

            const loadReport = (event) => {
                event.target.setAttribute('disabled', 'disabled');
                const currentStep = parseInt(event.target.dataset.step);
                const onResponse = function (json) {
                    console.log(json);
                    const metadata = steps[currentStep];
                    metadata['id'] = currentStep;
                    showReport(metadata, json.data);
                    showSpinner(false);
                }

                showSpinner(true);
                console.log(steps[currentStep]);
                const url = new URL(steps[currentStep]['action']);
                for (let i in steps[currentStep]["sort_by"]) {
                    url.searchParams.set("sort_by", i);
                    url.searchParams.set("sort_dir", steps[currentStep]["sort_by"][i]);
                }
                let filterCount = 0;
                for (let i in steps[currentStep]["filter_by"]) {
                    filterCount++;
                    url.searchParams.set("filter_" + filterCount, i);
                    url.searchParams.set("filter_value_" + filterCount, steps[currentStep]["filter_by"][i]);
                }

                getData(url, onResponse);
            }

            const showReport = (metadata, data) => {
                const newTitle = document.createElement("h4");
                newTitle.textContent = metadata['title'];

                const newTable = document.createElement("table");
                newTable.classList.add("table", "table-bordered");
                newTable.dataset.step = metadata["id"];

                const thead = document.createElement("thead");
                const newHeading = document.createElement("tr");
                for (columnName in data[0]) {
                    const newColumn = document.createElement("th");
                    newColumn.textContent = columnName;
                    newColumn.classList.add('sortable');
                    if (typeof metadata["sort_by"][columnName] !== 'undefined') {
                        const className = metadata["sort_by"][columnName] === 'DESC' ? 'sort-desc' : 'sort-asc';
                        newColumn.classList.add(className);
                    }
                    newHeading.appendChild(newColumn);
                }
                thead.appendChild(newHeading);
                newTable.appendChild(thead);

                const tbody = document.createElement("tbody");
                const filterRow = document.createElement("tr");
                for (columnName in data[0]) {
                    const newColumn = document.createElement("td");
                    const input = document.createElement("input");
                    input.dataset.filter = columnName;
                    input.dataset.step = metadata['id'];
                    if (typeof metadata['filter_by'][columnName] !== 'undefined') {
                        input.value = metadata['filter_by'][columnName];
                    }
                    newColumn.appendChild(input);
                    filterRow.appendChild(newColumn);
                }
                tbody.append(filterRow);
                newTable.append(tbody);

                for (row of data) {
                    const newRow = document.createElement("tr");
                    for (columnName in row) {
                        const newColumn = document.createElement("td");
                        newColumn.textContent = row[columnName];
                        newRow.appendChild(newColumn);
                    }
                    tbody.appendChild(newRow);
                }

                const target = document.getElementById('report-data');
                target.innerHTML = '';
                target.appendChild(newTitle);
                target.appendChild(newTable);
            }

            const body = document.getElementsByTagName("body")[0];
            body.addEventListener("keyup", applyFilter);
            body.addEventListener("click", (event) => {
                if (event.target.classList.contains("nav-link")
                    && typeof event.target.dataset.step === 'string'
                ) {
                    loadReport(event);
                }
                if (event.target.classList.contains("sortable")) {
                    applySort(event);
                }
            });
        }
    </script>
</body>

</html>