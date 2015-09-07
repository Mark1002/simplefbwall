<div class="msg panel"  style="background-color: #f6f7f8;">
  <?php echo form_open("messageboard/addCmt", array("id" => "frm")) ?>
      <div>
          <textarea class="add" name="comment" placeholder="在想些什麼?"></textarea>
      </div>
      <div style="overflow: auto;">
          <button class="btn btn-primary" style="float: right;" type="submit">留言</button>
      </div>
  </form>
  <div class="valid"></div>
</div>
<script>
  $(document).ready(function() {

    $(document).on("submit", "#frm" ,function(event) {

        var userId = "<?php echo $_SESSION['id']; ?>";
        var cmt = $(".add").val();
        var token =$("input[name ='csrf_test_name']").val();
        $(".add").val("").css("height", "48px");
        console.log(cmt);

        $.ajax({

            url: "<?php echo base_url(); ?>index.php/messageboard/ax_addCmt",
            type: "POST",
            dataType: "json",
            data: {
                user_id: userId,
                comment: cmt,
                csrf_test_name: token
            },
        }).done(function(data) {

            if(data.isError === true) {

                $(".valid").html(data.error); 
            }
            else{

                 $(".valid p").remove();
                  var content = $(data.content);
                 $("#msg_list").prepend(content.fadeIn(600));
              }
        });

        event.preventDefault();
    });
  });
</script>