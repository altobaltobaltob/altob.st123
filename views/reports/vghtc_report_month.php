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
                    <td align="center" valign="middle">臺中榮總南區停車場&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $month;?>月車流量表<br></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">月總流量: <?php echo $monthba;?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center" valign="middle">日均流量: <?php echo floor($dateba);?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td align="center" valign="middle">時均流量: <?php echo floor($hourba);?></td>
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
<?php
				if($month<10){
					$month0="0".$month;
				}else{
					$month0=$month;
				}
	 $start00=date('Y-'.$month0.'-01'); ?>

    <tr>
        <td>
            <table align="center" border="1" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">日期/出入口</td>
                    <?php foreach($sel_in_lane[$start00] as $key0 => $val0){?>
                    <td align="center" valign="middle"><?php echo $val0['name'];?></td>
                    <?php }?>
                    <td align="center" valign="middle">日流量</td>
                </tr>
                <?php 
				 for($i=1; $i<=$dd; $i++){	
				 
							if($i<10){
								$i0="0".$i;
							}else{
								$i0=$i;
							}
				 $start=date('Y-'.$month0.'-'.$i0); ?>
                <tr>
                    <td align="center" valign="middle"><?php echo $i;?></td>
                    <?php foreach($dateba_lane[$start] as $key3 => $val3){?>
                    <td align="center" valign="middle"><?php echo $val3;?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo array_sum($dateba_lane[$start]);?></td>
                </tr>
               <?php }?>

                <tr>
                    <td align="center" valign="middle">各出口月流量</td>
                    <?php foreach($dateba_lane_tot as $key3 => $val3){?>
                    <td align="center" valign="middle"><?php echo $val3;?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo array_sum($dateba_lane_tot);?></td>
                </tr>
                <tr>
                    <td align="center" valign="middle">各出口日流量</td>
                    <?php foreach($dateba_lane_tot as $key3 => $val3){?>
                    <td align="center" valign="middle"><?php echo floor($val3/$dd);?></td>
                    <?php }?>
                    <td align="center" valign="middle"><?php echo floor(array_sum($dateba_lane_tot)/$dd);?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right" valign="middle">製表:歐特儀</td>
    </tr>


</table>


</body>
</html>