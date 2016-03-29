<?php
include "run.php";
function clean_array($tab)
{
  $i = 0;
  $tab2 = array();
  foreach($tab as $value)
    if (!empty($value) && isset($value))
    {
      $tab2[$i] = $value;
      $i++;
    }
    return($tab2);
}
function fich_open($i)
{
  $opn_start = fopen("./runlvl/runlvl$i/start.txt","c+");
  $opn_stop = fopen("./runlvl/runlvl$i/stop.txt","c+");

  if (filesize("./runlvl/runlvl$i/start.txt") > 0 && filesize("./runlvl/runlvl$i/stop.txt") > 0)
  {
    $tab[0] = fread($opn_start, filesize("./runlvl/runlvl$i/start.txt"));
    $tab[1] = fread($opn_stop, filesize("./runlvl/runlvl$i/stop.txt"));
  }
  else if (filesize("./runlvl/runlvl$i/start.txt") > 0 && filesize("./runlvl/runlvl$i/stop.txt") < 1)
  {
    $tab[1] = array();
    $tab[0] = fread($opn_start, filesize("./runlvl/runlvl$i/start.txt"));
  }
  else if (filesize("./runlvl/runlvl$i/start.txt") < 1 && filesize("./runlvl/runlvl$i/stop.txt") > 0)
  {
    $tab[0] = array();
    $tab[1] = fread($opn_stop, filesize("./runlvl/runlvl$i/stop.txt"));
  }
  else
    $tab = array();
  fclose($opn_start);
  fclose($opn_stop);
  return($tab);
}
function runlvl($i)
{
  if (!file_exists("./runlvl/runlvl$i"))
  {
    $tab = "";
    $result = "";
    while ($result != 'Y' && $result != 'n')
    {
      echo "Niveau d'init inexistant, voulez vous le creer?  (Y/n)\n";
      $result = readline("=> ");
    }
    if ($result == "Y")
    {
      exec("mkdir -p ./runlvl/runlvl$i");
      $tab = fich_open($i);
    }
    else
      return(0);
  }
  else
    $tab = fich_open($i);
  return($tab);
}
function aff_ps($start, $stop)
{
  $fichier = scandir("/etc/init.d");

  for($i = 0; $i < 6; $i++)
    unset($fichier[$i]);
  echo "\033c";
  foreach ($fichier as $value)
  {

    if ($start && in_array($value, $start))
      echo "$value  [ \033[32mOK\033[0m ]\n";
    else if ($stop && in_array($value, $stop))
      echo "$value  [ \033[31mKO\033[0m ]\n";
    else
      echo "$value  [    ]\n";
  }
}
function modif_ps($tab, $i)
{
  $entre = "";
  $tab = my_split($tab);
  $start = $tab[0];
  $stop = $tab[1];

  while ($entre != "s" && $entre != "S" && $entre != "q" && $entre != "Q")
  {
    aff_ps($start, $stop);
    echo "\nEntrez le nom du processus que vous voulez modifier,\nS pour sauvegarder et Q pour revenir au menu.\n";
    $entre = readline("=> ");
    strtolower($entre);
    $stab = modif_stat($entre, $start, $stop, $i);
    $start = $stab[0];
    $stop = $stab[1];
  }
  if ($entre == 'q')
   echo "\033c";
}
function modif_stat($entre, $start, $stop, $i)
{
  $fichier = scandir("/etc/init.d");
  if (in_array($entre, $fichier))
  {
    if (!in_array($entre, $start) && !in_array($entre, $stop))
      array_push($start, $entre);
    else if (in_array($entre, $start) && !in_array($entre, $stop))
    {
      $key = array_search($entre, $start);
      array_push($stop, $entre);
      unset($start[$key]);
    }
    else
    {
      $key = array_search($entre, $stop);
      unset($stop[$key]);
    }
  }
  $start = clean_array($start);
  $stop = clean_array($stop);
  if ($entre == 's' || $entre == 'S')
    save($i, $start, $stop);
  else
    echo "\033c";
  $stab[0] = $start;
  $stab[1] = $stop;
  return($stab);
}
?>
