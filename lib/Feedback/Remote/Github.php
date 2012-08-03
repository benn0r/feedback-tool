<?php

namespace Feedback\Remote;

use Feedback\Remote,
	Github\Client;

class Github extends Remote
{
	/**
	 * API for communicating with github server
	 * 
	 * @var Github\Client
	 */
	protected $_host;
	
	/**
	 * Repository owner
	 * 
	 * @var string
	 */
	protected $_user;
	
	/**
	 * Repository
	 * 
	 * @var string
	 */
	protected $_repo;
	
	/**
	 * Label which every feedback will get
	 * 
	 * @var string
	 */
	protected $_label;
	
	public function __construct(Client $host)
	{
		$this->_host = $host;
	}
	
	public function setRepo($user, $repo)
	{
		$this->_user = $user;
		$this->_repo = $repo;
	}
	
	public function setLabel($label)
	{
		$this->_label = $label;
	}
	
	/**
	 * Returns all labels of a repository 
	 * 
	 * @return array<array> contains color,name and url
	 */
	public function getLabels()
	{
		if (!($url = $this->_url()))
			return false;
		
		return $this->_host->get($url . '/labels');
	}
	
	public function put($title, $body, array $options = null)
	{
		if (!$this->_host)
			return false;
		
		if (!is_array($options))
			$options = array();
		
		$options['title'] = $title;
		$options['body'] = $body;
		
		if (!isset($options['labels']) || !is_array($options['labels']))
			$options['labels'] = array();
		
		if ($this->_label)
			$options['labels'][] = $this->_label;
		
		if (!($url = $this->_url()))
			return false;
		
		$this->_host->post($url . '/issues', $options);
	}
	
	/**
	 * Generates the url for adding an issue
	 * 
	 * @return string
	 */
	protected function _url()
	{
		if (!$this->_user || !$this->_repo)
			return false;
		
		return '/repos/' . $this->_user . '/' . $this->_repo;
	}
}