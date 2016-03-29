<?php
function exec_ps($argv, $argc)
{
  if ($argc == 2)
  {
    if ($argv[1] == "panel")
      aff_panel();
    else if (file_exists("./runlvl/runlvl" . $argv[1]))
      {
        stop($argv[1]);
        start($argv[1]);
      }
    else
      echo "Runlvl inexistant.\nEntrez un Runlvl valide ou\ncreez le en lancant 'sysV-NG.php' avec l'argument 'panel'.\n";
  }
  else if ($argc > 2)
    echo "Vous ne devez entrer qu'un parametre,\nce parametre doit etre le nom du runlvl que vous voulez lancer.\nSi vous ne mettez aucun parametre le runlvl par defaut sera lance.\n";
  else if (file_exists("./runlvl/runlvldefault"))
  {
    stop("default");
    start("default");
  }
  else
    echo "Configuration par defaut inexistante.\nCreez la en lancant 'sysV-NG.php' avec l'argument 'panel' et nommez la 'default'.\n";
}
function save($i , $start , $stop)
{
  if (isset($start))
  {
    $op_sta = fopen("./runlvl/runlvl$i/start.txt","w");
    foreach($start as $value)
      fwrite($op_sta,"$value\n");
  }
  if (isset($stop))
  {
    $op_sto = fopen("./runlvl/runlvl$i/stop.txt","w");
    foreach($stop as $value)
        fwrite($op_sto,"$value\n");
  }
  echo "\033c";
  echo "Runlvl sauvegarde\n";
}
function start($init)
{
  if (file_exists("./runlvl/runlvl".$init."/start.txt") && filesize("./runlvl/runlvl".$init."/start.txt") > 0)
  {
    $tab = my_split(fich_open($init));
    $start = clean_array($tab[0]);
	  foreach($start as $value)
	  {
		  exec("/etc/init.d/$value start", $result);
      if ($result)
        echo "\033[32mSTART\033[m $value :\t [ \033[32mOK\033[m ]\n";
      else
        echo "\033[32mSTART\033[m $value :\t [ \033[31mKO\033[m ]\n";
	  }
  }
}
function stop($init)
{
if (file_exists("./runlvl/runlvl".$init."/stop.txt") && filesize("./runlvl/runlvl".$init."/stop.txt") > 0)
  {
    $tab = my_split(fich_open($init));
    $stop = clean_array($tab[1]);
	  foreach($stop as $value)
	  {
		  exec("/etc/init.d/$value stop", $result);
      if ($result)
        echo "\033[31mSTOP\033[m $value :\t [ \033[32mOK\033[m ]\n";
      else
        echo "\033[31mSTOP\033[m $value :\t [ \033[31mKO\033[m ]\n";
	  }
  }
}
function my_split($modif)
{
  $start = array();
  $stop = array();
  if (!empty($modif[0]))
    $start = explode("\n" , $modif[0]);
  if (!empty($modif[1]))
   $stop = explode("\n" , $modif[1]);
  $tab[0] = $start;
  $tab[1] = $stop;
  return($tab);
}
?>
