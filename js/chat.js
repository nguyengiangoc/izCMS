var chat = {};

chat.fetchMessages = function(){
    $.ajax({
        url: 'processor/get_message.php',
        type: 'POST',
        data: { method: 'fetch' },
        success: function(data) {
            $('.chat .message-box').html(data);
        }
    });
}

chat.throwMessages = function(message) {
    if ($.trim(message).length != 0) { //neu do dai cua tin nhan khac 0, tuc la tin nhan khong rong~
        $.ajax({
        url: 'processor/insert_message.php',
        type: 'POST',
        //data: { method: 'throw', content: message },
        data: "method=throw&content="+ message,
        success: function(response) {
            chat.fetchMessages();
            chat.entry.val(''); //dat gia tri cua khung entry trong trang web ve rong~
        }
    });
    }
}

chat.entry = $('.chat .entry'); //goi den thanh phan khung nhap tin nhan trong trang web
chat.entry.bind('keydown', function(e){ //
    if (e.keyCode === 13 && e.shiftKey === false) {
        chat.throwMessages($(this).val());        
    }
});

chat.interval = setInterval(chat.fetchMessages, 1000);
chat.fetchMessages();