<?php

unset($func_result);
$func_result="";

$sqlTLT = "select * from TLT where ID_HSP=".$GLOBALS["cur_hsp"] ." and ID_PAT=" .$GLOBALS["cur_pat"] ." and MEASURE_PERIOD NOT IN (5,6,7,9,11,13,15,17,19) order by MEASURE_PERIOD";
$sqlAD = "select INPUT_AT, AD_SIST, AD_DIAS from TLT where ID_HSP=".$GLOBALS["cur_hsp"] ." and ID_PAT=" .$GLOBALS["cur_pat"] ." order by MEASURE_PERIOD";
$sqlBeginTLT = "select DATE_BEGIN_TLT, TIME_BEGIN_TLT from TLT where ID_HSP=".$GLOBALS["cur_hsp"] ." and ID_PAT=" .$GLOBALS["cur_pat"] . " and MEASURE_PERIOD = 0";
$sqlPatient = "select FAM, NAM, OTS, D_BIR from PAT_LIST where ID_PAT=" .$GLOBALS["cur_pat"];

$resTLT = $GLOBALS["db"]->query($sqlTLT);
$resAD = $GLOBALS["db"]->query($sqlAD);
$resBeginTLT = $GLOBALS["db"]->query($sqlBeginTLT);
$resPatient = $GLOBALS["db"]->query($sqlPatient);

while ($patient = $GLOBALS["db"]->fetch_object($resPatient))
{
  $fam = $patient->FAM;
  $nam = $patient->NAM;
  $ots = $patient->OTS;
  $dBir = date('d.m.Y', $patient->D_BIR);
}

while ($beginTLT = $GLOBALS["db"]->fetch_object($resBeginTLT))
{
  $date = date('d.m.Y', strtotime($beginTLT->TIME_BEGIN_TLT));
  $time = $beginTLT->TIME_BEGIN_TLT;
}

while ($TLT = $GLOBALS["db"]->fetch_object($resTLT))
{
  $rowTLTArray['INPUT_AT'][] = $TLT->INPUT_AT;
  $rowTLTArray['CONSCIOUSNESS'][] = $TLT->CONSCIOUSNESS;
  $rowTLTArray['QUESTIONS'][] = $TLT->QUESTIONS;
  $rowTLTArray['COMMANDS'][] = $TLT->COMMANDS;
  $rowTLTArray['EYEBALLS'][] = $TLT->EYEBALLS;
  $rowTLTArray['FIELDS_VIEW'][] = $TLT->FIELDS_VIEW;
  $rowTLTArray['FACIAL_MUSCLES'][] = $TLT->FACIAL_MUSCLES;
  $rowTLTArray['LEFT_HAND'][] = $TLT->LEFT_HAND;
  $rowTLTArray['RIGHT_HAND'][] = $TLT->RIGHT_HAND;
  $rowTLTArray['LEFT_LEG'][] = $TLT->LEFT_LEG;
  $rowTLTArray['RIGHT_LEG'][] = $TLT->RIGHT_LEG;
  $rowTLTArray['ATAXIA'][] = $TLT->ATAXIA;
  $rowTLTArray['SENSITIVITY'][] = $TLT->SENSITIVITY;
  $rowTLTArray['APHASIA'][] = $TLT->APHASIA;
  $rowTLTArray['DYSARTHRIA'][] = $TLT->DYSARTHRIA;
  $rowTLTArray['IGNORING'][] = $TLT->IGNORING;
  $rowTLTArray['TOTAL'][] = $TLT->TOTAL;
}

while ($AD = $GLOBALS["db"]->fetch_object($resAD))
{
  $rowAdArray['INPUT_AT'][] = $AD->INPUT_AT;
  $rowAdArray['AD_SIST'][] = $AD->AD_SIST;
  $rowAdArray['AD_DIAS'][] = $AD->AD_DIAS;
}

function getHour()
{
  $row = "";
  for ($i=2; $i<25; $i++) {
    $row .= "<td align=center >$i</td>";
  }
  $row .= "</tr>";

  return $row;
}

function getTime($array)
{
  $row = "";
  foreach ($array as $time) {
    $row .= "<td align=center>&nbsp;<br>" . substr($time, 0, 2) . "<br>" . substr($time, -2) . "</td>";
  }
  $row .= "</tr>";

  return $row;
}

function getValue($array)
{
  $row = "";
  foreach ($array as $value) {
    $row .= "<td align=center>$value</td>";
  }
  $row .= "</tr>";

  return $row;
}

