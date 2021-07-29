/**
 * Created by x2chi-objet on 2014-10-02.
 */
/************************************************************
 사용 법
 *************************************************************
 <input type="text" name="birthM" id="birthM" style="width:80px;" readonly>
 <img src="/img/calen.gif" style="cursor:pointer;" onclick="(calStr1.style.display=='none')?calStr1.style.display='':calStr1.style.display='none';" align="absmiddle">
 <span id="calStr1" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
 <script>
 //함수호출
 show_cal('19850101','calStr1','birthM');
 //날짜를 지정하고 싶으면 -> show_cal('20040808','calStr1','birthM');
 //calStr은 해당 달력을 올려야 하는 div레이어(의 ID)
 //birthM 날짜가 입력될 폼
 // ''꼭입력...
 // Http://www.x2chi.com
 // x2chi@x2chi.com
 </script>

 /////
 <input type="text" name="endD" id="endD" value="<?=date("Ymd")?>" style="width:80px;" readonly>
 <img src="/images/mini_cal_calen.gif" style="cursor:pointer;" onclick="(endDC.style.display=='none') ? endDC.style.display='' : endDC.style.display='none';" align="absmiddle">
 <span id="endDC" style="position:absolute;display:none;border:1px solid #d9d9d9;padding:3px;background-color: #FFFFFF;"></span>
 <script>
 show_cal('<?=date("Ymd")?>','endDC','birthM');
 </script>

 ************************************************************/



//자바함수 라이브러리에 추가했으면 하는 함수덜..
function lpad(str,fill,leng)
{
	var n = leng - str.length;
	var out ="";
	for (i =0; i < n; i++)  out = out + fill;
	out=out+str;
	return out;
}



//parseInt에서는 '08'을 8진수로 인식해서 0이나 NaN을 돌려준다
function toInt(str)
{
	i=0;
	while(1)
	{
		if(str.substr(0,1)=='0') {
			str=str.substr(1,str.length)
		} else {
			break;
		}
		i++;
	}
	return parseInt(str);
}




//기념일에 해당하는 배열 전역변수(이는 서버사이드에서 동적으로 생성시켜줘야함);
//var anniversary = new Array();

function show_cal(selectDate,calDivObj,targetObj, dayLimit ) //selectDate이슈가 되는 날짜, calDivObj달력을 뿌릴 DIV태그 아이디, targetObj 날자 입력될 폼
{
	if(dayLimit==undefined) var dayLimit = 'unLimit';

	//전역변수들 세팅
	var selectDate = ''+selectDate; //전역변수1 - 이슈가 되는 날짜 지정
	today = new Date();
	var toDate = today.getFullYear() + lpad(''+(today.getMonth()+1),'0',2) + lpad(''+today.getDate(),'0',2); // 오늘날짜 지정
	if (selectDate == '') selectDate=toDate;

	var preMonDate;
	var nextMonDate;
	preMonDate= selectDate.substr(0,4)+lpad(''+(toInt(selectDate.substr(4,2))-1),'0',2)+selectDate.substr(6,2);
	nextMonDate= selectDate.substr(0,4)+lpad(''+(toInt(selectDate.substr(4,2))+1),'0',2)+selectDate.substr(6,2);
	if(selectDate.substr(4,2)=='01') preMonDate= (toInt(selectDate.substr(0,4))-1) + '12' + selectDate.substr(6,2);
	if(selectDate.substr(4,2)=='12') nextMonDate= (toInt(selectDate.substr(0,4))+1) + '01' + selectDate.substr(6,2);

	var firstDay = getFirstDay(selectDate.substr(0,4), selectDate.substr(4,2)); // 첫번째 요일의 숫자값
	var lastDay = getLastDay(selectDate.substr(0,4), selectDate.substr(4,2)); // 마지막 요일의 숫자값
	var daysOfMonth = getDaysOfMonth(selectDate.substr(0,4), selectDate.substr(4,2)); // 28, 29, 30, 31 중 하나


	var calString;//달력 HTML을 저장하기 위한 변수다.
	calString="<table border='0' cellspacing='0' cellpadding='0' style='font-size:11px'>";
	// 상단 컨트롤
	calString+="<tr style='color=#0000C6'><td colspan='7' align=center>";

	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ (parseInt(selectDate.substr(0,4))-1)+ selectDate.substr(4,4) +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_pre.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span OnClick=show_cal('"+ toDate +"','"+ calDivObj +"','"+targetObj+"');><font size='2'><b>"+selectDate.substr(0,4)+"</b></font></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ (parseInt(selectDate.substr(0,4))+1)+ selectDate.substr(4,4) +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_next.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ preMonDate +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_pre.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span OnClick=show_cal('"+ toDate +"','"+ calDivObj +"','"+targetObj+"');><font size='2' color='#FF6600'><b>"+selectDate.substr(4,2)+"</b></font></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick=show_cal('"+ nextMonDate +"','"+ calDivObj +"','"+targetObj+"');><img src='/images/mini_cal_next.gif' border=0 align=absmiddle></span>";
	calString+="&nbsp;";
	calString+="<span style=cursor:pointer; OnClick="+calDivObj+".style.display='none';><img src='/images/mini_cal_exit.gif' align=absmiddle></span>";

	calString+="</td></tr>";

	// 요일
	calString+="<tr height='5'><td colspan='7'></td></tr>";
	calString+="<tr height=19>";
	calString+="<td width='19' align=center style='color=#C60000'>S</td>";
	calString+="<td width='19' align=center>M</td>";
	calString+="<td width='19' align=center>T</td>";
	calString+="<td width='19' align=center>W</td>";
	calString+="<td width='19' align=center>T</td>";
	calString+="<td width='19' align=center>F</td>";
	calString+="<td width='19' align=center style='color=#0000C6'>S</td>";
	calString+="</tr>";

	// 달력 textfield 출력
	for (var i=0; i < Math.ceil( (firstDay+daysOfMonth)/7 ); i++) {
		calString+="<tr valign='middle' height='19'>";
		for (var j=1; j <= 7; j++) {
			colNum=i*7+j; //달력의 각 칸의 칼럼을 번호로 지정

			if (colNum>firstDay && colNum<firstDay+daysOfMonth+1) //달력에 날짜가 나와야 되는 조건
			{
				thisDay=colNum-firstDay; //이날의 날짜(숫자)

				//요일의 색깔을 결정하자 ㅋㅋ
				if(colNum%7==1) {tdColor="C60000";}
				else if(colNum%7==0) {tdColor="0000C6";}
				else {tdColor="333333";}

				//기념일일 경우
				/*
				 for(k=0;k<anniversary.length;k++)
				 {
				 if(thisDay==anniversary[k])
				 {
				 thisDay="<a href='http://www.x2chi.com'><b>"+thisDay+"</b></a>";
				 break;
				 }
				 }
				 */

				// 날짜 출력
				if(thisDay<10) thisDay="0"+thisDay;
				chkDate=selectDate.substr(0,4)+selectDate.substr(4,2)+thisDay;

				calString+="<td align=center onClick=miniCal_chk("+chkDate+","+calDivObj+",'"+targetObj+"','"+dayLimit+"'); style=color=#"+tdColor+";cursor:pointer; onMouseOver=this.style.background='#e4e4e4'; onMouseOut=this.style.background='#ffffff';>"+thisDay+"</td>";
			}
			else
			{
				calString+="<td></td>";
			}
		}
		calString+="</tr>";
	}
	calString+="</table>";

	//저장된 스트링변수를 DIV레이어에 올리자..
	//document.getElementById(calDivObj).innerHTML=calString;
}




