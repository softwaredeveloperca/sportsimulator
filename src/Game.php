<?php
require_once("GameInterface.php");
class Game {
	public $Type;
	public $CommonData;
	public $DBClass;
	public $Sport;
	public $Skills;
	public $Positions;
	public function Game($Type="", $DBClass=""){
		if($Type != ""){
			require($Type . ".php");
			$this->Type=new $Type;
			$this->Sport=$Type;
		}
		if($DBClass != ""){
			$this->DBClass=$DBClass;
		}
		$this->Skills=$this->DBClass->getPlayerSkillFields();
		$this->Positions=$this->DBClass->getPositions();;
	}
	public function runSingleGame(){
		$this->Type->runSingleGame();
		$this->CommonData=$this->Type->getCommonData();
	}
	public function createSeason(){
		//$this->createSchedule();
		$this->createPlayers(0);
		$this->draftPlayers();
	}
	public function draftPlayers(){
		$Teams=$this->DBClass->getTeams();
		$Sport=$this->DBClass->getSport($this->Sport);
		$Players=$this->DBClass->getUndraftedPlayers($this->Skills);
		print "Players per team" . $Sport->PlayersPerTeam;
		for($x=1; $x <= ($Sport->PlayersPerTeam); $x++){
			foreach($Teams as $value){
				
				$Player=array_shift($Players);
				$this->DBClass->draftPlayer($Player['PlayerID'], $value->TeamID);
			}
		}
			
	}
	public function createPlayers($Draft=1){
		
		$Sport=$this->DBClass->getSport($this->Sport);
		$Teams=$this->DBClass->getTeams();
		
		$PlayerAmount=count($Teams) * $Sport->PlayersPerTeam;
		$FirstNames=$this->DBClass->getRandomFirstNames(1000);	
	
		$LastNames=$this->DBClass->getRandomLastNames(1000);	
		$Skills=$this->Skills;
		$ExtraFields=$this->getFormatedFields($this->Skills);
		
		for($x=1; $x <= ($Sport->PlayersPerTeam * 4); $x++){
			foreach($Teams as $value){
				$ExtraValues=$this->Type->getPlayerSkillValues($Skills);
				
				$FirstName=$FirstNames[array_rand($FirstNames)];
				$LastName=$LastNames[array_rand($LastNames)];
				
				$Age=rand(18, 40);
				$Hand=1;
				if(rand(1, 10) < 3){ $Hand=2; }
				$Height=rand(130, 220);
				$Weight=rand(130, 300);		
				if($Draft == 0){
					$value->TeamID=0;
				}
					$this->DBClass->insertPlayer($FirstName->Name, $LastName->Name, $FirstName->Nationality, $Age, $Hand, $Height, $Weight, $value->TeamID, $ExtraFields, $ExtraValues);
			}
		}
		
	}
	public function createPlayerStats(){
	}
	public function playGame($GameID){	
		$Schedule=$this->DBClass->getGame($GameID);
		$Plays=$this->DBClass->getPlays();
		
		$HomeTeamID=$Schedule->HomeTeam;
		$AwayTeamID=$Schedule->VisitorTeam;
		
		$Sport=$this->DBClass->getSport($this->Sport);
		
		// Get Players for Game
		$HomePlayerPos=$this->getPlayerPositions($HomeTeamID);
		$AwayPlayerPos=$this->getPlayerPositions($AwayTeamID);
	
	
		$GameStatFields=$this->Type->getFormatedFields($this->DBClass->getGameStatFields());
			
		// Play Game
		$this->Type->playGame($HomePlayerPos, $AwayPlayerPos, $Plays);
	
		$this->DBClass->saveGameResults($GameID, $this->Type->HomeTeam['Points'], $this->Type->AwayTeam['Points']);
		
		// Save Game Stats	
		$this->DBClass->saveGameStats($GameID, $this->Type->GameStats);
		
		// Save Team Stats
		$this->DBClass->saveTeamGameResults($GameID, $this->Type->HomeTeam, $HomeTeamID, "Team");
		$this->DBClass->saveTeamGameResults($GameID, $this->Type->AwayTeam, $AwayTeamID, "Team");
		
		// Save Player Stats	
		$this->DBClass->savePlayerGameResults($GameID, $this->Type->HomeTeamPlayers, $HomeTeamID);
		$this->DBClass->savePlayerGameResults($GameID, $this->Type->AwayTeamPlayers, $AwayTeamID);
		
	}
	public function saveTeamGameResults($GameID, $HomeTeam, $VisitorTeam){
		$AnArray=array();
		foreach($this->Type->ClassFields as $Field){
			$AnArray=$this->Type->HomeTeam[$Field];
		}
		$Fields=implode(", ", $this->Type->ClassFields);
		$Values=implode(", ", $AnArray);
		print $Fields . $Values . "<br>";
	}
	public function getPlayerPositions($TeamID){
		foreach($this->Positions as $Position){
			$PositionID=$Position->PositionID;
			$PlayersPos[$PositionID]=$this->DBClass->getPlayerPosition($PositionID, $TeamID, $this->SkillstoStr($this->Skills));
		}
		
		foreach($this->Positions as $Position){
			$PositionID=$Position->PositionID;;
			if(count($PlayersPos[$PositionID]) > 3){
				//array_pop($PlayersPos[$PositionID]);
				$PlayersPos[$PositionID]=array_slice($PlayersPos[$PositionID], 0, 3, true);
				//print count($PlayersPos[$PositionID]) . " $PositionID + 3<br>";
			}
			elseif(count($PlayersPos[$PositionID]) < 2){
				//print count($PlayersPos[$PositionID]) . " $PositionID - 3<br>";
			}
		}

		return $PlayersPos;
	}
	public function playSeason(){
		$Games=$this->DBClass->getGames();
		foreach($Games as $value){
			$this->playGame($value->ScheduleID);
		}
	}
	public function createSchedule(){
		$HomeTeams=$this->DBClass->getTeams();
		$VisitorTeams=$this->DBClass->getTeams();
		$cnt=0;
		foreach($HomeTeams as $value){
			foreach($VisitorTeams as $value2){
				$HomeID=$value->TeamID;
				$VisitorID=$value2->TeamID;
				if($HomeID != $VisitorID){
					$cnt++;			
					$Week=rand(1, 52);
					$GameDay=rand(1, 7);
					$TimeSlot=rand(1, 24);
					/* $HomeID, $VisitorTeamID, $Season, $Week, $GameDay, $TimeSlot */
					$this->DBClass->insertSchedule($value->TeamID, $value2->VisitorID, 1, $Week, $GameDay, $TimeSlot);
				}
			}
		}
		print " $cnt schedule dates added<br>";
	}
	private function getFormatedFields($Skills){
		$AnArray=array();
		$Fields="";
		foreach($Skills as $value){
			array_push($AnArray, $value->Name);
		}
		$Fields=", " . implode(",", $AnArray);
		return ($Fields);
	}
	private function SkillstoStr($Skills){
		$SkillArr=array();
		foreach($Skills as $Skill){
			array_push($SkillArr, $Skill->Name);
		}
		$SkillStr=implode("+", $SkillArr);
		return $SkillStr;
	}
	
	
}
?>
