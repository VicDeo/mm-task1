<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                    <a class="nav-link disabled" data-url="<?= link_to_route('report_excess') ?>"
                        title="Raport Nadpłaty / Excess Payments report" href="#">Raport
                        Nadpłaty / Excess Payments</a>
                    <a class="nav-link disabled" data-url="<?= link_to_route('report_underpayment') ?>"
                        title="Raport Niedopłaty / Underpayment Report" href="#">Raport
                        Niedopłaty / Underpayment</a>
                    <a class="nav-link disabled" data-url="<?= link_to_route('report_outstanding') ?>"
                        title="Raport Nierozliczone faktury po terminie płatnośći / Report Outstanding invoices after payment due date"
                        href="#">Raport
                        Nierozliczone faktury po terminie
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
            class ReportTable extends HTMLElement {
                constructor() {
                    super();

                    if (this.hasAttribute('src')) {
                        this.src = this.getAttribute('src');
                    }

                    if (!this.src) return;

                    this.sortField = this.hasAttribute('sortField') ? this.getAttribute('sortField') : '';
                    this.sortDirection = this.hasAttribute('sortDirection') ? this.getAttribute('sortDirection') : '';
                    this.filters = {};

                    this.onToggleDirection = this.onToggleDirection.bind(this);
                    this.onApplyFilter = this.onApplyFilter.bind(this);

                    const shadow = this.attachShadow({
                        mode: 'open'
                    });

                    const style = document.createElement('style');
                    style.textContent = `
th {
    cursor: pointer
}

th.sort-asc:before {
    content: " \u2193"
}

th.sort-desc:before {
    content: " \u2191"
    }
                    `;
                    shadow.appendChild(style);

                    const table = document.createElement('table');
                    table.classList.add("table", "table-bordered");
                    const thead = document.createElement('thead');
                    const tbody = document.createElement('tbody');
                    table.append(thead, tbody);
                    shadow.append(table);
                }

                attributeChangedCallback(name, oldValue, newValue) {
                    if (name === 'src') {
                        this.src = newValue;
                        this.load();
                    }
                }

                async load() {
                    showSpinner(true);
                    const url = new URL(this.src);
                    url.searchParams.set("sort_by", this.sortField);
                    url.searchParams.set("sort_dir", this.sortDirection);

                    let filterCount = 0;
                    for (let i in this.filters) {
                        filterCount++;
                        url.searchParams.set("filter_" + filterCount, i);
                        url.searchParams.set("filter_value_" + filterCount, this.filters[i]);
                    }
                    let result = await fetch(url);
                    this.data = await result.json();
                    this.render();
                    showSpinner(false);
                }

                static get observedAttributes() {
                    return [
                        'src'
                    ];
                }

                onToggleDirection(evt) {
                    if (evt.target.innerText === this.sortField) {
                        if (this.sortDirection === 'ASC') {
                            this.sortDirection = 'DESC'
                        } else {
                            this.sortDirection = 'ASC'
                        }
                    } else {
                        this.sortField = evt.target.innerText
                        this.sortDirection = 'ASC'
                    }
                    this.load();
                }

                onApplyFilter(evt) {
                    if (evt.key === "Enter"
                        && evt.target.dataset.filter) {
                        this.filters[evt.target.dataset.filter] = evt.target.value;
                        this.load();
                    }
                }

                render() {
                    this.renderHeader();
                    this.renderBody();
                }

                renderHeader() {
                    const table = this.shadowRoot.querySelector('table');
                    table.querySelector('caption') && table.querySelector('caption').remove();

                    if (this.hasAttribute('title')) {
                        const caption = document.createElement('caption');
                        caption.textContent = this.getAttribute('title');
                        table.append(caption);
                    }

                    const newHeading = document.createElement("tr");
                    for (const columnName of this.data.data.head) {
                        const newColumn = document.createElement("th");
                        newColumn.textContent = columnName;
                        newColumn.classList.add('sortable');
                        if (columnName === this.sortField) {
                            const className = this.sortDirection === 'DESC' ? 'sort-desc' : 'sort-asc';
                            newColumn.classList.add(className);
                        }
                        newHeading.appendChild(newColumn);
                    }
                    const thead = this.shadowRoot.querySelector('thead');
                    thead.innerHTML = '';
                    thead.appendChild(newHeading);

                    const filterRow = document.createElement("tr");
                    for (const columnName of this.data.data.head) {
                        const newColumn = document.createElement("td");
                        newColumn.classList.add('filterable');
                        const input = document.createElement("input");
                        input.dataset.filter = columnName;
                        if (typeof this.filters[columnName] !== 'undefined') {
                            input.value = this.filters[columnName];
                        }

                        newColumn.appendChild(input);
                        filterRow.appendChild(newColumn);
                    }
                    thead.append(filterRow);

                    this.shadowRoot.querySelectorAll('th.sortable').forEach(t => {
                        t.addEventListener('click', this.onToggleDirection, false);
                    });

                    this.shadowRoot.querySelectorAll('td.filterable input').forEach(t => {
                        t.addEventListener('keyup', this.onApplyFilter, false);
                    });
                }

                renderBody() {
                    const tbody = this.shadowRoot.querySelector('tbody');
                    tbody.innerHTML = '';
                    for (const row of this.data.data.body) {
                        const newRow = document.createElement("tr");
                        for (const columnName in row) {
                            const newColumn = document.createElement("td");
                            newColumn.textContent = row[columnName];
                            newRow.appendChild(newColumn);
                        }
                        tbody.appendChild(newRow);
                    }
                }
            }

            fetch("<?= link_to_route('db_create') ?>")
                .then(() => {
                    return fetch("<?= link_to_route('db_seed') ?>");
                })
                .then(() => {
                    showSpinner(false);
                    document.getElementsByClassName("nav-link")[0].click();
                });

            const showSpinner = (state) => {
                const spinner = document.getElementsByClassName("spinner-wrapper")[0];
                spinner.style.display = state ? 'flex' : 'none';
                const method = state ? 'add' : 'remove';
                spinner.classList[method]("d-flex");
                for (const link of document.getElementsByClassName("nav-link")) {
                    link.classList[method]("disabled");
                }
            }

            const body = document.getElementsByTagName("body")[0];
            body.addEventListener("click", (event) => {
                if (event.target.classList.contains("nav-link")
                    && typeof event.target.dataset.url === 'string'
                ) {
                    document.querySelectorAll('.nav-link').forEach(a => {
                        a.classList.remove('active');
                    });
                    event.target.classList.add('active');

                    const target = document.getElementById('report-data');
                    target.innerHTML = `<report-table src="${event.target.dataset.url}" title="${event.target.getAttribute('title')}">`
                }
            });
            customElements.define('report-table', ReportTable);
        }
    </script>
</body>

</html>