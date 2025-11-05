$(document).ready(function () {
    var clickTimer = null;
    var clickDelay = 300; // ms
    var sortOrders = {};  // to track asc/desc per column

    $('.sortable th').each(function(index) {
        sortOrders[index] = true; // true = ascending initially
    });

    $('.sortable th').on('click', function (e) {
        var th = $(this);
        var colIndex = th.index();

        if (clickTimer == null) {
            clickTimer = setTimeout(function () {
                sortTableByColumn(colIndex, sortOrders[colIndex]);
                sortOrders[colIndex] = !sortOrders[colIndex]; // toggle order
                clickTimer = null;
            }, clickDelay);
        }
    });

    $('.sortable th').on('dblclick', function (e) {
        clearTimeout(clickTimer);
        clickTimer = null;

        var th = $(this);
        var colName = th.text();

        $('#filterModalLabel').text('Filter: ' + colName);

        $('#filterSearchInput').val('');
        $('#filterSelectInput').val('');

        var filterModal = new bootstrap.Modal(document.getElementById('filterModal'));
        filterModal.show();
    });

    function sortTableByColumn(colIndex, asc) {
        var table = $('table').first();
        var tbody = table.find('tbody');
        var rows = tbody.find('tr').toArray();

        rows.sort(function (a, b) {
            var cellA = $(a).children().eq(colIndex).text().trim();
            var cellB = $(b).children().eq(colIndex).text().trim();

            // Try to parse date or number
            var dateA = Date.parse(cellA);
            var dateB = Date.parse(cellB);
            if (!isNaN(dateA) && !isNaN(dateB)) {
                cellA = dateA;
                cellB = dateB;
            } else if (!isNaN(parseFloat(cellA)) && !isNaN(parseFloat(cellB))) {
                cellA = parseFloat(cellA);
                cellB = parseFloat(cellB);
            }

            if (cellA < cellB) return asc ? -1 : 1;
            if (cellA > cellB) return asc ? 1 : -1;
            return 0;
        });

        // Re-append sorted rows
        $.each(rows, function (index, row) {
            tbody.append(row);
        });

        $('.sortable th').each(function (i, el) {
            let originalText = $(el).text().replace(/[\u2191\u2193]/g, '').trim(); // remove old arrows
            $(el).text(originalText); // reset text
        });

        let th = $('.sortable th').eq(colIndex);
        let arrow = asc ? ' ↑' : ' ↓';
        let cleanedText = th.text().replace(/[\u2191\u2193]/g, '').trim(); // clean again in case of fast toggling
        th.text(cleanedText + arrow);
    }

    $('#applyFilterBtn').on('click', function () {
        var searchVal = $('#filterSearchInput').val().toLowerCase();
        var selectVal = $('#filterSelectInput').val();
        var filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
        filterModal.hide();

        alert('Filter applied! Search: ' + searchVal + ', Select: ' + selectVal);
    });
});
