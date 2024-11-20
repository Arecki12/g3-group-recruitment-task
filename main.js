const $errorDiv = $('#errors_container');
buildResultsContainer();
fetchCounters();
$('#account_group').attr('hidden', 'hidden');

function updateRequiredAttribute() {
    if ($('#choose_1').is(':checked')) {
        $('#account_group').removeAttr('hidden');
        $('#account').attr('required', 'required');
    } else {
        $('#account_group').attr('hidden', 'hidden');
        $('#account').removeAttr('required');
    }
}

$('#choose_1, #choose_2').on('change', updateRequiredAttribute);

function saveForm(event) {
    event.preventDefault();
    const $form = $('#form');
    if (!$form[0].reportValidity()) {
        return;
    }

    const formData = new FormData($form[0]);

    $.ajax({
        url: 'index.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                buildErrorContainer([data.error]);
                return;
            }
            fetchCounters();
            buildResultsContainer();
        },
        error: function(xhr, status, error) {
            buildErrorContainer([error]);
        }
    });
}

function fetchCounters() {
    $.getJSON('index.php?option=counter')
        .done(function(data) {
            if (data.error) {
                buildErrorContainer([data.error]);
                return;
            }
            $('#surname_counter').text(data.counterSurname);
            $('#domain_counter').text(data.counterDomain);
        })
        .fail(function(xhr, status, error) {
            console.error('error:', error);
            buildErrorContainer([error]);
        });
}

function buildErrorContainer(errors) {
    $errorDiv.html('Nie można wysłać formularza z powodu błędów:');
    const $ul = $('<ul>');
    errors.forEach(error => {
        $('<li>').text(error).appendTo($ul);
    });
    $errorDiv.append($ul);
}

function buildResultsContainer(sortKey = '', sortDirection = 'ASC') {
    const $resultsDiv = $('#results_container');
    const url = new URL('index.php?option=results', window.location.origin);
    if (sortKey) {
        url.searchParams.append('sortKey', sortKey);
        url.searchParams.append('sortDirection', sortDirection);
    }

    $.getJSON(url.toString())
        .done(function(data) {
            if (data.length === 0) {
                buildErrorContainer(["Brak danych"]);
                return;
            }
            if (data.error) {
                buildErrorContainer([data.error]);
                return;
            }

            $resultsDiv.empty();
            const $table = $('<table>');
            const $thead = $('<thead>');
            const $tbody = $('<tbody>');

            const headers = Array.isArray(data[0]) ? data[0] : Object.keys(data[0]);

            const $tr = $('<tr>');
            headers.forEach(header => {
                const $th = $('<th>').text(header);
                const $sortIcon = $('<span>').text(' (X)');
                $th.append($sortIcon);

                $th.on('click', function() {
                    const newDirection = (sortDirection === 'ASC' || sortDirection === '') ? 'DESC' : 'ASC';
                    buildResultsContainer(header, newDirection);
                });

                $tr.append($th);
            });
            $thead.append($tr);
            $table.append($thead);

            data.forEach(row => {
                const $tr = $('<tr>');
                const rowValues = Array.isArray(row) ? row : Object.values(row);

                rowValues.forEach(value => {
                    $('<td>').text(value).appendTo($tr);
                });
                $tbody.append($tr);
            });

            $table.append($tbody);
            $resultsDiv.append($table);
        })
        .fail(function(xhr, status, error) {
            buildErrorContainer([error]);
        });
}

$('#form').on('submit', saveForm);
