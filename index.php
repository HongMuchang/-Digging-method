<?php

//-----変数の初期設定-----
$bg_clr = ['black', 'white']; 
$dir_list = []; // 0=>上, 1=>下, 2=>左, 3=>右
$br_list = [];
$wid = 31;
$hgt = 31;
$pos = ['wid' => rand(1, ($wid-1)/2) * 2 - 1, 'hgt' => rand(1, ($hgt-1)/2) * 2 - 1];
$err_msg = [];
$end_flg = 0;
$dir_err_cnt = 0;
$sum = 0;
$total = ($wid - 1) * ($hgt - 1) / 2 - 1;

//-----配列の値を全て0にする-----
for ($i = 0; $i < $hgt; $i++) {
  for ($j = 0; $j < $wid; $j++) {
    $sq[$i][$j] = 0;
  }
}

//-----開始位置の値を1にする-----
$sq[$pos['hgt']][$pos['wid']] = 1;

//-----迷路作成ループ-----
while ($end_flg == 0) {

  // 進行可能方向を確認
  if ( ($pos['hgt'] - 2) >= 0 && $sq[$pos['hgt'] - 2][$pos['wid']] == 0 ) {
    $dir_list[0] = 1;
  }
  if ( ($pos['hgt'] + 2) < $hgt && $sq[$pos['hgt'] + 2][$pos['wid']] == 0 ) {
    $dir_list[1] = 1;
  }
  if ( ($pos['wid'] - 2) >= 0 && $sq[$pos['hgt']][$pos['wid'] - 2] == 0 ) {
    $dir_list[2] = 1;
  }
  if ( ($pos['wid'] + 2) < $wid && $sq[$pos['hgt']][$pos['wid'] + 2] == 0 ) {
    $dir_list[3] = 1;
  }

  // 進行可能かの判断
  if (array_sum($dir_list) != 0) {

    // ランダムで進む方向を決める
    $dir_num = array_rand($dir_list);
    
    // 分岐可能リストへの格納判断
    if (array_sum($dir_list) >= 2) {
      $br_list[] = [$pos['wid'], $pos['hgt']];
    }

    switch ($dir_num) {
      case 0: // 上方向
        $pos['hgt'] -= 2;
        $sq[$pos['hgt']][$pos['wid']] = 1;
        $sq[$pos['hgt'] + 1][$pos['wid']] = 1;
      break;
      
      case 1: // 下方向
        $pos['hgt'] += 2;
        $sq[$pos['hgt']][$pos['wid']] = 1;
        $sq[$pos['hgt'] - 1][$pos['wid']] = 1;
      break;
      
      case 2: // 左方向
        $pos['wid'] -= 2;
        $sq[$pos['hgt']][$pos['wid']] = 1;
        $sq[$pos['hgt']][$pos['wid'] + 1] = 1;
      break;
      
      case 3: // 右方向
        $pos['wid'] += 2;
        $sq[$pos['hgt']][$pos['wid']] = 1;
        $sq[$pos['hgt']][$pos['wid'] - 1] = 1;
      break;

      default: // 進行可能方向ではない時
        $dir_err_cnt++;
        $err_msg[] = '進行方向が正しく算出されませんでした。('.$dir_err_cnt.'回目)';
      break;
    }

  } else {

    // 分岐リストから進行可能な座標を取得
    foreach ($br_list as $key => $row) {
      if (!empty($row)) {
        $pos['wid'] = $row[0];
        $pos['hgt'] = $row[1];
        unset($br_list[$key]);
        break;
      }
    }

  }
  // 進行可能方向リストな初期化
  $dir_list = [];

  // ループの終了判断
  if (empty($br_list)) {
    $end_flg = 1;
  }

}

//-----1の値の数の確認-----
foreach ($sq as $row) {
  $sum += array_sum($row);
}
if ($sum != $total) {
  $err_msg[] = "処理の回数が正しくありません.";
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>自動生成迷路</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<?php if (empty($err_msg)) : ?>
  <table>
<?php   foreach ($sq as $row) : ?>
    <tr>
<?php     foreach ($row as $num) : ?>
      <td class="<?php echo $bg_clr[$num]; ?>"></td>
<?php     endforeach; ?>
    </tr>
<?php   endforeach; ?>
  </table>
<?php else : ?>
  <ul class="err">
<?php   foreach ($err_msg as $val) : ?>
    <li><?php echo $val; ?></li>
<?php   endforeach; ?>
  </ul>
<?php endif; ?>
</body>
</html>