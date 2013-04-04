<?php
class DBClass {
	public $Link;
	public $Type;
	public function DBClass($Type){
		$this->Type=$Type;
		$this->Link=mysql_connect ("localhost", "test", "") or die ('I cannot connect to the database because: ' . mysql_error());
		mysql_select_db ("Prefix_" . $Type);
	}
	public function getTeams(){
		$sql="SELECT * FROM Teams WHERE 1";
		return ($this->getResults($sql, 2));
	}
	public function getGame($GameID){
		$sql="SELECT * FROM Schedule WHERE ScheduleID='$GameID'";
		return ($this->getResult($sql, 2));
	}
	public function getGames(){
		$sql="SELECT * FROM Schedule WHERE Completed='0'";
		return ($this->getResults($sql, 2));
	}
	public function getGameStatFields(){
		$sql="SELECT * FROM GameStatFields WHERE 1";
		return ($this->getResults($sql, 2));
	}
	public function getRandomFirstNames($Limit=1000){
		$sql="SELECT * FROM firstNames WHERE 1 ORDER BY Rand() LIMIT $Limit";
		return ($this->getResults($sql, 2));
	}
	public function getRandomLastNames($Limit=1000){
		$sql="SELECT * FROM lastNames ORDER BY Rand() LIMIT $Limit";
		return ($this->getResults($sql, 2));
	}
	public function getPositions(){
		$sql="SELECT * FROM Positions WHERE 1";
		return ($this->getResults($sql, 2));
	}
	public function getConferences(){
		$sql="SELECT * FROM Conferences WHERE 1";
		return ($this->getResults($sql, 2));
	}
	public function getDivisions(){
		$sql="SELECT * FROM Divisions WHERE 1";
		return ($this->getResults($sql, 2));
	}
    
