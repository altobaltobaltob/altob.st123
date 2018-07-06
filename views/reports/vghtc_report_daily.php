<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle"><?php echo $station_arr['results'][0]['short_name'];?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $month;?>月車流量表<br></td>
                </tr>
            </table>
        </td>
    </tr>
	<?php //print_r($dateba_lane);exit;
				if($month<10){
					$month0="0".$month;
				}else{
					$month0=$month;
				}
	 for($i=1; $i<=$dd; $i++){	
	 
				if($i<10){
					$i0="0".$i;
				}else{
					$i0=$i;
				}
	 $start=date('Y-'.$month0.'-'.$i0); ?>
    <tr>
        <td>
            <table align="left" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="left" valign="middle"><br></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle"><?php echo $start;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center" valign="middle">日均流量: <?php echo array_sum($dateba_lane[$start]);?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center" valign="middle">時均流量: <?php echo floor(array_sum($dateba_lane[$start])/24);?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td><br></td>
    </tr>

    <tr>
        <td>
            <table align="center" border="1" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">小時/出入口</td>
                    <?php foreach($sel_in_lane[$start] as $key0 => $val0){?>
                    <td align="center" valign="middle"><?php echo $val0['name'];?></td>
                    <?php }?>
                    <td align="center" valign="middle">時流量</td>
                </tr>
                
                <?php foreach($sel_cario_tatal[$start] as $key1 => $val1){?>
                <tr>
                    <td align="center" valign="middle"><?php echo $val1['time'];?></td>
                    <?php foreach($val1['sel_cario'] as $key2 => $val2){?>
                    <td align="center" valign="middle"><?php echo $val2;?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo $val1['dateba0'];?></td>
                </tr>
				<?php }?>
                <tr>
                    <td align="center" valign="middle">各出口日流量</td>
                    <?php foreach($dateba_lane[$start] as $key3 => $val3){?>
                    <td align="center" valign="middle"><?php echo $val3;?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo array_sum($dateba_lane[$start]);?></td>
                </tr>
                <tr>
                    <td align="center" valign="middle">各出口時流量</td>
                    <?php foreach($dateba_lane[$start] as $key3 => $val3){?>
                    <td align="center" valign="middle"><?php echo floor($val3/24);?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo floor(array_sum($dateba_lane[$start])/24);?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
    </tr>
	<?php }?>
	
    <tr>
        <td align="right" valign="middle">製表:歐特儀</td>
    </tr>


</table>


</body>
</html>