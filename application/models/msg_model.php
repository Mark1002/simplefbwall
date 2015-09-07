<?php
class msg_model extends CI_Model {

	public function __construct() {

		$this->load->database();

	}

	public function get_userInfo() {

		$this->db->select("id, account, password");
		$this->db->from("msg_user");
		$this->db->where("account", $this->input->post("account"));
		$this->db->where("password", $this->input->post("password"));
		$query = $this->db->get();

		return $query->result();
	}

/*---------------------insert model-----------------------*/

	public function set_msg($data) {

		$this->db->insert("msg", $data);
	}

/*--------------------get one record---------------------*/

	public function get_cmtObj($data = FALSE) {

		if($data) {//編輯時用到

			$this->db->select("msg")->from("msg")->where("id", $data);
		}
		else {

			$this->db->select("id, msg, parents_id")->from("msg")->where("parents_id", 0)->order_by("id", "desc");
			$this->db->limit(1);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function get_rsp($data = FALSE) {

		if($data) {//編輯時用到

			$this->db->select("msg")->from("msg")->where("id", $data);
		}
		else {

			$this->db->select("id, msg, parents_id")->from("msg")->order_by("id", "desc");
			$this->db->limit(1);
	    }

		$query = $this->db->get();

		return $query->result();
	}

/*-------------------------remove model--------------------------*/

	public function remove_rsp($data) {

		$this->remove_like($data);
		$this->db->where("id", $data)->delete("msg");
	}

	public function remove_cmt($data) {

		$this->remove_like($data);
		$this->db->where("id", $data)->or_where("parents_id", $data)->delete("msg");
	}

	public function remove_like($data) {

		$this->db->select("id")->from("msg")->where("id", $data)->or_where("parents_id", $data);
		$query = $this->db->get();

		foreach($query->result() as $row) {
			$this->db->where("msg_id", $row->id)->delete("msg_like");
		}
	}

/*-------------------------edit model---------------------------*/

	public function edit_cmt($data) {

		$this->db->where("id", $this->input->post("comment_id"));
		$this->db->update("msg",$data);
	}

	public function edit_rsp($data) {

		$this->db->where("id", $this->input->post("response_id"));
		$this->db->update("msg", $data);
	}

/*-----------------------render model------------------------*/

	public function get_msg($parents_id = FALSE) {

		if($parents_id === 0) $this->db->where("parents_id", 0)->order_by("id", "desc");

	    else $this->db->where("parents_id !=", 0)->order_by("id", "asc");

	    	$query = $this->db->get("msg");
		$msg_items = $query->result_array();

		foreach ($msg_items as &$row) {

			$username     = $this->get_name($row["user_id"]);
			$like_num     = $this->get_like($row["id"]);
			$islike       = $this->isUserLike($row["id"]);
			$likeSentence = $this->createLikeSentence($like_num, $islike, $row["id"]);

			$row = array_merge(
				$row,
				array(
			 		"username" => $username,
			 		"like_num" => $like_num,
			 		"likeSentence" => $likeSentence,
			 		"islike" => $islike	
			 	)
			);
		}

		$msg_items = json_decode(json_encode($msg_items));

		return $msg_items;
	}

/*------------------------------取得使用者名稱--------------------------------*/	

	public function get_name($data) {

		$this->db->select("name")->from("msg_user")->where("id", $data);

		$query    = $this->db->get();
		$result   = $query->result();
		$username = $result[0]->name;

		return $username;
	}

	public function set_like($data) {

		$this->db->insert("msg_like", $data);
	}

/*----------------------------取得當前評論按讚數----------------------------*/

	public function get_like($data) {

		return $this->db->where("msg_id", $data)->count_all_results("msg_like");
	}

/*------------------------判斷使用者對當前評論是否按讚----------------------------*/

	public function isUserLike($data) {

		$this->db->select("user_id")->from("msg_like")->where("msg_id", $data);

		$query  = $this->db->get();
		$islike = FALSE;

		foreach($query->result() as $row) {
			if($row->user_id === $_SESSION["id"]) $islike = TRUE;
		}
		return $islike;
	}

/*-----------------------------取得按讚使用者名稱-------------------------------*/

	public function getLikeUsername($data) {

		$this->db->select("user_id")->from("msg_like")->where("msg_id", $data);

		$query     = $this->db->get();
		$usernames = array();

		foreach ($query->result() as $row) {
			array_push($usernames, $this->get_name($row->user_id));
		}
		return $usernames;
	}

	public function retake_like() {

		$this->db->where("msg_id", $this->input->post("msg_id"));
		$this->db->where("user_id", $this->input->post("user_id"));
		$this->db->delete("msg_like");
	}

	public function createLikeSentence($like_num, $islike, $msg_id) {

		if($islike === TRUE && $like_num > 0) {

			if($like_num === 1) {	
				$likeStr = "你覺得這真讚。";
			}
			else {
				$likeStr = "你與其他".($like_num-1)."人覺得這真讚。";
			}
		}
		else {

			if($like_num === 1) {

				$usernames = $this->getLikeUsername($msg_id);
				$likeStr   = "{$usernames[0]}覺得這真讚。";
			}
			else {
				$likeStr = "{$like_num}個人覺得這真讚。";
			}
		}
		return $likeStr;
	}

	public function isParentsId($data) {

		$this->db->select("parents_id")->from("msg")->where("id", $data);
		$query      = $this->db->get();
		$result     = $query->result();
		$parents_id = (int)$result[0]->parents_id;

		return ($parents_id > 0) ? TRUE : FALSE;  
	}
}