<?php

	$score = intval($_GET["score"]);
	
	if ($score >= 0)
	{
		if ($score < 500)
			echo '{"message":"Your score is low. Better luck next time!"}';
		else if ($score < 2000)
			echo '{"message":"Your score is in 10% of our lowest results. Better luck next time!"}';
		else if ($score < 500000)
			echo '{"message":"Cool score! But you can go higher!"}';
		else if ($score < 1000000)
			echo '{"message":"Your have a great score!!"}';
		else if ($score < 2000000)
			echo '{"message":"Your score is in 10% of our TOP results. Try harder!"}';
		else if ($score < 3866136)
			echo '{"message":"Your score is in 1% of our TOP results! You are cool!"}';
		else if ($score >= 3866136) //yeah, this if can be skipped, but just to make it obvious when reading the code to find the solution
			echo '{"message":"That is impossible!!!!! flag{Y0R_D4_B35T_1N_GAM35}!"}';
	}

?>
