<?php
class Basketball extends Game implements GameInterface {
	public $HomeTeamScore;
	public $AwayTeamScore;
	
	public $HomeUnit1;
	public $HomeUnit2;
	public $HomeUnit3;
	
	public $AwayUnit1;
	public $AwayUnit2;
	public $AwayUnit3;
	
	public $firstPossession;
	public $Possession;
	public $Plays;
	
	public $HomeTeam;
	public $AwayTeam;
	
	public $HomeTeamPlayers;
	public $AwayTeamPlayers;	
	
	public $HomeTeamName;
	public $AwayTeamName;
	
	public $GameStats;
	
	public $ClassFields=array('Blocks', 'ShotMade', 'Shot3', 'Shots', 'Turnovers', 'Steals', 'Assists', 'Fouls', 'ORebounding', 'DRebounding', 'Points');
	
	public $QuarterLength=720;
	public function Basketball()
	{
	}
	public function runSingleGame(){
		$this->HomeTeamScore=1;
		$this->AwayTeamScore=2;
		$this->HomeTeamName="Raptors";
		$this->AwayTeamName="Thunder";
	}
	public function getCommonData(){
		$data['HomeTeamScore']=$this->HomeTeamScore;
		$data['AwayTeamScore']=$this->AwayTeamScore;
		$data['HomeTeamName']=$this->HomeTeamName;
		$data['AwayTeamName']=$this->AwayTeamName;
		return $data;
	}
	public function getPlayerPosition($PositionID){
		if($this->Possession == 1){
			$Player=$this->{"HomeUnit1"}[$PositionID];
		}
		else {
			$Player=$this->{"AwayUnit1"}[$PositionID];
		}
			return ($Player->PlayerID);
	}
	public function createPlayerStat($WhoStated, $StatValue, $Field){
		if($WhoStated < 1){
			print "NO PLAYER";
			exit();
		}
		$PlayerID=$this->getPlayerPosition($WhoStated);
		$Poss="Home";
		if($Field == 1){
			$Poss="Home";
		}
		elseif($this->Possession != 1){
			$Poss="Away";
		}
		$this->{$Poss."TeamPlayers"}[$PlayerID][$Field]+=$StatValue;
	}
	public function createStat($WhoStated, $StatValue, $Field, $Possession){
		if($WhoStated < 1){
			print "NO PLAYER";
			exit();
		}
		$this->createPlayerStat($WhoStated, $StatValue, $Field);
		if($Possession==1){
			$this->HomeTeam[$Field]=$StatValue + $this->HomeTeam[$Field];	
		}
		else {
			$this->AwayTeam[$Field]=$StatValue + $this->AwayTeam[$Field];
		}
		$this->GameStats[$Field]=$StatValue + $this->GameStats[$Field];
	}
	public function changePossession(){
		if($this->Possession==1){
			$this->Possession=2;
		}
		else {
			$this->Possession=1;
		}
	}
	public function getPlayerSkillValues($Skills){
		$Values="";
		$AnArray=array();
		foreach($Skills as $value){
			$Value2=rand(0, 100);
			array_push($AnArray, $Value2);
		}
		$Values=", " . implode(",", $AnArray);
		return ($Values);
	}
	public function getGameStatValues($Fields){
		$Values="";
		$AnArray=array();
		foreach($Fields as $value){
			
			$Value2=rand(0, 100);
			array_push($AnArray, $Value2);
		}
		$Values=", " . implode(",", $AnArray);
		return ($Values);
	}
	public function checkPossession(){
		if($this->Possession == 1){
			//$HomeUnit1;
		}
	}
	public function playGame($HomePlayerPos, $AwayPlayerPos, $Plays){
		$this->Plays=$Plays;
		$UnitValue=3;
		$Coaching['Units']=70;
		for($y=1; $y<=2; $y++){
			if($y == 1){ $HType="Home"; } else { $HType="Away"; }
			for($x=1; $x<=$UnitValue; $x++){
				foreach(${$HType . "PlayerPos"} as $key => $value){
					$this->{$HType . "Unit" . $x}[$key]=array_shift($value);
				}
			}
		}

		/* Play Quarter 1 */
		for($x=1;$x<=4;$x++){
			$this->playQuarter($x);
		}	
	}
	public function getOffUnit(){
		if($this->Possession == 1){
			return $this->HomeUnit1;
		}
		else {
			return $this->AwayUnit1;
		}
	}
	public function getDefUnit(){	
		if($this->Possession == 1){
			return $this->HomeUnit1;
		}
		else {
			return $this->AwayUnit1;
		}
	}
	public function playQuarter($QNumber){
		$QLength=720;
		if($QNumber==1){ $this->jumpBall(0, 1); }
		elseif($QNumber%2==0){ if($this->firstPossession==2){ $this->Possession=1; } else { $this->Possession=2;} }
		else { $this->Possession=$this->firstPossession; }
		for($x=0; $x<=$this->QuarterLength; $x++){
			$Length=$this->runPlay($this->getOffUnit(), $this->getDefUnit());
			$x=$x+$Length;
		}
	}
	public function jumpBall($PositionID=0, $First=0){
		if($PositionID==0){ $PositionID=5; }
		$HomeHeight=$this->HomeUnit1[$PositionID]->Height;
		$AwayHeight=$this->AwayUnit1[$PositionID]->Height;
		
		$HomeDunk=$this->HomeUnit1[$PositionID]->Dunk;
		$AwayDunk=$this->AwayUnit1[$PositionID]->Dunk;
		
		$HomeNumber=rand(1, $HomeHeight + $HomeDunk);
		$VisitorNumber=rand(1, $AwayHeight + $AwayDunk);
		
		if($HomeNumber >= $VisitorNumber){ $this->Possession=1; }
		else { $this->Possession=2; }
		if($First == 1){ $this->firstPossession=$this->Possession; }
	}
	public function getPlayStatArray($Stat){
		$AnArray=array();
		if(isset($Stat) && $Stat != ""){
			$AnArray=explode(",", $Stat);
		}
		return ($AnArray);
	}
	public function getOne($AnArray){
		 $rand=$AnArray[array_rand($AnArray, 1)];
		return ($rand);
	}
	public function getDefendingTeam(){ 
		if($this->Possession==1) return 2;
		else return 1;
	}
	public function runPlay($OffUnit, $DefUnit){
		
		$PlayID=round(rand(0, count($this->Plays)-1));
		
		$Shooters=$this->getPlayStatArray($this->Plays[$PlayID]->Shooters);
		$Passers=$this->getPlayStatArray($this->Plays[$PlayID]->Passers);
		$OffRebounders=$this->getPlayStatArray($this->Plays[$PlayID]->OffRebounders);
		$DefRebounders=$this->getPlayStatArray($this->Plays[$PlayID]->DefRebounders);
		$Defenders=$this->getPlayStatArray($this->Plays[$PlayID]->Defenders);
		$Shooters3Point=$this->getPlayStatArray($this->Plays[$PlayID]->Shooters3Point);
		$Stealers=$this->getPlayStatArray($this->Plays[$PlayID]->Stealers);
		$Blockers=$this->getPlayStatArray($this->Plays[$PlayID]->Defenders);
		
		$Stealer=$this->getOne($Stealers);
		$Passer=$this->getOne($Passers);
		$Defender=$this->getOne($Defenders);	
		$Blocker=$this->getOne($Blockers);
		$DefRebounder=$this->getOne($DefRebounders);
		$OffRebounder=$this->getOne($OffRebounders);
		
		$ChanceSteal=$this->Plays[$PlayID]->ChanceSteal + $DefUnit[$Stealer]->Stealing;
		$ChanceNonSteal=(100-$this->Plays[$PlayID]->ChanceSteal) + $OffUnit[$Stealer]->Passing;
		
		// Steal
		if(rand(0, $ChanceSteal) > (rand(0, $ChanceNonSteal) + 65)){
			$this->createStat($Stealer, 1, "Steals", $this->Possession);
			print "Steals<br>";
		}
		else {
			if(rand(0, $this->Plays[$PlayID]->Chance3Point) > rand(0, (100-$this->Plays[$PlayID]->Chance3Point)) + 50){
				$Change3Point=1;
				$Shooter=$this->getOne($Shooters3Point);
				$Shooting=$OffUnit[$Shooter]->Shooting + $OffUnit[$Shooter]->SRange;
				$this->createStat($Shooter, 1, "Shot3", $this->Possession);
			}
			else {
				$Shooter=$this->getOne($Shooters);
				$this->createStat($Shooter, 1, "Shots", $this->Possession);
				if(round(rand(1,3)) == 2){
					$CloseRange=1;
					$Shooting=$OffUnit[$Shooter]->Shooting + $OffUnit[$Shooter]->Dunking + $OffUnit[$Shooter]->Strength;
				}
				else {
					$Shooting=$OffUnit[$Shooter]->Shooting + $OffUnit[$Shooter]->Agility;
				}
			}
			
			$ChanceDefend=$this->Plays[$PlayID]->ChanceDefence + $DefUnit[$Defender]->Defending + $DefUnit[$Defender]->Blocking;
			$ChanceNonDefend=$OffUnit[$Shooter]->Agility + Shooting;
			$NumChanceDefend=rand(0, $ChanceDefend);
			$NumChanceNonDefend=rand(0, $ChanceNonDefend);
			
			// Foul
			if(abs($NumChanceDefend - $NumChanceNonDefend) < 7){
				if($NumChanceDefend - $NumChanceNonDefend < 0){
					$this->createStat($Shooter, 1, "Fouls", 1);
				}
				else {
					$this->createStat($Defender, 1, "Fouls", 2);
				}
			}
			elseif(rand(0, $ChanceDefend) > rand(0, $ChanceNonDefend)){	
			
				$ChanceBlock=$DefUnit[$Blocker]->Blocking;
				$ChanceNonBlock=$OffUnit[$Shooter]->Speed;
				//
				if(rand(0, $ChanceBlock) > rand(0, $ChanceNonBlock)){
					// Blocking
					$this->createStat($Blocker, 1, "Blocks", $this->getDefendingTeam());
					if(rand(0, 35) < rand(0, 100)){
						$this->changePossession();
					}
				}
				elseif(rand(0, 7) >= rand(0, 100)){
					//  Out of Bounds
					$this->changePossession();
				}
				elseif(rand(0, 4) >= rand(0, 100)){
					//  Not off Players
				}
				else {
					// Rebound
					$ChanceDefRebound=$DefUnit[$DefRebounder]->DRebounding;
					$ChanceOffRebound=$DefUnit[$OffRebounder]->ORebounding;
					if(rand(0, $ChanceDefRebound) <= rand(0, $ChanceOffRebound)){
						$this->createStat($OffRebounder, 1, "ORebounding", $this->Possession);
					}
					else{
						$this->createStat($DefRebounder, 1, "DRebounding", $this->getDefendingTeam());
					}
				}
			}
			else {
				$WhoScored=$Shooter;
				$ChanceAssist=$this->Plays[$PlayID]->ChanceAssist + $OffUnit[$Passer]->Passing;
				$ChanceNonAssist=((100-$this->Plays[$PlayID]->ChanceAssist) + 50);
				if(rand(0, $ChangeAssist) > rand(0, $ChanceNonAssist)){
					$Assist=1;
				}																										   
				if(isset($Change3Point) && $Change3Point == 1){
					$PointValue=3;
				}
				else {
					$PointValue=2;
				}
				$this->createStat($WhoScored, $PointValue, "Points", $this->Possession);
				$this->createStat($WhoScored, 1, "ShotMade", $this->Possession);
				$this->changePossession();	
			}	
		}		
		//$ChanceSteal=$this->Plays[$PlayID]->Stealers + getOne($Stealers);
	
		if(isset($Scored) && $Scored == 1){
			print "Time:" . $this->Plays[$PlayID]->Duration . " Who scored: $WhoScored <br>" . $PointValue . "<br>";
		}
		
		return ($this->Plays[$PlayID]->Duration);
		
	}
}
?>
