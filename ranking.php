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
    <link rel="preload" href="font/pxiKyp0ihIEF2isQFJXGdg.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="font/pxiKyp0ihIEF2isRFJXGdg.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="font/pxiKyp0ihIEF2isfFJU.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <meta charset="utf-8">
    <title>G-FLGHT (RANKING)</title>
  </head>
  <body>
  <style>

   /* vietnamese */
    @font-face {
      font-family: 'VT323';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: local('VT323 Regular'), local('VT323-Regular'), url(./font/pxiKyp0ihIEF2isQFJXGdg.woff2) format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+1EA0-1EF9, U+20AB;
    }
    /* latin-ext */
    @font-face {
      font-family: 'VT323';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: local('VT323 Regular'), local('VT323-Regular'), url(./font/pxiKyp0ihIEF2isRFJXGdg.woff2) format('woff2');
      unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    /* latin */
    @font-face {
      font-family: 'VT323';
      font-style: normal;
      font-weight: 400;
      font-display: swap;
      src: local('VT323 Regular'), local('VT323-Regular'), url(./font/pxiKyp0ihIEF2isfFJU.woff2) format('woff2');
      unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }

    #container{
      width: 1480px;
      height: 680px;
      border: 10px solid gray;
      background-color: black;
      color: white;
      font family: 'VT323';
      text-align: center;
    }
    #dbtable{
      width: 70%;
      margin-left: auto;
      margin-right: auto;
      margin-top: 5%;
      text-align: center;
      font-family: 'VT323';
      font-size: 40px;
    }

    th, td{
      padding: 10px;
    }

  </style>


  <div id="container">
    <table id="dbtable">
      <caption>
        <colgroup>
          <col style="width:20%">
          <col style="width:30%">
          <col style="width:20%">
          <col style="width:30%">
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





