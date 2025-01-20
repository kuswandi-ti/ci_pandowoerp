<?php

function format_currency($angka, $curr = "Rp. ", $desimal = 2)
{	
	$hasil = $curr . number_format((float)$angka, $desimal, ',', '.');
	return $hasil; 
}
