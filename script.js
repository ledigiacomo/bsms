$(document).ready(function(){
$('input[name="chk1"]').change(function(){
    checkQuestion("Q1)", this.value);
});
$('input[name="chk2"]').change(function(){
    checkQuestion("Q2)", this.value);
});
$('input[name="chk3"]').change(function(){
    checkQuestion("Q3)", this.value);
});

function checkQuestion(text, value) {
    if ($('#comment').text().indexOf(text)<0)
        $('#comment').text($('#comment').text() + text + value + "\n");
    else {
        var oldValue = 'Yes';
        if (value==='Yes') oldValue = 'No';
        $('#comment').text($('#comment').text().replace(text + oldValue, text + value));
    }
}
}); 