function getTimeForAd($array, $start, $end)
{
  $row = "";
  for ($i = $start; $i < $end; $i++) {
    $row .= "<td align=center>&nbsp;<br>" . substr($array[$i], 0, 2) . "<br>" . substr($array[$i], -2) . "</td>";
  }
  $row .= "</tr>";

  return $row;
}

function getValueForAd($array1, $array2, $start, $end)
{
  $row = "";
  for ($i = $start; $i < $end; $i++) {
    $row .= "<td align=center>&nbsp;<br>" .$array1[$i] ."<br>". $array2[$i]."</td>";
  }
  $row .= "</tr>";

  return $row;
}

$func_result = "<p style='font-weight: bold; text-align: center; margin-bottom: 0'>����� �������� ������������� ��������� �������� (NIHSS)</p>"
  ."<hr>"
  ."<p style='font-weight: bold; margin-bottom: 0'>�������: $fam $nam $ots ($dBir �.�.)</p>"
  ."<p style='font-weight: bold; margin-bottom: 0'>���� � ����� ������ ���: $date �., $time</p>"
  ."<table border='1'>"
  ."<tr><td colspan='2' rowspan='2'></td><td align=center><b>��</b></td><td colspan='4' align=center>���������� ��� (������)</td><td colspan='23' align=center>24 ���� (����)</td></tr>"
  ."<tr><td align=center><b>���</b></td><td align=center>15</td><td align=center>30</td><td align=center>45</td><td align=center>60</td>" .getHour()
  ."<tr><td colspan='2' align=right><b>�����:</b><br>���<br>���</td>" .getTime($rowTLTArray['INPUT_AT'])
  ."<tr><td colspan='30'><span><b>1.1 ������� ��������: </b></span>0 - �������� �����; 1 - ���������; 2 - �����; 3 - ����</td></tr>"
  ."<tr><td colspan='2'><b>1.1</b></td>" .getValue($rowTLTArray['CONSCIOUSNESS'])
  ."<tr><td colspan='30'><span><b>1.2 ������ �� �������: </b></span>0 - ��������� �� ��� �������; 1 - ��������� �� ���� ������; 2 - ��������� �� �� ���� ������</td></tr>"
  ."<tr><td colspan='2'><b>1.2</b></td>" .getValue($rowTLTArray['QUESTIONS'])
  ."<tr><td colspan='30'><span><b>1.3 ���������� ������: </b></span>0 - ��������� ��� �������; 1 - ��������� ���� �������; 2 - �� ���� ���������</td></tr>"
  ."<tr><td colspan='2'><b>1.3</b></td>" .getValue($rowTLTArray['QUESTIONS'])
  ."<tr><td colspan='30'><span><b>2. �������� ������� �����: </b></span>0 - �����; 1 - ��������� ����� �����; 2 - �������� �/� ��� ������ ������� �����</td></tr>"
  ."<tr><td colspan='2'><b>2.</b></td>" .getValue($rowTLTArray['COMMANDS'])
  ."<tr><td colspan='30'><span><b>3. ���� ������: </b></span>0 - �����; 1 - ��������� �����������; 2 - ������ �����������</td></tr>"
  ."<tr><td colspan='2'><b>3.</b></td>" .getValue($rowTLTArray['EYEBALLS'])
  ."<tr><td colspan='30'><span><b>4. ����� ������� �����������: </b></span>0 - �����; 1 - �����������; 2 - ������ ���������� ����; 3 - ������� � ������</td></tr>"
  ."<tr><td colspan='2'><b>4.</b></td>" .getValue($rowTLTArray['FIELDS_VIEW'])
  ."<tr><td colspan='30'><span><b>5. �������� � �����: </b></span>0 - ��� ��������� 10 ���; 1 - �������� ����� 10 ���; 2 - ������ �������������� ������������� ���� �������; 3 - ��� ������������� ���� �������; 4 - ��� �������� ��������; 9 - ���������� ���������</td></tr>"
  ."<tr><td colspan='2'>�����</td>" .getValue($rowTLTArray['LEFT_HAND'])
  ."<tr><td colspan='2'>������</td>" .getValue($rowTLTArray['RIGHT_HAND'])
  ."<tr><td colspan='30'><span><b>5. �������� � �����: </b></span>0 - ��� ��������� 5 ���; 1 - �������� ����� 5 ���; 2 - ������ �������������� ������������� ���� �������; 3 - ��� ������������� ���� �������; 4 - ��� �������� ��������; 9 - ���������� ���������</td></tr>"
  ."<tr><td colspan='2'>�����</td>" .getValue($rowTLTArray['LEFT_LEG'])
  ."<tr><td colspan='2'>������</td>" .getValue($rowTLTArray['RIGHT_LEG'])
  ."<tr><td colspan='30'><span><b>7. ������� �����������: </b></span>0 - �����������; 1 - � ����� ����������; 2 - � ���� �����������</td></tr>"
  ."<tr><td colspan='2'><b>7.</b></td>" .getValue($rowTLTArray['ATAXIA'])
  ."<tr><td colspan='30'><span><b>8. ���������������� (�������): </b></span>0 - �����; 1 - ������ ��� ��������� ���������; 2 - ������. ��� ������ ���������</td></tr>"
  ."<tr><td colspan='2'><b>8.</b></td>" .getValue($rowTLTArray['SENSITIVITY'])
  ."<tr><td colspan='30'><span><b>9. ������: </b></span>0 - ��� ������; 1 - ������ ��� ��������� ������; 2 - ������ ������; 3 - ���������� ����</td></tr>"
  ."<tr><td colspan='2'><b>9.</b></td>" .getValue($rowTLTArray['APHASIA'])
  ."<tr><td colspan='30'><span><b>10. ���������: </b></span>0 - ���������� �����������; 1 - ������ ��� ��������� ���������; 2 - ������ ���������</td></tr>"
  ."<tr><td colspan='2'><b>10.</b></td>" .getValue($rowTLTArray['DYSARTHRIA'])
  ."<tr><td colspan='30'><spanp><b>11. ������������� (�������): </b></spanp>0 - �����������; 1 - ����������� ����� ��������� �����������; 2 - ���������� ����������� ��� ����������� >1 ��������� �������.</td></tr>"
  ."<tr><td colspan='2'><b>11.</b></td>" .getValue($rowTLTArray['IGNORING'])
  ."<tr style='font-weight: bold'><td colspan='2'><span><b>����� ����: </b></span></td>" .getValue($rowTLTArray['TOTAL'])
  ."</table>"

  ."<p style='font-weight: bold; text-align: center;'>������������ �������� (�� ��. ��.)</p>"
  ."<table border='1'>"
  ."<tr><td rowspan='2'></td><td align=center><b>��</b></td><td colspan='8' align=center><b>������ 2 ���� (������)</b></td><td colspan='9' align=center><b>��������� 6 ����� (����)</b></td></tr>"
  ."<tr><td align=center><b>���</b></td><td align=center>15</td><td align=center>30</td><td align=center>45</td><td align=center>60</td><td align=center>75</td><td align=center>90</td><td align=center>105</td><td align=center>120</td>"
  ."<td align=center>2,5</td><td align=center>3</td><td align=center>3,5</td><td align=center>4</td><td align=center>4,5</td><td align=center>5</td><td align=center>5,5</td><td align=center>6</td><td align=center>6,5</td></tr>"
  ."<tr><td align=right><b>�����:</b><br>���<br>���</td>" .getTimeForAd($rowAdArray['INPUT_AT'], 0, 18)
  ."<tr><td align=right><b>��:</b><br>����<br>����</td>" .getValueForAd($rowAdArray['AD_SIST'], $rowAdArray['AD_DIAS'], 0, 18 )
  ."</table>"

  ."<table border='1'>"
  ."<tr><td rowspan='2'></td></td><td colspan='19' align=center><b>��������� 16 ����� (����)</b></td></tr>"
  ."<tr><td align=center>7</td><td align=center>7,5</td><td align=center>8</td><td align=center>9</td><td align=center>10</td><td align=center>11</td>"
  ."<td align=center>12</td><td align=center>13</td><td align=center>14</td><td align=center>15</td><td align=center>16</td><td align=center>17</td>"
  ."<td align=center>18</td><td align=center>19</td><td align=center>20</td><td align=center>21</td><td align=center>22</td><td align=center>23</td><td align=center>24</td></tr>"
  ."<tr><td align=right><b>�����:</b><br>���<br>���</td>" .getTimeForAd($rowAdArray['INPUT_AT'], 18, 37)
  ."<tr><td align=right><b>��:</b><br>����<br>����</td>" .getValueForAd($rowAdArray['AD_SIST'], $rowAdArray['AD_DIAS'], 18, 37 )
  ."</table>"
?>

