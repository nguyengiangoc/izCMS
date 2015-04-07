$(document).ready(function(){ //trang web phai dc load het truoc khi js lam viec, ham nay nghia la khi moi thu san sang moi lam viec
    $('#email').change(function() { //kich hoat ham khi mot phan tu duoc thay doi, luc nay dang o trong phan tu co id la email
        var email = $(this).val(); //lay bien bang chinh gia tri cua phan tu dang duoc xet, do la phan tu co id la email
        if(email.length > 8) {
            $('#available').html('<span class="check">Checking availability...</span>');
            //neu nguoi dung dang nhap mail ma nhieu hon 8 ki tu thi bao la dang kiem tra
            $.ajax({
                type: "get", //gui du lieu den file dc noi ra o url qua phuong thuc get
                url: "check.php", //duong link den file php kiem tra csdl
                data: "email=" + email, //du lieu duoc gui di qua phuong thuc o tren den url o tren
                success: function(response) {
                    if(response == "YES") {
                        //ham ajax gui di cac thong so, neu thanh cong
                        $('#available').html('<span class="avai">Email is available for registration.</span>');
                    } else if (response == "NO") {
                        $('#available').html('<span class="not-avai">Email is NOT available for registration.</span>');
                    } else if (response == "FORMAT") {
                        $('#available').html('<span class="not-avai">Email is in wrong format.</span>');
                    }
                }
            });
        } else {
            $('#available').html('<span class="not-avai">Email is too short.</span>');
            //neu nguoi dung dang nhap mail ma it hon 8 ki tu thi bao la qua ngan
        }
    });
});