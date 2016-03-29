#!/usr/bin/php
<?php
include "fonc.php";
include "install.php";
function a()
{
  $repuser = scandir("./runlvl/");

  echo "Runlvl :\n";
  foreach ($repuser as $value)
    if (preg_match("/^runlvl.*$/" , $value) == 1)
      echo "\t$value\n";
  echo "\n\n";
}
function b()
{
  $tab = "";

  echo "Entrez votre niveau d'init ou Q pour revenir au menu.\n";
  $init = readline("=> ");
  if ($init != 'q' && $init != 'Q')
  {
    $tab = runlvl($init);
    if ($tab != 0)
      modif_ps($tab, $init);
    else
      echo "\033c";
  }
  else
   echo "\033c";
}
function c()
{
  $entre = "";
  $tab = scandir("./runlvl/");

  while ($entre != 'q' && $entre != 'Q')
  {
      $conf = "";
    a();
    echo "Entrez le nom du runlvl que vous voulez supprimer ou Q pour revenir au menu.\n";
    $entre = readline("=> ");
    if (preg_match("/^runlvl.*$/" , $entre) == 1 && in_array($entre, $tab))
    {
      while ($conf != 'Y' && $conf != 'n')
      {
        echo "Voulez vous vraiment supprimer $entre? (Y/n)\n";
        $conf = readline("=> ");
        if ($conf == 'Y')
        {
          exec("rm -rf ./runlvl/$entre");
          echo "\033c";
        }
        else if ($conf == 'n')
          echo "\033c";
      }
    }
    else if ($entre != "q" && $entre != "Q")
      echo "\033c"."Entrez un runlvl existant.\n";
  }
  echo "\033c";
}
function aff_panel()
{
  $entre = "";

  while ($entre != "q")
  {
    echo "\033c";
    if ($entre == "a" || $entre == "b" || $entre == "c" || $entre == "d")
    {
      $entre();
    }
    echo "\t\t\tSYSV-NG PANEL\nEntrez la lettre d'une des commandes suivantes:\n\n";
  	echo "\tA - Liste les runlvls\n\tB - Creer, Modifier les runlvls\n\tC - Supprimer des runlvls\n\tD - Installer un paquet (En cours de developpement)\n\tQ - Quitter\n";
    $entre = readline("=> ");
    $entre = strtolower($entre);
  }
}
exec_ps($argv, $argc);
?>
