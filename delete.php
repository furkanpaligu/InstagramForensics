 <?php

require_once ('Factory/MakeDBConnect.php');


 $stmt1 = $con->prepare('DROP DATABASE BrowSwExLite');
 $stmt1->execute();
 $stmt1->close();
 echo("<br>BrowSwExLite Database Deleted<br>");
 
 
 header('Location: index.php');
?> 