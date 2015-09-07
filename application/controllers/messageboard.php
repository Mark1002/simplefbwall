<?php
session_start();
class MessageBoard extends CI_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model("msg_model");
		$this->load->helper("url");
		$this->load->helper("form");
		$this->load->helper('date');
		$this->load->library('typography');
	}

	public function index() {

		if(!isset($_SESSION["id"])) {

			redirect("messageboard/login", "refresh");
		}

		$post_date = "1411723475";
		$datestring = "m月d日h時";
		$now = time();
		$timezone = "UP8";
		$daylight_saving = TRUE;
		print_r(date($datestring, $now));
		print_r("/");
		print_r(timespan($post_date, $now));

		$data["title"] = "塗鴉牆";
		$data["include"] = "index";
		$data["msg_items"] = $this->msg_model->get_msg(0);
		$data["msg_responses"] = $this->msg_model->get_msg();

		$this->load->view("messageboard/templates", $data);
	}

	public function login() {

		if(isset($_SESSION["id"])) {

			redirect("messageboard/index", "refresh");
		}

		$this->load->library("form_validation");

		$data["title"] = "塗鴉牆登入";
		$data["include"] = "login";

		$config = array(
        			array("field" => "account", 
        				  "label" => "account", 
        				  "rules" => "required"
        			),
        			array("field" => "password", 
        				  "label" => "password", 
        				  "rules" => "required"
        			),
        );

		$this->form_validation->set_rules($config);

		if($this->form_validation->run() === FALSE) {

			$this->load->view("messageboard/templates", $data);
		}
		else {

			$data = $this->msg_model->get_userInfo();

			if(empty($data)) {

				redirect("messageboard/login", "refresh");
			}

			if( ($data[0]->account === $this->input->post("account")) && ($data[0]->password === $this->input->post("password")) ) {

				$_SESSION["id"] = $data[0]->id;

				redirect("messageboard/index", "refresh");
			}
			else {
				redirect("messageboard/login", "refresh");
			}
		}
	}

	public function logout() {

		unset($_SESSION["id"]);
		redirect("messageboard/login", "refresh");
	}

	public function ax_addRsp() {

		$response = htmlspecialchars($this->input->post("response"));
		$response = $this->typography->nl2br_except_pre($response);

		$data = array(
			"user_id" => $this->input->post("user_id"),
			"msg" => $response,
			"parents_id" => $this->input->post("comment_id")
		);

		$this->msg_model->set_msg($data);

		$name = $this->msg_model->get_name($_POST["user_id"]);
		$rsp = $this->msg_model->get_rsp();
		$rsp = get_object_vars($rsp[0]);
		$data = array_merge(array("username" => $name), $rsp);
		$content = $this->renderHtml($data);
		echo json_encode($content);
	}

