$(document).ready(function() {
    $('.remove').click(function(){
        var container = $(this).parent(); //doi voi nut delete thi doi tuong parent la <li>, phai lay <li> vi delete la xoa ca <li>
        var cid = $(this).attr('id'); //lay cid tu attribute ID cua nut delete
        var string = 'cmt_id='+ cid; //du lieu muon gui di
        
        $.ajax({
            type: "POST",
            url: "processor/delete_comment.php",
            data: string,
            success: function() {
                container.slideUp('slow', function() {container.remove();});
            }
        });
    });
});