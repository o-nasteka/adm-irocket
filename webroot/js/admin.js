function confirmDelete(){
    if ( confirm("Отправить билет на E-mail?") ){
        return true;
    } else {
        return false;
    }
}

$(function(){
    var $tableClass = $('.table');
//Make a clone of our table
    var $fixedColumn = $tableClass.clone().insertBefore($tableClass).addClass('fixed-column');

//Remove everything except for first column
    $fixedColumn.find('th:not(:first-child),td:not(:first-child)').remove();

//Match the height of the rows to that of the original table's
    $fixedColumn.find('tr').each(function (i, elem) {
        $(this).height($tableClass.find('tr:eq(' + i + ')').height());
    });
});

$(".no-submit").click(function(event) {
    event.preventDefault();

});