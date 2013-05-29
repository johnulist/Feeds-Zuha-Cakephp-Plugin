<?php
App::uses('FeedsController', 'Feeds.Controller');

class FeedCJsController extends FeedsController {
		
	public $uses = array('Feeds.FeedCJ');
	
	public $viewPath = 'Feeds';
	
	public $defaultKeywords = '+Clothing/Apparel';
	
	public function index ($keywords = 'all') {
		if(!empty($this->request['named'])) {
			$advertiserName = isset($this->request['named']['name']) ? $this->request['named']['name'] : null;
			$pageNumber = isset($this->request['named']['page']) ? $this->request['named']['page'] : 1;
			$recordPerPage = isset($this->request['named']['rec']) ? $this->request['named']['rec'] : 50;
		}
		//conditions
		$conditions = array(
			'page-number' => $pageNumber,
			'records-per-page' => $recordPerPage,
		);
		
		if($advertiserName !== null){
			$conditions['manufacturer-name'] = $advertiserName;
		}
		
		$results = $this->FeedCJ->find('all', array(
			'keywords' => $this->defaultKeywords . $keywords,
			'conditions' => $conditions,
		));
		
		$this->set('products', $results['FeedCJ']['products']['product']);
		$this->set('pageNumber', $results['FeedCJ']['products']['@page-number']);
		$this->set('recPerPage', $results['FeedCJ']['products']['@records-returned']);
		$this->set('totalMatches', $results['FeedCJ']['products']['@total-matched']);
		
	}
	
	public function advertisers ($keywords = array()) {
		debug($this->request);
		debug($keywords);
		if(!empty($this->request['named'])) {
			$advertiserName = $this->request['named']['name'];
		}
		$advertisers = $this->FeedCJ->getAdvertisers($keywords, $advertiserName);
		$this->set('advertisers', $advertisers);
		
	}
	
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
	
		//Adds Rateable Helpers.
		if (in_array('Ratings', CakePlugin::loaded())) {
			$this->helpers[] = 'Ratings.Rating';
		}
		
		//Adds Favorable Helpers
		if (in_array('Favorites', CakePlugin::loaded())) {
			$this->helpers[] = 'Favorites.Favorites';
		}
	}
}