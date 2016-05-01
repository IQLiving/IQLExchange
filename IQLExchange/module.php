<?
class IQLExchange extends IPSModule {
		
	public function Create() {
		//Never delete this line!
		parent::Create();	
		//These lines are parsed on Symcon Startup or Instance creation
		//You cannot use variables here. Just static values.
		$this->RegisterPropertyString("login", "");		
		$this->RegisterPropertyString("password", "");
		$this->RegisterPropertyString("user", "");
		$this->RegisterPropertyString("serverversion", "exV15");
		$this->RegisterPropertyString("host", "");
		$this->RegisterPropertyString("search", "URLAUB!!!, Urlaub!!!, Urlaub !!!, URLAUB !!!, Urlaub, URLAUB, Vacation - Confirmed");
		$this->RegisterPropertyString("timeframe", "today");
	}
	
	public function ApplyChanges() {
		//Never delete this line!
		parent::ApplyChanges();
		
		$this->RegisterVariableBoolean("vacation", "Urlaub", "", 0 );
		//$this->RegisterEventCyclic("UpdateTimer", "Automatische aktualisierung", 15);
	}
	public function Update() {
		$userholiday = $this->GetVacationFromEx();
		if($userholiday == "Urlaubstag") {
			SetValue($this->GetIDForIdent("vacation"),true);
		}
		else {
			SetValue($this->GetIDForIdent("vacation"),false);
		}
		
	}
	private function GetVacationFromEx() {
		if($this->ReadPropertyString("serverversion") == "exV15") {
			include_once __DIR__ ."/init.php";
			$searchstring = (string) $this->ReadPropertyString("search");
			$search = explode(",",$searchstring);
			$search = array_map("trim", $search);
			$returnvar = "Arbeitstag";
			if($this->ReadPropertyString("timeframe") == "today") {
				$datum = date("Y-m-d\T",time());
			}
			elseif($this->ReadPropertyString("timeframe") == "tomorrow") {
				$tomorrow = strtotime("+1 Day");
				$datum = date("Y-m-d\T",$tomorrow);
			}
			$startdatum = $datum ."00:00:00Z";
			$enddatum = $datum ."19:59:59Z";
			$ec = new ExchangeClient();
			$ec->init($this->ReadPropertyString("login"), $this->ReadPropertyString("password"),$this->ReadPropertyString("user"), "https://" .$this->ReadPropertyString("host") ."/EWS/Services.wsdl");
			$urlaub = $ec->get_events($startdatum,$enddatum);
			$subjects = array();
			foreach($urlaub as $item) {
				$subjects[] = (string) $item->{'subject'};
			}
			foreach($subjects as $subject) {
				if(in_array($subject, $search)) {
					$returnvar = "Urlaubstag";
				}
			}
			return $returnvar;	
		}
		elseif($this->ReadPropertyString("serverversion") == "exV16") {
			$returnvar = "not implemented yet.";
			return $returnvar;
		}
		
	}
	public function GetVacation( string $user) {
		$holiday = false;
		$holidayinstanz = IPS_GetInstanceListByModuleID("{B5D1BEFB-DA80-4063-BB84-92C8BCB5150C}");
		$allholiday = array();
		foreach($holidayinstanz as $entry) {
			$allholiday[] = IPS_GetObjectIDByIdent("vacation",$entry);
		}
		if($user == "any") {
			foreach($allholiday as $person) {
				if(GetValue($person) == true) {
					$holiday = true;
				}
			}
			
		}
		elseif($user == "all") {
			$holidayall = array();
			foreach($allholiday as $allperson) {
				if(GetValue($allperson) == false) {
					$holidayall[] = "false";
				}
				elseif(GetValue($allperson) == true) {
					$holidayall[] = "true";
				}
			}
			if(!in_array("false",$holidayall)) {
				$holiday = true;
			}
		}
		return $holiday;
	}
	public function SendMail(string $to, string $subject, string $content) {
		include_once __DIR__ ."/init.php";
		$bodytype = "Text";
		$ec = new ExchangeClient();
		$ec->init($this->ReadPropertyString("login"), $this->ReadPropertyString("password"),NULL, "https://" .$this->ReadPropertyString("host") ."/EWS/Services.wsdl");
		$mail = $ec->send_message($to, $subject, $content, $bodytype);
	}
	public function SendMailHTML( string $to, string $subject, string $content ) {
		include_once __DIR__ ."/init.php";
		$bodytype = "HTML";
		$ec = new ExchangeClient();
		$ec->init($this->ReadPropertyString("login"), $this->ReadPropertyString("password"),NULL, "https://" .$this->ReadPropertyString("host") ."/EWS/Services.wsdl");
		$mail = $ec->send_message($to, $subject, $content, $bodytype);
		
	}
}
?>