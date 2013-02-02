<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once $application_folder."/controllers/base.php";

class Tweets extends Base {

	/**
	 * Index
	 *
	 * Show a list of the users saved tweets
	 *
	 * When you access a controller without an action, the index it loaded first, imagine it as the index file if the controller were a folder.
	 */
	public function index() {

		$this->set_active_menu_element('my_tweets');

		$tweets = Tweet::find('all', array('order' => 'id desc'));

		$data = array(
			'tweets'=>$tweets
		);

		$this->display_page_with_view('view_list', $data);
	}

	/**
	 * Search
	 *
	 * Search twitter for tweets mentioning a keyword
	 *
	 */
	public function search(){
		
		$this->set_active_menu_element('search');
		
		$search_term = $this->input->post('search');

		$tweets = Twitter::search($search_term);
		$facebook_pages = Facebook::search_pages($search_term);

		$data = array(
			'tweets'=>$tweets,
			'facebook_pages'=>$facebook_pages,
			'search_term'=>$search_term
		);

		$this->load->helper('text');

		$this->display_page_with_view('search', $data);
	}

	/**
	 * Add
	 *
	 * Add a new tweet
	 *
	 */
	public function add()
	{
		$tweet = new Tweet();
		$tweet->tweet = $this->input->post('tweet');
		$tweet->user_img_url = $this->input->post('user_img_url');
		
		try
		{
			$tweet->save();
		}catch(TweetException $e){ }
		
		redirect('/');
	}

	/**
	 * Delete
	 *
	 * Delete a saved tweet
	 *
	 */
	public function delete($tweet_id)
	{
		$tweet = Tweet::find($tweet_id);
		$tweet->delete();
		redirect('/');
	}

	public function add_comment($tweet_id)
	{
		$comment = new Comment();
		$comment->text = $this->input->post('comment');
		$comment->tweet_id = $tweet_id;
		$comment->save();

		redirect('/');
	}
}
