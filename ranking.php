<?php 
@header('Content-Type: text/html; charset=UTF-8');
@date_default_timezone_set('Asia/Seoul');

include_once("config/config.php");


$connect = mysqli_connect($hostname,$username,$password,$database);
mysqli_select_db($connect, $database);

?>
<!DOCTYPE html>
<html lang="ko" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ranking</title>
  </head>
  <body>

  <table>
  <caption>
  <colgroup>
    <col style="width:10%">
    <col style="width:20%">
    <col style="width:50%">
    <col style="width:50%">
    </colgroup>
  <thead>
<tr>
    <th scope="col">순위</th>
    <th scope="col">이름</th>
    <th scope="col">점수</th>
    <th scope="col">날자</th>
</tr>
</thead>
<tbody>
<?php
  $sql="SELECT * FROM ranking ORDER BY score DESC";
  $re = mysqli_query($connect, $sql);
  $i = 1;
  while($data=mysqli_fetch_array($re)){
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

  <div class="goBack">돌아가기</div>
  </body>
</html>
<script src="https://code.jquery.com/jquery-1.11.1.js"></script>
<script>
$(".goBack").click(function() {
    location.href = 'index.php';
});
</script>





