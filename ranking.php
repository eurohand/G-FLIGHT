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
    body{
      font-family: 'VT323';
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
    table{
      width: 70%;
      margin-left: auto;
      margin-right: auto;
      margin-top: 3%;
      text-align: center;
      font-size: 40px;
    }
    th{
      font-size: 50px;
      color: slategray;
    }

    th, td{
      padding: 10px;
    }

    #goBack{
      position: absolute;
      left: 110px;
      top: 640px;
      color: white;
      font-family: 'VT323';
      font-size: 40px;
    }

    .dot{
      position: absolute;
      left: random(1500);
      top: random(700);
    }

    span{
      font-size: 20px;
      text-align: right;
    }

    h1{
      margin-top: 1em;
      font-size: 50px;
    }

  </style>


  <div id="container">
    <div id="total">
      <h1>TOTAL RANK</h1>
      <table id="totalTable">
        <caption>
          <colgroup>
            <col style="width:10%">
            <col style="width:30%">
            <col style="width:15%">
            <col style="width:30%">
            <col style="width:15%">
          </colgroup>
          <thead>
            <tr>
              <th scope="col">RANK</th>
              <th scope="col">NAME</th>
              <th scope="col">SCORE</th>
              <th scope="col">DATE</th>
              <th scope="col">HASH</th>
              </tr>
          </thead>

          <tbody>
          <?php
            $sql="SELECT * FROM ranking ORDER BY score DESC";
            $re = mysqli_query($connect, $sql);
            $i = 1;
            while($data=mysqli_fetch_array($re)){
              if($i>5){
                break;
              }
          ?>
          <tr>
              <td><?php echo $i;?></td>
              <td><?php echo $data['name']; ?></td>
              <td><?php echo $data['score']; ?></td>
              <td><?php echo $data['date']; ?></td>
              <td><?php echo $data['hash']; ?></td>
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
    
    
    <div id="goBack">
    ESC: HOME &nbsp; &nbsp; &nbsp; ENTER: SEARCH &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
          <span>
          LEFT: PREVIOUS DAY &nbsp; &nbsp; &nbsp; RIGHT: NEXT DAY &nbsp; &nbsp; &nbsp; UP & DOWN: CHANGE RANK MODE
          </span>
    </div>
  </div>









 

  

  <script>
  const BODY = document.querySelector('body');
  let mode = true;
  
  
  let Dot = function(x, y, r, color){
    let div = document.createElement('div');
    div.className = 'dot';
    div.style.backgroundColor = "#" + color + color + color;
    div.style.width = r + 'px';
    div.style.height = r + 'px';
    div.style.position = 'absolute';
    div.style.left = x + 'px';
    div.style.top = y + 'px';
    document.getElementById("container").appendChild(div);
		this.color = color+color+color;
	}
	

  let random = (number) => Math.floor(Math.random() * number);
  let dots = [];
  for(let i=0 ; i<250 ; i++){
		dots.push(new Dot(random(1500), random(700), random(4), random(14).toString(16)));
	}





  BODY.addEventListener("keydown", function(event){
    if(keyActions[event.keyCode] === "esc"){
      location.href = 'index.php';
    }else if(keyActions[event.keyCode] === "enter"){
      search();
    }else if(keyActions[event.keyCode] === "arrowUp" || keyActions[event.keyCode] === "arrowDown"){
      changeMode();
    }else if(keyActions[event.keyCode] === "arrowLeft"){
      
    }else if(keyActions[event.keyCode] === "arrowRight"){
      
    }
      
  })

  let keyActions = {
      13: "enter",
      
      38: "arrowUp",
      40: "arrowDown",
      37: "arrowLeft",
      39: "arrowRight",
      
      27: "esc"
    };


    //event handle function
    function search(){
      let searchName = prompt("TYPE NAME ?");
      let hashIndex = hash(searchName);
      <?php 
        $searchName = "<script>document.write (searchName);</script>";
        $hashIndex = "<script>document.write (hashIndex);</script>";

        $sql="SELECT * FROM ranking ORDER BY score DESC WHERE hash=$hashIndex AND name=$searchName";
        $searchre = mysqli_query($connect, $sql);
      ?>
      console.log(<?php $searchre ?>);
    }

    function changeMode(){
      mode = !mode;
    }

    function hash(name){
		  let key = 0;
		  for(let i=0 ; i<name.length ; i++){
		  	key += name.charCodeAt(i);
		  }
		  return key%TABLE_SIZE;
  	}

  </script>
  </body>
</html>






