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

$func_result = "<p style='font-weight: bold; text-align: center; margin-bottom: 0'>Шкала Инсульта Национального Института Здоровья (NIHSS)</p>"
  ."<hr>"
  ."<p style='font-weight: bold; margin-bottom: 0'>Пациент: $fam $nam $ots ($dBir г.р.)</p>"
  ."<p style='font-weight: bold; margin-bottom: 0'>Дата и время начала ТЛТ: $date г., $time</p>"
  ."<table border='1'>"
  ."<tr><td colspan='2' rowspan='2'></td><td align=center><b>До</b></td><td colspan='4' align=center>проведение ТЛТ (минуты)</td><td colspan='23' align=center>24 часа (часы)</td></tr>"
  ."<tr><td align=center><b>ТЛТ</b></td><td align=center>15</td><td align=center>30</td><td align=center>45</td><td align=center>60</td>" .getHour()
  ."<tr><td colspan='2' align=right><b>Время:</b><br>час<br>мин</td>" .getTime($rowTLTArray['INPUT_AT'])
  ."<tr><td colspan='30'><span><b>1.1 Уровень сознания: </b></span>0 - сознание ясное; 1 - оглушение; 2 - сопор; 3 - кома</td></tr>"
  ."<tr><td colspan='2'><b>1.1</b></td>" .getValue($rowTLTArray['CONSCIOUSNESS'])
  ."<tr><td colspan='30'><span><b>1.2 Ответы на вопросы: </b></span>0 - правильно на оба вопроса; 1 - правильно на один вопрос; 2 - правильно ни на один вопрос</td></tr>"
  ."<tr><td colspan='2'><b>1.2</b></td>" .getValue($rowTLTArray['QUESTIONS'])
  ."<tr><td colspan='30'><span><b>1.3 Выполнение команд: </b></span>0 - правильно обе команды; 1 - правильно одна команда; 2 - ни одна правильно</td></tr>"
  ."<tr><td colspan='2'><b>1.3</b></td>" .getValue($rowTLTArray['QUESTIONS'])
  ."<tr><td colspan='30'><span><b>2. Движение глазных яблок: </b></span>0 - норма; 1 - частичный парез взора; 2 - девиация г/я или полный паралич взора</td></tr>"
  ."<tr><td colspan='2'><b>2.</b></td>" .getValue($rowTLTArray['COMMANDS'])
  ."<tr><td colspan='30'><span><b>3. Поля зрения: </b></span>0 - норма; 1 - частичная гемианопсия; 2 - полная гемианопсия</td></tr>"
  ."<tr><td colspan='2'><b>3.</b></td>" .getValue($rowTLTArray['EYEBALLS'])
  ."<tr><td colspan='30'><span><b>4. Парез лицевой мускулатуры: </b></span>0 - норма; 1 - минимальный; 2 - нижних мимических мышц; 3 - верхних и нижних</td></tr>"
  ."<tr><td colspan='2'><b>4.</b></td>" .getValue($rowTLTArray['FIELDS_VIEW'])
  ."<tr><td colspan='30'><span><b>5. Движения в руках: </b></span>0 - нет опускания 10 сек; 1 - опускает ранее 10 сек; 2 - только незначительное сопротивление силе тяжести; 3 - нет сопротивления силе тяжести; 4 - нет активных движений; 9 - невозможно проверить</td></tr>"
  ."<tr><td colspan='2'>Левая</td>" .getValue($rowTLTArray['LEFT_HAND'])
  ."<tr><td colspan='2'>Правая</td>" .getValue($rowTLTArray['RIGHT_HAND'])
  ."<tr><td colspan='30'><span><b>5. Движения в ногах: </b></span>0 - нет опускания 5 сек; 1 - опускает ранее 5 сек; 2 - только незначительное сопротивление силе тяжести; 3 - нет сопротивления силе тяжести; 4 - нет активных движений; 9 - невозможно проверить</td></tr>"
  ."<tr><td colspan='2'>Левая</td>" .getValue($rowTLTArray['LEFT_LEG'])
  ."<tr><td colspan='2'>Правая</td>" .getValue($rowTLTArray['RIGHT_LEG'])
  ."<tr><td colspan='30'><span><b>7. Атаксия конечностей: </b></span>0 - отсутствует; 1 - в одной конечности; 2 - в двух конечностях</td></tr>"
  ."<tr><td colspan='2'><b>7.</b></td>" .getValue($rowTLTArray['ATAXIA'])
  ."<tr><td colspan='30'><span><b>8. Чувствительность (гемитип): </b></span>0 - норма; 1 - легкое или умеренное нарушение; 2 - значит. или полное нарушение</td></tr>"
  ."<tr><td colspan='2'><b>8.</b></td>" .getValue($rowTLTArray['SENSITIVITY'])
  ."<tr><td colspan='30'><span><b>9. Афазия: </b></span>0 - нет афазии; 1 - легкая или умеренная афазия; 2 - грубая афазия; 3 - отсутствие речи</td></tr>"
  ."<tr><td colspan='2'><b>9.</b></td>" .getValue($rowTLTArray['APHASIA'])
  ."<tr><td colspan='30'><span><b>10. Дизартрия: </b></span>0 - нормальная артикуляция; 1 - легкая или умеренная дизартрия; 2 - грубая дизартрия</td></tr>"
  ."<tr><td colspan='2'><b>10.</b></td>" .getValue($rowTLTArray['DYSARTHRIA'])
  ."<tr><td colspan='30'><spanp><b>11. Игнорирование (агнозия): </b></spanp>0 - отсутствует; 1 - гемиагнозия одной сенсорной модальности; 2 - выраженная гемиагнозия или гемиагнозия >1 сенсорной модальн.</td></tr>"
  ."<tr><td colspan='2'><b>11.</b></td>" .getValue($rowTLTArray['IGNORING'])
  ."<tr style='font-weight: bold'><td colspan='2'><span><b>Общий балл: </b></span></td>" .getValue($rowTLTArray['TOTAL'])
  ."</table>"

  ."<p style='font-weight: bold; text-align: center;'>Артериальное давление (мм рт. ст.)</p>"
  ."<table border='1'>"
  ."<tr><td rowspan='2'></td><td align=center><b>До</b></td><td colspan='8' align=center><b>Первые 2 часа (минуты)</b></td><td colspan='9' align=center><b>Следующие 6 часов (часы)</b></td></tr>"
  ."<tr><td align=center><b>ТЛТ</b></td><td align=center>15</td><td align=center>30</td><td align=center>45</td><td align=center>60</td><td align=center>75</td><td align=center>90</td><td align=center>105</td><td align=center>120</td>"
  ."<td align=center>2,5</td><td align=center>3</td><td align=center>3,5</td><td align=center>4</td><td align=center>4,5</td><td align=center>5</td><td align=center>5,5</td><td align=center>6</td><td align=center>6,5</td></tr>"
  ."<tr><td align=right><b>Время:</b><br>час<br>мин</td>" .getTimeForAd($rowAdArray['INPUT_AT'], 0, 18)
  ."<tr><td align=right><b>АД:</b><br>диас<br>сист</td>" .getValueForAd($rowAdArray['AD_SIST'], $rowAdArray['AD_DIAS'], 0, 18 )
  ."</table>"

  ."<table border='1'>"
  ."<tr><td rowspan='2'></td></td><td colspan='19' align=center><b>Следующие 16 часов (часы)</b></td></tr>"
  ."<tr><td align=center>7</td><td align=center>7,5</td><td align=center>8</td><td align=center>9</td><td align=center>10</td><td align=center>11</td>"
  ."<td align=center>12</td><td align=center>13</td><td align=center>14</td><td align=center>15</td><td align=center>16</td><td align=center>17</td>"
  ."<td align=center>18</td><td align=center>19</td><td align=center>20</td><td align=center>21</td><td align=center>22</td><td align=center>23</td><td align=center>24</td></tr>"
  ."<tr><td align=right><b>Время:</b><br>час<br>мин</td>" .getTimeForAd($rowAdArray['INPUT_AT'], 18, 37)
  ."<tr><td align=right><b>АД:</b><br>диас<br>сист</td>" .getValueForAd($rowAdArray['AD_SIST'], $rowAdArray['AD_DIAS'], 18, 37 )
  ."</table>"
?>