    public function getTable($TableName, $ReturnType=2){
        $sql="SELECT * FROM $TableName WHERE 1";
        return ($this->getResults($sql, $ReturnType));
    }
	public function getSport($Sport){
		$sql="SELECT * FROM Sports WHERE Name LIKE '$Sport'";
		return ($this->getResult($sql, 2));
	}
	public function getRandomPosition(){
		$sql="SELECT PositionID FROM Positions ORDER BY Rand() LIMIT 1";
		return ($this->getResult($sql, 2));
	}
	public function getPlayerSkillValue($PlayerID, $Skills){
		$SkillArr=array();
		foreach($Skills as $Skill){
			array_push($SkillArr, $Skill->Name);
		}
		$SkillStr=implode("+", $SkillArr);
		$sql="SELECT *, SUM(" . $SkillStr . ") as TheSum FROM Players WHERE PlayerID='$PlayerID'";
		return ($this->getResults($sql, 2));
	}
	public function getPlayerSkillFields(){
		$sql="SELECT * FROM Skills";
		return ($this->getResults($sql, 2));
	}
	public function getPlays(){
		$sql="SELECT * FROM Plays";
		return ($this->getResults($sql, 2));
	}
	public function getPlayerPosition($PositionID, $TeamID, $ExtraStr){
		$sql="SELECT *, SUM(" . $ExtraStr . ") as TheSum FROM TeamPlayers, Players WHERE TeamPlayers.PlayerID=Players.PlayerID AND TeamID='$TeamID' AND PositionID='$PositionID' GROUP BY Players.PlayerID ORDER BY TheSum";
		//print $sql;
		return ($this->getResults($sql, 2));
	}
	public function getUndraftedPlayers($Skills){
		$SkillArr=array();
		foreach($Skills as $Skill){
			array_push($SkillArr, $Skill->Name);
		}
		$SkillStr=implode("+", $SkillArr);
		$sql="select *, SUM(" . $SkillStr . ") as TheSum FROM Players GROUP BY PlayerID ORDER BY TheSum DESC";
		return ($this->getResults($sql, 1));	
	}
	public function updatePlayer($PlayerID, $Field, $Value){
		$sql="UPDATE Player SET " . $Field . "='" . $Value . "' WHERE PlayerID='" . $PlayerID . "'";
		$worked=$this->runQuery($sql);
	}
	public function draftPlayer($PlayerID, $TeamID, $PositionID=0){
		if($PositionID == 0){
			$PositionID=$this->getRandomPosition();
			$PositionID=$PositionID->PositionID;
		}
		$sql="INSERT INTO TeamPlayers (PlayerID, TeamID, Status, PositionID) VALUES ('$PlayerID', '$TeamID', '1', '$PositionID')";
		$worked=$this->runQuery($sql);
	}
	public function insertPlayer($FirstName, $LastName, $Nationality, $Age, $Hand, $Height, $Weight, $TeamID=0, $ExtraFields="", $ExtraValues=""){
		$sql="INSERT INTO Players (FirstName, LastName, Nationality, Age, Hand, Height, Weight" . $ExtraFields . ")  VALUES ('$FirstName', '$LastName', '$Nationality', '$Age', '$Hand', '$Height', '$Weight'" . $ExtraValues . ")";
		
		//print $sql . "<hr>";

		$PlayerID=$this->runQuery($sql, 1);

		
		if($TeamID > 0){
			$this->draftPlayer($PlayerID, $TeamID);
		}
	}
	public function insertSchedule($HomeID, $VisitorTeamID, $Season, $Week, $GameDay, $TimeSlot){
		$sql="INSERT INTO Schedule (HomeTeam, VisitorTeam, SeasonID, Week, GameDay, TimeSlot) VALUES ('$HomeID', '$VisitorTeamID', '$Season', '$Week', '$GameDay', '$TimeSlot')";
		$worked=$this->runQuery($sql);
	}
	public function saveGameResults($GameID, $HomeScore, $AwayScore){
		
		$sql="update Schedule SET Completed='1', CompletedDate=NOW(), HomeScore='$HomeScore', VisitorScore='$AwayScore' WHERE ScheduleID='$GameID'";
		$worked=$this->runQuery($sql);
	}
	public function saveGameStats($GameID, $Stats){
		$sql="INSERT INTO GameStats (GameID, " . implode(", ", array_keys($Stats)) . ") VALUES (" . $GameID . ", " . implode(", ", array_values($Stats)) . ")";
		print $sql;
		$worked=$this->runQuery($sql);
	}
	public function savePlayerGameResults($GameID, $Stats, $TeamID){
		$AnArray=array();
		foreach($Stats as $key => $value){
			if($key < 1){ $key=0; }
			$value['PlayerID']=$key;		
			$sql="INSERT INTO PlayerStats (TeamID, GameID, " . implode(", ", array_keys($value)) . ") VALUES (" . $TeamID . ", " . $GameID . ", " . implode(", ", array_values($value)) . ")";
			print $sql;
			$worked=$this->runQuery($sql);
		}
	}
	public function saveTeamGameResults($GameID, $Stats, $TeamID, $Type){
		$sql="INSERT INTO TeamStats (TeamID, GameID, " . implode(", ", array_keys($Stats)) . ") VALUES (" . $TeamID . ", " . $GameID . ", " . implode(", ", array_values($Stats)) . ")";
		$worked=$this->runQuery($sql);
	}
	public function getResults($sql, $ReturnType){
		$TheArray=array();
		$re=mysql_query($sql) or die(mysql_error());
		if($ReturnType == 1){
			while($rw=mysql_fetch_array($re))
			{
				array_push($TheArray, $rw);
			}
		}
		else {
			while($rw=mysql_fetch_object($re))
			{
				array_push($TheArray, $rw);
			}
		}
		return ($TheArray);
	}
	public function getResult($sql){
		$re=mysql_query($sql) or die(mysql_error());
		if($ReturnType == 1){
			$rw=mysql_fetch_array($re);
		}
		else
		{
			$rw=mysql_fetch_object($re);
		}
		return ($rw);
	}
	public function runQuery($sql, $lastinserted=0){
		$upd=mysql_query($sql) or die(mysql_error());
		if($lastinserted == 1){
			$lastid=mysql_insert_id();
			return ($lastid);
		}
		return ($upd);
	}
}