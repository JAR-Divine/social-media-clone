$(document).ready(function(){
    //Button for profile post

    $('#submit_profile_post').click(function(){
        
        $.ajax({
            type: "POST",
            url: "private/functions/ajax/ajax_profile_post.php",
            data: $('.profile_post').serialize(),
            success: function(msg) {
                $("#post_form").modal('hide');
                location.reload();
            },
            error: function() {
                alert('Unable to Post');
            }
        });
    });
});