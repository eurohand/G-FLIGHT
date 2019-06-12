<?php 
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');

include_once("config/config.php");
$hostname = 'thzz882efnak0xod.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
$username = 'wnvaak8rsg3pchfw';
$password = 'pimtbqjggrbf1tiv';
$database = 'clp3n4gm746vekgy';

$connect = mysqli_connect($hostname,$username,$password,$database);
mysqli_select_db($connect, $database);

?>
<!DOCTYPE html>
<html lang="ko" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>G-FLGHT (RANKING)</title>
  </head>
  <body>
  <style>

   @font-face {
    font-family: 'VT323'; /*a name to be used later*/
    src: url('https://fonts.googleapis.com/css?family=VT323&display=swap'); /*URL to font*/
  }

    #container{
      width: 1480px;
      height: 680px;
      border: 10px solid gray;
      background-color: black;
      color: white;
    }
    #table{
      width: 50%;
      height: 70%;
      margin-left: auto;
      margin-right: auto;
      margin-top: 10%;
      text-align: center;
      font family: 'VT323';
    }

  </style>


  <div id="container">
    <table id="table">
      <caption>
        <colgroup>
          <col style="width:10%">
          <col style="width:20%">
          <col style="width:50%">
          <col style="width:50%">
        </colgroup>
        <thead>
          <tr>
            <th scope="col">RANK</th>
            <th scope="col">NAME</th>
            <th scope="col">SCORE</th>
            <th scope="col">DATE</th>
            </tr>
        </thead>

        <tbody>
        <?php
          $sql="SELECT * FROM ranking ORDER BY score DESC";
          $re = mysqli_query($connect, $sql);
          $i = 1;
          while($data=mysqli_fetch_array($re)){
            if($i>10){
              break;
            }
        ?>
        <tr>
            <th><?php echo $i;?></th>
            <th><?php echo $data['name']; ?></th>
            <th><?php echo $data['score']; ?></th>
            <th><?php echo $data['date']; ?></th>
        </tr>

        <?php
            $i++;
          }
          mysqli_close($connect);
          ?>

        </tbody>
      </caption>
    </table>
  </div>









 

  <div class="goBack">돌아가기</div>
  </body>
</html>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script>
$(".goBack").click(function() {
    location.href = 'index.php';
});
</script>





