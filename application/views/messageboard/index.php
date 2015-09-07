<?php $this->load->view("messageboard/logout"); ?>
<?php $this->load->view("messageboard/add"); ?>
<div id="msg_list">
<?php foreach ($msg_items as $msg): ?>

  <div class="msg panel">
      <div class="comment" msg-id="<?php echo $msg->id; ?>">
      	  <div class="cmt_item">
      		 <img src="<?php echo base_url(); ?>images/profile.jpg" />
      	  	<span class="user-title"><?php echo $msg->username ?></span>
          </div>
      	  <div class="cmt_text"><?php echo $msg->msg; ?></div>
      	  <div class="edit_area"></div>
      	  <div class="dropdown tool">
      	  	<a class="tool-icon glyphicon glyphicon-chevron-down" data-toggle="dropdown" href="#"></a>
      	  	<ul class="dropdown-menu dropdown-menu-right">
				<li class="delete"><a href="#">刪除</a></li>
				<li class="edit"><a href="#">編輯貼文</a></li>
			</ul>	
		  </div>   	
       </div>
       <div class="msg_container">
       		<div class="msg_bar">
       			<?php 		
       				if($msg->islike) $like_href = "<a class='cmt-unlike' href='#'>收回讚</a>";
       				else $like_href = "<a class='cmt-like' href='#'>讚</a>";
       			?>
      	  		<span><?php echo $like_href; ?></span> · <span><a href="#">留言</a></span> · <span><a href="#">分享</a></span>
       		</div>
       		<div class="like_container">
       		<?php if($msg->like_num > 0) echo "<div class='likeSentence'><li class='glyphicon glyphicon-thumbs-up'></li>{$msg->likeSentence}</div>"; ?>
       		</div>
    		<div class="rsp_list">
      		<?php foreach ($msg_responses as $rsp): ?>

      			<?php if ($rsp->parents_id !== $msg->id) continue;?>

      				<div class="msg_item rsp_index" msg-id="<?php echo $rsp->id; ?>">
      					<img src="<?php echo base_url(); ?>images/profile.jpg" />
      					<div class="rsp_content">
      						<div>
      							<span class="user-title"><?php echo $rsp->username; ?></span>
      							<span class="rsp_text"><?php echo $rsp->msg; ?></span>
      						</div>
      						<div class="rsp-bar">
      							<?php 		
       								if($rsp->islike) $like_href = "<a class='rsp-unlike' href='#'>收回讚</a>";
       								else $like_href = "<a class='rsp-like' href='#'>讚</a>";
       							?>
      							<span><?php echo $like_href; ?></a></span>
      						 	<span class='rsp-likeNum'>
      						 		<?php if($rsp->like_num > 0) echo " · <a class='showLike' href='#'><li class='glyphicon glyphicon-thumbs-up'></li>{$rsp->like_num}</a>"; ?>
      						 	</span>
      						</div>
      						<div class="dropdown tool">
      	  						<a class="tool-icon glyphicon glyphicon-pencil" data-toggle="dropdown" href="#"></a>
      	  						<ul class="rsp_drop dropdown-menu dropdown-menu-right">
      	  							<li class="edit"><a href="#">編輯......</a></li>
									<li class="delete"><a href="#">刪除......</a></li>
								</ul>	
		  					</div>
      					</div>
      					<div class="edit_area"></div>
      				</div>

      			<?php endforeach ?>
  	    		<div class="msg_item">
  	    			<img src="<?php echo base_url(); ?>images/profile.jpg" />
  	   				<textarea class="response" placeholder="留言" ></textarea>
  	   			</div>
  			</div>
  		</div>
   </div>
