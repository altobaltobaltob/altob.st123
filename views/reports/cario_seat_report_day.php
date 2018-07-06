<?php
define('SERVER_URL', 'http://61.219.216.35/');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-80594411-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-80594411-1');
</script>
</head>
	<script type="text/javascript" src="<?=SERVER_URL?>public/JSCal2/src/js/jscal2.js"></script>
    <link type="text/css" rel="stylesheet" href="<?=SERVER_URL?>public/JSCal2/src/css/jscal2.css" />
    <link type="text/css" rel="stylesheet" href="<?=SERVER_URL?>public/JSCal2/src/css/border-radius.css" />
    <link id="skin-gold" title="Gold" type="text/css" rel="stylesheet" href="<?=SERVER_URL?>public/JSCal2/src/css/gold/gold.css" />
    <script language="JavaScript" type="text/JavaScript" charset="utf-8">
    <!--
    Calendar.LANG("b5", "正體中文", {
            fdow: 1,                // first day of week for this locale; 0 = Sunday, 1 = Monday, etc.
            goToday: "移至今天",
            today: "今天",         // appears in bottom bar
            wk: "周",
            weekend: "0,6",         // 0 = Sunday, 1 = Monday, etc.
            AM: "上午",
            PM: "下午",
            mn : [ "一月",
                   "二月",
                   "三月",
                   "四月",
                   "五月",
                   "六月",
                   "七月",
                   "八月",
                   "九月",
                   "十月",
                   "十一月",
                   "十二月" ],
    
            smn : [ "一月",
                   "二月",
                   "三月",
                   "四月",
                   "五月",
                   "六月",
                   "七月",
                   "八月",
                   "九月",
                   "十月",
                   "十一月",
                   "十二月"  ],
    
            dn : [ "周日",
                   "周一",
                   "周二",
                   "周三",
                   "周四",
                   "周五",
                   "周六",
                   "周日" ],
    
            sdn : [ "日",
                    "一",
                    "二",
                    "三",
                    "四",
                    "五",
                    "六",
                    "日" ]
    
    });
    //-->
    </script>
<body topmargin="0" leftmargin="0" rightmargin="0">
<table align="center" border="0" width="90%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">臺中榮總南區停車場&nbsp;停車時間分析表<br></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size:22px;">
                        <?php
						if($this->input->post('sin_time', TRUE)){
							$sin_time=$sin_time;
						}else{
							$sin_time=date('Y-m-01');
						}
						if($this->input->post('ein_time', TRUE)){
							$ein_time=$this->input->post('ein_time', TRUE);
						}else{
							$ein_time=date('Y-m-d');
						}
						//print_r($sel_hq_stations);
						?>
                            <div class="form-group">
                            <form action="" method="post" enctype="application/x-www-form-urlencoded">
                            <label class="radio-inline">日期：起<input type="text" name="sin_time" id="sin_time" value="<?php echo $sin_time;?>">
                            <input type="button" value=".." id="BTN01EDATE" name="BTN01EDATE">
                            <script type="text/javascript">
                                new Calendar({
                                    inputField: "sin_time",
                                    dateFormat: "%Y-%m-%d",
                                    trigger: "BTN01EDATE",
                                    bottomBar: true,
                                    weekNumbers: true,
                                    showTime: 24,
                                    onSelect: function() {this.hide();}
                                });
                            </script>
                            </label>
                            <label class="radio-inline">迄<input type="text" name="ein_time" id="ein_time" value="<?php echo $ein_time;?>">
                            <input type="button" value=".." id="BTN02EDATE" name="BTN02EDATE">
                            <script type="text/javascript">
                                new Calendar({
                                    inputField: "ein_time",
                                    dateFormat: "%Y-%m-%d",
                                    trigger: "BTN02EDATE",
                                    bottomBar: true,
                                    weekNumbers: true,
                                    showTime: 24,
                                    onSelect: function() {this.hide();}
                                });
                            </script>
                            </label>
                            <label class="input-inline"><input type="submit" value="查詢" /></label> 
                            </form>
                            </div>
                        </div>
        </td>
    </tr>

    <tr>
        <td>
            <table align="center" width="90%" border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center" valign="middle">日期</td>
                    <td align="center" valign="middle">有效停車車輛</td>
                    <td align="center" valign="middle">完成停車時間(分鐘)</td>
                    <td align="center" valign="middle">完成停車平均時間(分鐘)</td>
                </tr>
                <?php foreach($in_lane_seat_avg as $key0 => $value0){?>
                <tr>
                    <td align="center" valign="middle"><?php echo $value0['in_date'];?></td>
                    <td align="center" valign="middle"><?php echo $value0['countlpr'];?></td>
                    <td align="center" valign="middle"><?php echo $value0['tot'];?></td>
                    <td align="center" valign="middle"><?php echo $value0['avg'];?></td>
                </tr>
                <?php }?>
            </table>
        </td>
    </tr>
    <tr>
        <td align="right" valign="middle">製表:歐特儀</td>
    </tr>

    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td><br></td>
    </tr>
	


</table>


</body>
</html>