<?php
interface GameInterface {	
	public function runSingleGame();
	public function getCommonData();
	public function playGame($HomePlayerPos, $AwayPlayerPos, $Plays);
}
?>
