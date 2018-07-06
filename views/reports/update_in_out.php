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
                    <td align="center" valign="middle">科博館 植物園 停車場&nbsp;&nbsp;&nbsp;&nbsp;大/小客車狀態更新<br></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0">
            <form action="#" method="post" enctype="application/x-www-form-urlencoded">
                <tr>
                    <td align="right" valign="middle">車牌號碼：<input type="text" name="lpr" value=""></td>
                    <td align="left" valign="middle"><input type="submit" value="車輛查詢"></td>
                </tr>
            </form>
                <tr>
                    <td align="center" valign="middle" colspan="2"><hr></td>
                </tr>

            <?php if(count($sel_cario_lpr)>0){
				
				if($sel_cario_lpr[0]['in_out']=="CI"){
					$in_out_1="CI";
					$in_out_2="TI";
				}elseif($sel_cario_lpr[0]['in_out']=="CO"){
					$in_out_1="CO";
					$in_out_2="TO";
				}elseif($sel_cario_lpr[0]['in_out']=="TI"){
					$in_out_1="CI";
					$in_out_2="TI";
				}else{
					$in_out_1="CO";
					$in_out_2="TO";
				}
				
				?>
            <form action="Update_in_out/save" method="post" enctype="application/x-www-form-urlencoded">
            	<input type="hidden" name="cario_no" value="<?php echo isset($sel_cario_lpr[0]['cario_no']) ? $sel_cario_lpr[0]['cario_no']:'';?>">
            	<input type="hidden" name="obj_id" value="<?php echo isset($sel_cario_lpr[0]['obj_id']) ? $sel_cario_lpr[0]['obj_id']:'';?>">
                <tr>
                    <td align="right" valign="middle">車牌號碼：</td>
                    <td align="left" valign="middle"><?php echo isset($sel_cario_lpr[0]['obj_id']) ? $sel_cario_lpr[0]['obj_id']:'';?></td>
                </tr>
                <tr>
                    <td align="right" valign="middle">入場時間：</td>
                    <td align="left" valign="middle"><?php echo isset($sel_cario_lpr[0]['in_time']) ? $sel_cario_lpr[0]['in_time']:'';?></td>
                </tr>
                <tr>
                    <td align="right" valign="middle">車輛類別：</td>
                    <td align="left" valign="middle">
					<select name="in_out">
                    	<option value="<?php echo $in_out_1;?>" <?php if($sel_cario_lpr[0]['in_out']==$in_out_1){echo "selected='selected'";}?>>小客車</option>
                    	<option value="<?php echo $in_out_2;?>" <?php if($sel_cario_lpr[0]['in_out']==$in_out_2){echo "selected='selected'";}?>>大客車</option>
                    </select>
					</td>
                </tr>
                <tr>
                    <td align="center" valign="middle" colspan="2"><input type="submit" value="類別更新"></td>
                </tr>
             </form>
             <?php }?>
           </table>
        </td>
    </tr>



</table>


</body>
</html>