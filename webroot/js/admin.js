function confirmDelete(){
    if ( confirm("Delete this item?") ){
        return true;
    } else {
        return false;
    }
}

$(function(){
    var $table = $('.table');
    //Make a clone of our table
    var $fixedColumn = $table.clone().insertBefore($table).addClass('fixed-column');

    //Remove everything except for first column
    $fixedColumn.find('th:not(:first-child),td:not(:first-child)').remove();

    //Match the height of the rows to that of the original table's
    $fixedColumn.find('tr').each(function (i, elem) {
        $(this).height($table.find('tr:eq(' + i + ')').height());
    });
});