/*--------------------------ajax function--------------------------*/

	public function ax_addCmt() {

		$this->load->library("form_validation");

		$config = array(
        			array("field" => "comment", 
        				  "label" => "comment", 
        				  "rules" => "required"
        			)
        		);

		$this->form_validation->set_rules($config);

        		if($this->form_validation->run() === FALSE) {
        			echo json_encode( array("isError" => TRUE, "error" => validation_errors() ) );
        		}
        		else {

        				$comment = htmlspecialchars($this->input->post("comment"));
        				$comment = $this->typography->nl2br_except_pre($comment);

        				$data = array(
					"user_id" => $this->input->post("user_id"),
					"msg" => $comment,
					"parents_id" => 0
				);

				$this->msg_model->set_msg($data);

				$name = $this->msg_model->get_name($this->input->post("user_id"));
				$cmt = $this->msg_model->get_cmtObj();
				$cmt = get_object_vars($cmt[0]);
				$data = array_merge(array("username" => $name), $cmt);
				$content = $this->renderHtml($data);
				echo json_encode($content);
        		}
	}

	public function ax_addLike() {

		$islike = $this->msg_model->isUserLike($this->input->post("msg_id"));

		if($islike === TRUE) return FALSE;

		$data = array(
			"msg_id" => $this->input->post("msg_id"),
			"user_id" => $this->input->post("user_id")
		);
		
		$this->msg_model->set_like($data);
		$like_num = $this->msg_model->get_like($this->input->post("msg_id"));
		$isParentsId = $this->msg_model->isParentsId($this->input->post("msg_id"));

		if($isParentsId) {
			$data = $like_num;
		} 
		else {
			$islike = $this->msg_model->isUserLike($this->input->post("msg_id"));
			$likeStr = $this->msg_model->createLikeSentence($like_num, $islike, $this->input->post("msg_id"));
			$data = "<div class='likeSentence'><li class='glyphicon glyphicon-thumbs-up'></li>{$likeStr}</div>";
		}

		echo $data;
	}

	public function ax_rspDel() {

		$this->msg_model->remove_rsp($_POST["response_id"]);
	}

	public function ax_cmtDel() {

		$this->msg_model->remove_cmt($_POST["comment_id"]);
	}

	public function ax_likeDel() {

		$this->msg_model->retake_like();
		$like_num    = $this->msg_model->get_like   ($this->input->post("msg_id"));
		$islike      = $this->msg_model->isUserLike ($this->input->post("msg_id"));
		$isParentsId = $this->msg_model->isParentsId($this->input->post("msg_id"));

		if($isParentsId) {
			$data = $like_num;
		}
		else {
			$likeStr = $this->msg_model->createLikeSentence($like_num, $islike, $this->input->post("msg_id"));
			$data = ($like_num > 0) ? $data = "<div class='likeSentence'><li class='glyphicon glyphicon-thumbs-up'></li>{$likeStr}</div>" : "";
		}
		echo $data;
	}

	public function ax_editCmt() {

		$comment = htmlspecialchars($this->input->post("comment"));
        $comment = $this->typography->nl2br_except_pre($comment);

		$data = array(
				"msg" => $comment
			);

		$this->msg_model->edit_cmt($data);

		$data = $this->msg_model->get_cmtObj($this->input->post("comment_id"));

		echo json_encode($data);
	}

	public function ax_editRsp() {

		$rsp = htmlspecialchars($this->input->post("response"));
		$rsp = $this->typography->nl2br_except_pre($rsp);

		$data = array(
			"msg" => $rsp
			);

		$this->msg_model->edit_rsp($data);

		$data = $this->msg_model->get_rsp($this->input->post("response_id"));

		echo json_encode($data);
	}

	public function ax_userList() {

		$data = $this->msg_model->getLikeUsername($this->input->post("msg_id"));

		echo json_encode($data);
	}

	public function renderHtml($data) {

		$url = base_url();
		if($data['parents_id'] == 0) {
		        $content = "<div class='msg panel'>
		                        		<div class='comment' msg-id='{$data['id']}'>
		                            		<div class='cmt_item'>
		                                			<img src='{$url}images/profile.jpg' />
		                                			<span class='user-title'>{$data['username']}</span>
		                            		</div>
		                            		<div class='cmt_text'>{$data['msg']}</div>
		                            		<div class='edit_area'></div>
		                            		<div class='dropdown tool'>
		                          				<a class='tool-icon glyphicon glyphicon-chevron-down' data-toggle='dropdown' href='#'></a>
		                          				<ul class='dropdown-menu dropdown-menu-right'>
		                          					<li class='delete'><a href='#'>刪除</a></li>
		                          					<li class='edit'><a href='#'>編輯貼文</a></li>
		                          				</ul>
		                          			</div>
		                        		</div>
		                        	   	<div class='msg_container'>
		                            		<div class='msg_bar'>
		                                			<span><a class='cmt-like' href='#'>讚</a></span> · 
		                                			<span><a href='#'>留言</a></span> ·  
		                                			<span><a href='#'>分享</a></span>
		                            		</div>
		                            		<div class='like_container'></div>
		                            		<div class='rsp_list'>
		                                			<div class='msg_item'>
		                                				<img src='{$url}images/profile.jpg' />
		                                				<textarea class='response' name='response' placeholder='留言'></textarea>
		                                			</div>
		                            		</div>
		                        	  	</div>
		                    	  </div>";
		}
		else {
			$content = "<div class='msg_item rsp_index' msg-id='{$data['id']}'>
                       				<img src='{$url}images/profile.jpg' />
                       				<div class='rsp_content'>
                       					<div>
                          						<span class='user-title'>{$data['username'] }</span>
                       		    				<span class='rsp_text'>{$data['msg'] }</span>
                       					</div>
                       					<div class='rsp-bar'>
                       						<span><a class='rsp-like' href='#'>讚</a></span>
                       						<span class='rsp-likeNum'></span>
                       					</div>
                       			 		<div class='dropdown tool'>
      	  					 		<a class='tool-icon glyphicon glyphicon-pencil' data-toggle='dropdown' href='#'></a>
      	  						 	<ul class='rsp_drop dropdown-menu dropdown-menu-right'>
      	  					 			<li class='edit'><a class='rsp_dropItem' href='#'>編輯......</a></li>
								 	<li class='delete'><a class='rsp_dropItem' href='#'>刪除......</a></li>
							 	</ul>
		  					</div>
                       				</div>
                       				<div class='edit_area'></div>
                            	       </div>";
		}
        		return array( "content" => $content );                
	}
}