<?php

class Session {
	private $Date;
	private $Cache;
	private $Traffic;
	private $Browser;
	
	function __construct($Cache = null){
		session_start();
		$this->CheckSession($Cache);
		
	}

	private function CheckSession($Cache = null){
		$this->Date = date('Y-m-d');
		$this->Cache = ((int) $Cache ? $Cache : 20);
		
		if(empty($_SESSION['user_online'])):
			$this->setTraffic();
			$this->setSession();
			$this->checkBrowser();
			$this->setUsuario();
			$this->browserUpdate();
		else:
			$this->trafficUpdate();
			$this->sessionUpdate();
			$this->checkBrowser();
			$this->updateUsuario();
		endif;
		
		$this->Date = null;
	}
	
	private function setSession() {
		$_SESSION['user_online'] = [
			'online_session' => session_id(),
			'online_startview' => date('Y-m-d H:i:s'),	
			'online_endview' => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
			'online_ip' => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
			//'online_url' => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_VALIDATE_URL),
			// Tirar o filtro para URL para debugar localmente
			'online_url' => filter_input(INPUT_SERVER, 'REQUEST_URI'),
			'online_agent' => filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_DEFAULT)
		];
	} 
	
	private function trafficUpdate(){
		$this->getTraffic();
		$ArrSiteViews = ['sitevieSystem_pages' => $this->Traffic['sitevieSystem_pages'] + 1];
		$updatePageViews = new Update();
		$updatePageViews->ExeUpdate('System_siteviews', $ArrSiteViews, "WHERE sitevieSystem_date = :date", "date={$this->Date}");
	
		$this->Traffic = null;
	}  
	
	private function sessionUpdate() {
		$_SESSION['user_online']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
		//$_SESSION['user_online']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_VALIDATE_URL),
		$_SESSION['user_online']['online_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI');
	} 

	private function setTraffic(){
		$this->getTraffic();
		if(!$this->Traffic):
			$ArrSiteViews = ['sitevieSystem_date' => $this->Date, 'sitevieSystem_users' => 1, 'sitevieSystem_views' => 1, 'sitevieSystem_pages' => 1];
			$createSiteViews = new Create();
			$createSiteViews->ExeCreate('System_siteviews', $ArrSiteViews);
			
		else:
			if(!$this->getCookie()):
				$ArrSiteViews = [ 'sitevieSystem_users' => $this->Traffic['sitevieSystem_users'] + 1, 'sitevieSystem_views' => $this->Traffic['sitevieSystem_views'] + 1, 'sitevieSystem_pages' => $this->Traffic['sitevieSystem_pages'] +1];
			else:
				$ArrSiteViews = ['sitevieSystem_views' => $this->Traffic['sitevieSystem_views'] + 1, 'sitevieSystem_pages' => $this->Traffic['sitevieSystem_pages'] +1];
			endif;
			
			$updateSiteViews = new Update();
			$updateSiteViews->ExeUpdate('System_siteviews', $ArrSiteViews, 'WHERE sitevieSystem_date = :date', "date={$this->Date}");
			
		endif;
	}
	
	private function getTraffic() {
		$readSiteViews = new Read;
		$readSiteViews->ExeRead('System_siteviews', 'where sitevieSystem_date = :date', "date={$this->Date}");
		if ($readSiteViews->getRowCount()):
			$this->Traffic = $readSiteViews->getResult()[0];
		endif;
	} 
	
	private function getCookie() {
		$Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
		setcookie("useronline", base64_encode("PHPOO"), time() + 86400);
		if(!$Cookie):
			return false;
		else:
			return true;
		endif;
		
	}	
	
	private function checkBrowser() {
		$this->Browser = $_SESSION['user_online']['online_agent'];
		
		if(strpos($this->Browser, 'Chrome')):
			$this->Browser = "Chrome";
		elseif(strpos($this->Browser, 'Firefox')):
			$this->Browser = "FireFox";
		elseif(strpos($this->Browser, 'Opera')):
			$this->Browser = "Opera";
		elseif(strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')):
			$this->Browser = "IE";
		elseif(strpos($this->Browser, 'Safari')):
			$this->Browser = "Safari";
		else:
			$this->Browser = "Outros";
		endif;
	}
	
	private function browserUpdate(){
		$readAgent = new Read();
		$readAgent->ExeRead("System_sitevieSystem_agent", "WHERE agent_name = :agent", "agent={$this->Browser}");
		if(!$readAgent->getResult()):
			$ArrAgent = ['agent_name' => $this->Browser, 'agent_views' => 1];
			$createAgent = new Create();
			$createAgent->ExeCreate("System_sitevieSystem_agent", $ArrAgent);
		else:
			$ArrAgent = ['agent_views' => $readAgent->getResult()[0]['agent_views'] + 1];
			$updateAgent = new Update();
			$updateAgent->ExeUpdate("System_sitevieSystem_agent", $ArrAgent, "WHERE agent_name = :name", "name={$this->Browser}");	
		endif;
	}
	
	private function setUsuario() {
		$sesOnline = $_SESSION['user_online'];
		$sesOnline['agent_name'] = $this->Browser;
		
		$userCreate = new Create();
		$userCreate->ExeCreate("System_sitevieSystem_online", $sesOnline);
		
	}
	
	private function updateUsuario() {
		$ArrOnline = [
			'online_endview' => $_SESSION['user_online']['online_endview'],
			'online_url' => $_SESSION['user_online']['online_url']
		
		];
		
		$userUpdate = new Update;
		$userUpdate->ExeUpdate("System_sitevieSystem_online", $ArrOnline, "WHERE online_session = :ses", "ses={$_SESSION['user_online']['online_session']}");
		
		if(!$userUpdate->getRowCount()):
			$readSes = new Read();
			$readSes->ExeRead("System_sitevieSystem_online", "WHERE online_session = :ses", "ses={$_SESSION['user_online']['online_session']}");
			if(!$readSes->getRowCount()):
				$this->setUsuario();
			endif;
		endif;

	}
}