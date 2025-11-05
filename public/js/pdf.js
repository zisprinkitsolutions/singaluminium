$(document).ready(function () {
    $('.extra-download-button').click(function () {
        var targetQuery = $(this).attr('data-query');
        var parsedQuery;
        parsedQuery = JSON.parse(targetQuery.replace(/'/g, '"'));
        var url = $(this).data('url');

        var params = new URLSearchParams(parsedQuery);
        var newUrl = url + (url.includes('?') ? '&' : '?') + params.toString();
        window.open(newUrl, '_blank');
        // $('#pdf-preview-frame').attr('src', newUrl);
        // $('#pdf-preview-container').fadeIn();
    });

    $('.extra-print-btn').click(function() {
        // Get the year and month for the selected row
        var body = $(this).data('body');
        var table = $(this).data('table');

        var rowsToPrint = $(`.body`);

        var printContent = $("<table class='table table-sm'>");
        var headerRow = $("<tr>");

        $(`.${table} .header th`).each(function(){
            var header = $("<th>").text($(this).text());
            headerRow.append(header);
        });
        printContent.append($("<thead>").append(headerRow));

        printContent.append(rowsToPrint);

        var newWindow = window.open('', '', 'height=500, width=800');
        newWindow.document.write('<html><head><title>Print Selected Data</title></head><body>');
        newWindow.document.write(printContent[0].outerHTML);
        newWindow.document.write('</body></html>');
        newWindow.document.close();
        newWindow.print();
    });

    $('#close-preview').click(function () {
        $('#pdf-preview-container').fadeOut();
    });
});
