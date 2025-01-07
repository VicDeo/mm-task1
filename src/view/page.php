<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="m-5">
    <div class="m-auto card" style="width:40rem" tabindex="-1">
        <div class="card-body">
            <h5 class="card-title">Task 1</h5>
            <p id="message-text" class="card-text"></p>
            <button id="btn-next" type="button" class="btn btn-primary">Next</button>
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

            let currentStep = 0;
            const steps = [
                {
                    "title": "Step 1. Create tables",
                    "action": "<?= link_to_route('db_create') ?>"
                },
                {
                    "title": "Step 2. Seed database",
                    "action": "<?= link_to_route('db_seed') ?>",
                    "showReport": false
                },
                {
                    "title": "Step 3. Raport Nadpłaty / Excess Payments report",
                    "action": "<?= link_to_route('report_excess') ?>",
                    "showReport": true
                },
                {
                    "title": "Step 4. Raport Niedopłaty / Underpayment Report",
                    "action": "<?= link_to_route('report_underpayment') ?>",
                    "showReport": true
                },
                {
                    "title": "Step 5. Raport Nierozliczone faktury po terminie płatnośći / Report Outstanding invoices after payment due date",
                    "action": "<?= link_to_route('report_outstanding') ?>",
                    "showReport": true
                }
            ];
            document.getElementById("message-text").innerText = steps[currentStep]['title'];

            document.getElementById("btn-next").addEventListener("click", function (event) {
                event.target.setAttribute('disabled', 'disabled');
                const onResponse = function(btn){
                    return function(json){
                        const hasReport = steps[currentStep]['showReport'];
                        currentStep++;
                        if (currentStep < steps.length){
                            document.getElementById("message-text").innerText = steps[currentStep]['title'];
                            btn.removeAttribute('disabled');
                        } else {
                            btn.style.display = 'none';
                        }
                        console.log(json);
                        if (hasReport){
                            showReport(steps[currentStep-1]['title'], json.data);
                        } 
                    }
                }(event.target);
                getData(steps[currentStep]['action'], onResponse);
            });

            function showReport(reportTitle, data){
                const newTitle = document.createElement("h4");
                newTitle.textContent = reportTitle;
                const newTable = document.createElement("table");
                newTable.classList.add("table")
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
                target.appendChild(newTitle);
                target.appendChild(newTable);
            }
        }
    </script>
</body>
</html>