<?php endforeach ?>
</div>
<script>
	$(document).ready(function() {

		$(document).on("focus", "textarea", function() {

			$("textarea").autosize();
		});

		$(document).on("click", ".showLike", function(event) {

			$this = $(this);
			var msg_id = $this.parents(".msg_item").attr("msg-id");
			var token = $("input[name ='csrf_test_name']").val();

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_userList",
				type: "POST",
				dataType: "json",
				data: {
					msg_id: msg_id,
					csrf_test_name: token
				}
			}).done(function(data) {

				var names = "";
				$.each(data, function(key, username) {
					names += "<div class='likelist-item'>"
					       + "<img src='<?php echo base_url();?>/images/profile.jpg'>" 
						   + "<span class='likeList-title'>" + username + "</span>"
					       + "</div>";
				});
				console.log(names);
				$(".likeModal").find(".modal-body").html(names);
			});
			$(".likeModal").modal();
			event.preventDefault();
		});

		$(document).on("click", ".cmt-like", function(event) {

			$this = $(this);
			var msg_id = $this.parents(".msg").find(".comment").attr("msg-id");
			var user_id = "<?php echo $_SESSION['id']; ?>";
			var token = $("input[name ='csrf_test_name']").val();

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_addLike",
				type: "POST",
				data: {
					msg_id: msg_id,
					user_id: user_id,
					csrf_test_name: token
				}
			}).done(function(data) {

				$this.parents(".msg").find(".like_container").html(data);
			    $this.attr("class", "cmt-unlike").text("收回讚");
			});
			event.preventDefault();
		});

		$(document).on("click", ".cmt-unlike", function(event) {

			$this = $(this);
			var msg_id = $this.parents(".msg").find(".comment").attr("msg-id");
			var user_id = "<?php echo $_SESSION['id']; ?>";
			var token =$("input[name ='csrf_test_name']").val();

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_likeDel",
				type: "POST",
				data: {
					msg_id: msg_id,
					user_id: user_id,
					csrf_test_name: token
				}
			}).done(function(data){

				$this.parents(".msg").find(".like_container").html(data);
				$this.attr("class", "cmt-like").text("讚");
			});
			event.preventDefault();
		});

		$(document).on("click", ".rsp-like", function(event) {

			$this = $(this);
			var msg_id = $this.parents(".msg_item").attr("msg-id");
			var user_id = "<?php echo $_SESSION['id']; ?>";
			var token =$("input[name ='csrf_test_name']").val();

			$this.attr("class", "rsp-unlike").text("收回讚");

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_addLike",
				type: "POST",
				data: {
					msg_id: msg_id,
					user_id: user_id,
					csrf_test_name: token
				}
			}).done(function(data) {

				$this.parents(".rsp-bar").find(".rsp-likeNum").html(" · <a class='showLike' href='#'><li class='glyphicon glyphicon-thumbs-up'></li>" + data + "</a>");
			});

			event.preventDefault();
		});

		$(document).on("click", ".rsp-unlike", function(event) {

			$this = $(this);
			var msg_id = $this.parents(".msg_item").attr("msg-id");
			var user_id = "<?php echo $_SESSION['id']; ?>";
			var token =$("input[name ='csrf_test_name']").val();

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_likeDel",
				type: "POST",
				data: {
					msg_id: msg_id,
					user_id: user_id,
					csrf_test_name: token
				}
			}).done(function(data) {

				$this.attr("class", "rsp-like").text("讚");

				if(data > 0) 
					$this.parents(".rsp-bar").find(".rsp-likeNum").html(" · <a class='showLike' href='#'><li class='glyphicon glyphicon-thumbs-up'></li>" + data + "</a>");
				else
					$this.parents(".rsp-bar").find(".rsp-likeNum ").html("");
			});																							
			event.preventDefault();
		});

		$(document).on("keydown", ".response", function(event) {

			var code = event.keyCode || event.which;

			if(code === 13 && !event.shiftKey) {

				event.preventDefault();

				var user_id = "<?php echo $_SESSION['id']; ?>";
				$this = $(this);
				var cmt_id = $this.parents(".msg").find(".comment").attr("msg-id");
				console.log(cmt_id);
				var rsp = $this.val();
				var token =$("input[name ='csrf_test_name']").val();
				$this.val("").css("height", "32px");

				if(rsp === "") return false;

				$.ajax({

					url: "<?php echo base_url(); ?>index.php/messageboard/ax_addRsp",
					type: "POST",
					dataType: "json",
					data: {
						user_id: user_id,
						comment_id: cmt_id,
						response: rsp,
						csrf_test_name: token
					}
				}).done(function(data) {
					console.log(data);
              				$this.parent().before(data.content);
				}).fail(function(jqXHR, textStatus, errorThrown) {

					alert("error");
            				console.log("Error:" + errorThrown);
            				console.log("Status:" + textStatus);
            				console.dir(jqXHR);
				});
			}	
		});

		$(document).on("click", ".rsp_list .delete", function(event) {

			$(".rspModal").data("ref", $(this)).modal();

			event.preventDefault();
		});

		$(".rspModal .delete").on("click", function() {

			$this = $(".rspModal").data("ref");
			var rsp_id = $this.parents(".msg_item").attr("msg-id");
			var token =$("input[name ='csrf_test_name']").val();

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_rspDel",
				type: "POST",
				data: {
					response_id: rsp_id,
					csrf_test_name: token

				}
			}).done(function() {

				$this.parents(".msg_item").remove();

			}).fail(function(jqXHR, textStatus, errorThrown) {

				alert("error");

            	console.log("Error:" + errorThrown);
            	console.log("Status:" + textStatus);
            	console.dir(jqXHR);

			});
		});

		$(document).on("click", ".comment .delete", function(event) {
			
			$(".cmtModal").data("ref", $(this)).modal();

			event.preventDefault();
		});

		$(".cmtModal .delete").on("click", function() {

			$this = $(".cmtModal").data("ref");
			var cmt_id = $this.parents(".msg").find(".comment").attr("msg-id");
			var token = $("input[name ='csrf_test_name']").val();
			console.log(token);

			$.ajax({

				url: "<?php echo base_url(); ?>index.php/messageboard/ax_cmtDel",
				type: "POST",
				data: { comment_id: cmt_id,
						csrf_test_name: token 
				}	
			}).done(function() {

				$(".cmtModal").modal("hide");
				$this.parents(".msg").fadeOut(600, function() {

					$this.parents(".msg").remove();
				});

			}).fail(function(jqXHR, textStatus, errorThrown) {

				alert("error");

            	console.log("Error:" + errorThrown);
            	console.log("Status:" + textStatus);
            	console.dir(jqXHR);
			});
		});

		$(document).on("click", ".comment .edit", function(event) {

			$this = $(this);
			var cmt;
			var token =$("input[name ='csrf_test_name']").val();
			var cmt_id = $this.parents(".comment").next().val();
			var content = $this.parents(".comment").find(".cmt_text").text();

			var form = $("<form class='edit_form' method='post' action='<?php echo base_url(); ?>index.php/messageboard/'></form>").css("border", "1px solid #b4bbcd");
		    var textarea = $("<textarea></textarea>").val(content).addClass("edit_cmt").autosize().css("border","0px");

			var editbar = $("<div class='editbar'>"
									+ "<button type='submit' class='edit_btn btn btn-primary'>完成編輯</button>"
									+ "<input type='button' value='取消' class='cancel_btn btn btn-default'/>"
							  	    + "</div>"
							).css("text-align", "right");

					$this.parents(".comment").find(".edit_area").html(form.append(textarea).append(editbar));
					$this.parents(".comment").find(".cmt_text").css("display","none");

					event.preventDefault();
		});

		$(document).on("click", ".cancel_btn", function() {

				$this = $(this);
				$this.parents(".comment").find(".cmt_text").removeAttr("style");
				$this.parents(".edit_area").empty();
		});
		
		$(document).on("submit", ".edit_form", function(event) {

				$this = $(this);
				var edit_text = $this.find(".edit_cmt").val();
				var token = $("input[name ='csrf_test_name']").val();
				var cmt_id = $this.parents(".msg").find(".comment").attr("msg-id");

				$.ajax({
					url: "<?php echo base_url(); ?>index.php/messageboard/ax_editCmt",
					type: "POST" ,
					dataType: "json",
					data: { comment_id: cmt_id,
							comment: edit_text,
							csrf_test_name: token 
						}
				}).done(function(data) {

					$this.parents(".comment").find(".cmt_text").html(data[0].msg).removeAttr("style");
					$this.parent().empty();
				});

				event.preventDefault();
		});

		$(document).on("click", ".rsp_list .edit", function(event) {

			$this = $(this);

			$this.parents(".rsp_list").find(".rsp_content").removeAttr("style");
			$this.parents(".rsp_list").find(".edit_area").empty();
			$this.parents(".rsp_list").find(".msg_item").last().css("display", "none");

			var rsp_text = $this.parents(".rsp_content").find(".rsp_text").text();
			var textarea = $("<textarea></textarea>").val(rsp_text).addClass("edit_rsp").autosize();
			var editbar = $("<span><span>按esc鍵可</span><span><a href='#'>取消</a></span><span>。</span></span>");

			$this.parents(".rsp_content").next().html(textarea).append(editbar);
			$this.parents(".rsp_content").css("display", "none");
			$this.parents(".rsp_content").next().find(".edit_rsp").focus();

			event.preventDefault();
		});

		$(document).on("click", ".edit_area a", function(event) {

			$this = $(this);
			$this.parents(".edit_area").prev().removeAttr("style");
			$this.parents(".rsp_list").find(".msg_item").last().removeAttr("style");
			$this.parents(".edit_area").empty();
			event.preventDefault();
		});

		$(document).on("keydown", ".edit_rsp", function(event) {

			var code = event.keyCode || event.which;

			$this = $(this);

			if(code === 13 && !event.shiftKey) {

				event.preventDefault();

				var edit_text = $this.val();
				var token =$("input[name ='csrf_test_name']").val();
				var rsp_id = $this.parents(".msg_item").attr("msg-id");

				$this.val("").css("height", "32px");

				if(edit_text === "") return false;

				$.ajax({
					url: "<?php echo base_url(); ?>index.php/messageboard/ax_editRsp",
					dataType: "json",
					type: "POST",
					data: {
						response: edit_text,
						response_id: rsp_id,
						csrf_test_name: token 
					}
				}).done(function(data) {

					$this.parents(".msg_item").find(".rsp_content").find(".rsp_text").html(data[0].msg);
					$this.parents(".msg_item").find(".rsp_content").removeAttr("style");
					$this.parents(".rsp_list").find(".msg_item").last().removeAttr("style");
					$this.parent().empty();
				});
			}
			else if(code === 27) {
				$this.parents(".msg_item").find(".rsp_content").removeAttr("style");
				$this.parents(".rsp_list").find(".msg_item").last().removeAttr("style");
				$this.parent().empty();
			}
		});
	});
</script>
<div class="modal fade cmtModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
        	<span aria-hidden="true">&times;</span>
        	<span class="sr-only">Close</span>
        </button>
        <div class="modal-title">刪除貼文</div>
      </div>
      <div class="modal-body">
        <span>你確定要刪除這篇貼文？</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary delete" data-dismiss="modal">刪除貼文</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade rspModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
        	<span aria-hidden="true">&times;</span>
        	<span class="sr-only">Close</span>
        </button>
        <div class="modal-title">刪除留言</div>
      </div>
      <div class="modal-body">
        <span>你確定要刪除這個留言嗎？</span>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary delete" data-dismiss="modal">刪除</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade likeModal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
        	<span aria-hidden="true">&times;</span>
        	<span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">覺得這很讚的人</h4>
      </div>
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>