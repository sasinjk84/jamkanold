
<SCRIPT FOR=window EVENT=onload LANGUAGE="JScript">
  //onloadImgResize('<?=$setup[board_width]?>');
</SCRIPT>

<STYLE type=text/css>
	#menuBar {
	}
	#contentDiv {
		WIDTH: <?=$setup[board_width]?>;
	}
</STYLE>
<table border="0" cellpadding="0" cellspacing="0" width="<?=$setup[board_width]?>">
<tr>
	<td>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
		<TR>
			<TD style="background:#f5f7f9;padding: 12px 0px;text-align: center;font-weight: normal;font-size:15px;line-height:160%;"><?=$strSubject?></TD>
		</TR>
		<TR>
			<TD height="30" align="right" style="padding-right:5px;" class="list_text"><B><?=$strName?></B><?=(($strDate || !$hide_hit_start)?", ":"")?><?=$strDate?><?=$strDate?", ":""?><?=($hide_hit_start?"":"HIT : ".$v_access)?>, ��õ : <?=$boardRow['vote']?></TD>
		</TR>
		<TR>
			<TD style="padding:30px 0px;">
				<DIV class=MsgrScroller id="contentDiv" style="OVERFLOW-x: auto; OVERFLOW-y: hidden">
				<DIV id=bodyList>
					<TABLE border="0" cellspacing="0" cellpadding="10" style="table-layout:fixed">
						<TR>
							<TD style="word-break:break-all;" bgcolor="<?=$view_body_color?>" valign="top">
								<?if ($upload_file1) {?>
								<span style="width:100%;line-height:160%;text-align:<?=$setup[img_align]?>">
								<?=$upload_file1?>
								</span>
								<?}?>
							</td>
						</tr>
						<TR>
							<TD style="word-break:break-all;" bgcolor="<?=$view_body_color?>" valign="top">
							<span style="width:100%;line-height:160%;">
								<?=$memo?>
							</span>
							</TD>
						</TR>
					</TABLE>
				</DIV>
				</DIV>
				<TABLE border="0" cellspacing="0" cellpadding="10" width="<?=$setup[board_width]?>">
					<? if ($file_name1) { ?>
					<TR>
						<TD align="right" class="list_text" height="30" style="padding-right:20px;">÷������ : <?=$file_name1?><?=($strIp?" ,".$strIp:"")?></TD>
					</TR>
					<? } else if($strIp) { ?>
					<TR>
						<TD align="right" class="list_text" height="30" style="padding-right:20px;"><?=$strIp?></TD>
					</TR>
					<? }?>
				</TABLE>
			</TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td>