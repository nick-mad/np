$(document).ready(function () {
    $('#ajax').click(function () {
        $.ajax({
            url: '/ajax',
            type: 'POST',
            dataType: 'json',
            data: $('form').serialize(),
            success: function(data)
            {
                if(data['success'] === true){
                    $('#response').html('<p class="success">'+data['message'] +'</p>')
                    $('textarea').val('');
                } else {
                    $('#response').html('<p class="error"><b>Ошибка!</b> '+data['message'] +'</p>')
                }
            }
        })
    })
});