/////////////////////////날짜 관련된 연산 함수들 시작////////////////////////////
function getDaysOfMonth(year, month) {
	var DOMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // 평년 월별 일수
	var lDOMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // 윤년 월별 일수

	if ((year % 4) == 0) {
		if ((year % 100) == 0 && (year % 400) != 0)
			return DOMonth[toInt(month)-1];

		return lDOMonth[toInt(month)-1];
	} else
		return DOMonth[toInt(month)-1];
}

// 첫번째 요일 구하기
function getFirstDay(year, month) {
	var tmpDate = new Date();
	tmpDate.setDate(1);
	tmpDate.setMonth(toInt(month)-1);
	tmpDate.setFullYear(year);
	return tmpDate.getDay();
}


// 마지막 요일 구하기
function getLastDay(year, month) {
	var tmpDate = new Date();
	tmpDate.setDate( getDaysOfMonth(year,month) );
	tmpDate.setMonth(toInt(month)-1);
	tmpDate.setFullYear(year);
	return tmpDate.getDay();
}
/////////////////////////날짜 관련된 연산 함수들 끝////////////////////////////



// 폼에 입력후 DIV 닫기
function miniCal_chk(chkDate,calDivObj,targetObj, dayLimit) {
	chkDate = String(chkDate);	
	today = new Date();
	
	if($j('#'+targetObj).data('dayLimit')) alert($j(targetObj).data('dayLimit'));
	var result = "";
	switch ( dayLimit ) {
		case "todayNext" : // 오늘 이후 날짜만 선택 가능 옵션
			if( Number(today.getFullYear()) <= Number(chkDate.substring(0,4)) && Number(today.getMonth()+1) <= Number(chkDate.substring(4,6)) && Number(today.getDate()) < Number(chkDate.substring(6,8)) ) {
				result = "todayNext OK";
			} else {
				result = "오늘("+today.getFullYear()+"/"+(today.getMonth()+1)+"/"+today.getDate()+") 이후의 날짜를 선택하세요!";
			}
			break;
		case "unLimit" : // 무조건 선택가능
			result = "unLimit OK";
			break;
		default :
			result = "OK";
			break;
	}

	if ( result == "todayNext OK" || result == "unLimit OK" || result == "OK" ) {
		document.getElementById( targetObj ).value=chkDate;
		
		calDivObj.style.display='none';
		if( result == "todayNext OK" ) {
			if( typeof form1 != 'undefined' ) priceCalc(form1);
			if( typeof quickfun_form1 != 'undefined' ) priceCalc(quickfun_form1);
		}
	} else {
		alert(result);
	}
}





