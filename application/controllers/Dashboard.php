<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dashboard extends MY_Generator {

	public function __construct()
	{
		parent::__construct();
	}
    public function index()
    {
        $this->theme('dashboard/index');
    }
}