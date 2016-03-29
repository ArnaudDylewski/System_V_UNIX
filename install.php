<?php
function d()
{
  $valid = "";
  $inp = "";

  while ($inp != "q" && $inp != "Q")
  {
    echo "Entrer le nom du paquet a installer ou Q pour retourner au menu.\n";
    $inp = readline("=>");
    echo "\033c";
    if ($inp != "q" && $inp != "Q")
    {
      $op = opendir("ftp://ftp.fr.debian.org/debian/pool/main/" . $inp[0] . "/");
      while (($file = readdir($op)) != FALSE)
      {
        if ($file == $inp)
          exec("aptitude install $inp" , $result);
      }
      if (isset($result))
        echo "INSTALLATION \ MAJ ($inp) :\t [ \033[32mOK\033[m ]\n";
      else
      {
        echo "INSTALLATION \ MAJ ($inp) :\t [ \033[31mKO\033[m ]\n";
        echo "Paquet incomplet ou invalide\n";
      }
      closedir($op);
    }
  }
}
?>
