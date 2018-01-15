<?php

function two_letter_word_count($str)
{
	$count = 0;
	$words = str_word_count($str, 1);
	foreach($words as $w)
	{
		if(strlen($w)==2)
		{
			$count++;
		}
	}
	return $count;
}

function capital_letter_count($str)
{
	return strlen(preg_replace('/[^A-Za-z]+/', '', $str));
}

function longest_word($str)
{
	$longestWord = '';
	$words = str_word_count($str, 1);
	foreach($words as $w)
	{
		if(strlen($w)>strlen($longestWord))
		{
			$longestWord = $w;
		}
	}
	return $longestWord;
}

?>