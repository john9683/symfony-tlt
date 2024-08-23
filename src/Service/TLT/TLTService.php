<?php

namespace App\Service\TLT;

use App\Entity\TLT;
use App\Util\DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TLTService
{
  public function __construct(
    EntityManagerInterface $em
  ) {
    $this->em = $em;
  }

  /**
   * @return array
   */
  public function getNameArrayForInput(): array
  {
    return [
      'consciousness' => [
        'title' => 'Уровень сознания',
        'options' => [
                      '0' => 'сознание ясное',
                      '1' => 'оглушение',
                      '2' => 'сопор',
                      '3' => 'кома',
          ],],
      'questions' => [
        'title' => 'Ответы на вопросы',
        'options' => [
                      '0' => 'правильно на оба вопроса',
                      '1' => 'правильно на один вопрос',
                      '2' => 'правильно ни на один вопрос',
          ], ],
      'commands' => [
        'title' => 'Выполнение команд',
        'options' => [
                      '0' => 'правильно обе команды',
                      '1' => 'правильно одна команды',
                      '2' => 'ни одна правильно',
        ], ],
      'eyeballs' => [
        'title' => 'Движение глазных яблок',
        'options' => [
                      '0' => 'норма',
                      '1' => 'частичный парез взора',
                      '2' => 'девиация г/я или полный паралич взора',
        ], ],
      'fieldsView' => [
        'title' => 'Поля зрения',
        'options' => [
                      '0' => 'норма',
                      '1' => 'частичная гемианопсия',
                      '2' => 'полная гемианопсия',
         ], ],
      'facialMuscles' => [
        'title' => 'Парез лицевой мускулатуры',
        'options' => [
                      '0' => 'норма',
                      '1' => 'минимальный',
                      '2' => 'нижних мимических мышц',
                      '3' => 'верхних и нижних',
        ], ],
      'leftHand' => [
        'title' => 'Движения в левой руке',
        'options' => [
                      '0' => 'нет опускания 10 сек',
                      '1' => 'опускает ранее 10 сек',
                      '2' => 'только незначительное сопротивление силе тяжести',
                      '3' => 'нет сопротивления силе тяжести',
                      '4' => 'нет активных движений',
                      '9' => 'невозможно проверить',
        ], ],
      'rightHand' => [
        'title' => 'Движения в правой руке',
        'options' => [
                      '0' => 'нет опускания 10 сек',
                      '1' => 'опускает ранее 10 сек',
                      '2' => 'только незначительное сопротивление силе тяжести',
                      '3' => 'нет сопротивления силе тяжести',
                      '4' => 'нет активных движений',
                      '9' => 'невозможно проверить',
        ], ],
      'leftLeg' => [
        'title' => 'Движения в левой ноге',
        'options' => [
                      '0' => 'нет опускания 5 сек',
                      '1' => 'опускает ранее 5 сек',
                      '2' => 'только незначительное сопротивление силе тяжести',
                      '3' => 'нет сопротивления силе тяжести',
                      '4' => 'нет активных движений',
                      '9' => 'невозможно проверить',
        ], ],
      'rightLeg' => [
        'title' => 'Движения в правой ноге',
        'options' => [
                      '0' => 'нет опускания 5 сек',
                      '1' => 'опускает ранее 5 сек',
                      '2' => 'только незначительное сопротивление силе тяжести',
                      '3' => 'нет сопротивления силе тяжести',
                      '4' => 'нет активных движений',
                      '9' => 'невозможно проверить',
        ], ],
      'ataxia' => [
        'title' => 'Атаксия конечностей',
        'options' => [
                      '0' => 'отсутствует',
                      '1' => 'в одной конечности',
                      '2' => 'в двух конечностях',
                      '3' => '',
        ], ],
      'sensitivity' => [
        'title' => 'Чувствительность (гемитип)',
        'options' => [
                      '0' => 'норма',
                      '1' => 'легкое или умеренное нарушение',
                      '2' => 'значительное или полное нарушение',
        ], ],
      'aphasia' => [
        'title' => 'Афазия',
        'options' => [
                      '0' => 'нет афазии',
                      '1' => 'легкая или умеренная афазия',
                      '2' => 'грубая афазия',
                      '3' => 'отсутствие речи',
        ], ],
      'dysarthria' => [
        'title' => 'Дизартрия',
        'options' => [
                      '0' => 'нормальная артикуляция',
                      '1' => 'легкая или умеренная дизартрия',
                      '2' => 'грубая дизартрия',
        ], ],
      'ignoring' => [
        'title' => 'Игнорирование (агнозия)',
        'options' => [
                      '0' => 'отсутствует',
                      '1' => 'гемиагнозия одной сенсорной модальности',
                      '2' => 'выраженная гемиагнозия или гемиагнозия >1 сенсорной модальн.',
        ], ],
      'adSist' => ['title' => 'АД систолическое', 'options' => false, ],
      'adDias' => ['title' => 'АД диастолическое', 'options' => false, ],
    ];
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @return array
   */
  public function getPeriodNameArray(string $hspId, string $patientId): array
  {
    return [
      '0' => ['title' => 'до ТЛТ', 'isExist' => $this->isTLTExist($hspId, $patientId, '0'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '0')],
      '1' => ['title' => '15 минут', 'isExist' => $this->isTLTExist($hspId, $patientId, '1'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '1')],
      '2' => ['title' => '30 минут', 'isExist' => $this->isTLTExist($hspId, $patientId, '2'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '2')],
      '3' => ['title' => '45 минут', 'isExist' => $this->isTLTExist($hspId, $patientId, '3'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '3')],
      '4' => ['title' => '60 минут', 'isExist' => $this->isTLTExist($hspId, $patientId, '4'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '4')],
      '5' => ['title' => '75 минут', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '5'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '5')],
      '6' => ['title' => '90 минут', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '6'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '6')],
      '7' => ['title' => '105 минут', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '7'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '7')],
      '8' => ['title' => '2 часа ', 'isExist' => $this->isTLTExist($hspId, $patientId, '8'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '8')],
      '9' => ['title' => '2,5 часа', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '9'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '9')],
      '10' => ['title' => '3 часа', 'isExist' => $this->isTLTExist($hspId, $patientId, '10'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '10')],
      '11' => ['title' => '3,5 часа', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '11'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '11')],
      '12' => ['title' => '4 часа', 'isExist' => $this->isTLTExist($hspId, $patientId, '12'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '12')],
      '13' => ['title' => '4,5 часа', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '13'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '13')],
      '14' => ['title' => '5 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '14'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '14')],
      '15' => ['title' => '5,5 часов', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '15'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '15')],
      '16' => ['title' => '6 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '16'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '16')],
      '17' => ['title' => '6,5 часа', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '17'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '17')],
      '18' => ['title' => '7 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '18'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '18')],
      '19' => ['title' => '7,5 часов', 'onlyAd' => true, 'isExist' => $this->isTLTExist($hspId, $patientId, '19'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '19')],
      '20' => ['title' => '8 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '20'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '20')],
      '21' => ['title' => '9 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '21'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '21')],
      '22' => ['title' => '10 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '22'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '22')],
      '23' => ['title' => '11 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '23'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '23')],
      '24' => ['title' => '12 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '24'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '24')],
      '25' => ['title' => '13 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '25'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '25')],
      '26' => ['title' => '14 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '26'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '26')],
      '27' => ['title' => '15 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '27'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '27')],
      '28' => ['title' => '16 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '28'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '28')],
      '29' => ['title' => '17 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '29'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '29')],
      '30' => ['title' => '18 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '30'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '30')],
      '31' => ['title' => '19 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '31'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '31')],
      '32' => ['title' => '20 часов', 'isExist' => $this->isTLTExist($hspId, $patientId, '32'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '32')],
      '33' => ['title' => '21 час', 'isExist' => $this->isTLTExist($hspId, $patientId, '33'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '33')],
      '34' => ['title' => '22 часа', 'isExist' => $this->isTLTExist($hspId, $patientId, '34'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '34')],
      '35' => ['title' => '23 часа', 'isExist' => $this->isTLTExist($hspId, $patientId, '35'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '35')],
      '36' => ['title' => '24 часа', 'isExist' => $this->isTLTExist($hspId, $patientId, '36'), 'isCompleted' => $this->isTLTCompleted($hspId, $patientId, '36')],
   ];
  }

  /**
   * @param Request $request
   * @param string $hspId
   * @param string $patientId
   * @param string $userId
   * @param string $period
   * @return void
   */
  public function createTLT(
    Request $request,
    string $hspId,
    string $patientId,
    string $userId,
    string $period
  ): void
  {
    $tlt = $this->getTLTByPeriod($hspId, $patientId, $period);

    if ($tlt !== null) {
      $tlt->userUpdate = $_SESSION['cur_user'];
      $tlt->updatedAt = new DateTimeImmutable('now', new DateTimeZone('Asia/Novosibirsk'));
      $this->saveTLTAttributes($tlt, $request);

    } else {

      $tlt = new TLT();
      $tlt->hspId = $hspId;
      $tlt->patientId = $patientId;
      $tlt->userCreate = $userId;
      $tlt->createdAt = new DateTimeImmutable('now', new DateTimeZone('Asia/Novosibirsk'));
      $this->saveTLTAttributes($tlt, $request);
    }
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @param string $period
   * @return TLT|null
   */
  public function getTLTByPeriod(string $hspId, string $patientId, string $period): ?TLT
  {
    return $this->em->getRepository(TLT::class)->findOneBy(['hspId' => $hspId, 'patientId' => $patientId, 'period' => $period]);
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @return array
   */
  public function getDateTimeBeginTLT(string $hspId, string $patientId): array
  {
    $TLT = $this->em->getRepository(TLT::class)->findOneBy(['hspId' => $hspId, 'patientId' => $patientId, 'period' => 0]);

    return ['date' => date('d.m.Y', strtotime($TLT->dateBeginTLT)), 'time' => $TLT->timeBeginTLT];
  }

  public function getRowTLTByPatientForTable(string $hspId, string $patientId): array
  {
    $TLTArray = $this->getTLTByPatient($hspId, $patientId);
    $rowTLTArray = [];

    if (count($TLTArray) == 0) return [];

    foreach ($TLTArray as $TLT) {
      $rowTLTArray['Период'][] = $this->getPeriodNameArray($hspId, $patientId)[$TLT->period]['title']
                                . ($this->getPeriodNameArray($hspId, $patientId)[$TLT->period]['onlyAd'] ? ' (только АД)' : '');
      $rowTLTArray['Время'][] = $TLT->inputAt;
      $rowTLTArray[$this->getNameArrayForInput()['consciousness']['title']][] = $this->getNameArrayForInput()['consciousness']['options'][$TLT->consciousness];
      $rowTLTArray[$this->getNameArrayForInput()['questions']['title']][] = $this->getNameArrayForInput()['questions']['options'][$TLT->questions];
      $rowTLTArray[$this->getNameArrayForInput()['commands']['title']][] = $this->getNameArrayForInput()['commands']['options'][$TLT->commands];
      $rowTLTArray[$this->getNameArrayForInput()['eyeballs']['title']][] = $this->getNameArrayForInput()['eyeballs']['options'][$TLT->eyeballs];
      $rowTLTArray[$this->getNameArrayForInput()['fieldsView']['title']][] = $this->getNameArrayForInput()['fieldsView']['options'][$TLT->fieldsView];
      $rowTLTArray[$this->getNameArrayForInput()['facialMuscles']['title']][] = $this->getNameArrayForInput()['facialMuscles']['options'][$TLT->facialMuscles];
      $rowTLTArray[$this->getNameArrayForInput()['leftHand']['title']][] = $this->getNameArrayForInput()['leftHand']['options'][$TLT->leftHand];
      $rowTLTArray[$this->getNameArrayForInput()['rightHand']['title']][] = $this->getNameArrayForInput()['rightHand']['options'][$TLT->rightHand];
      $rowTLTArray[$this->getNameArrayForInput()['leftLeg']['title']][] = $this->getNameArrayForInput()['leftLeg']['options'][$TLT->leftLeg];
      $rowTLTArray[$this->getNameArrayForInput()['rightLeg']['title']][] = $this->getNameArrayForInput()['rightLeg']['options'][$TLT->rightLeg];
      $rowTLTArray[$this->getNameArrayForInput()['ataxia']['title']][] = $this->getNameArrayForInput()['ataxia']['options'][$TLT->ataxia];
      $rowTLTArray[$this->getNameArrayForInput()['sensitivity']['title']][] = $this->getNameArrayForInput()['sensitivity']['options'][$TLT->sensitivity];
      $rowTLTArray[$this->getNameArrayForInput()['aphasia']['title']][] = $this->getNameArrayForInput()['aphasia']['options'][$TLT->aphasia];
      $rowTLTArray[$this->getNameArrayForInput()['dysarthria']['title']][] = $this->getNameArrayForInput()['dysarthria']['options'][$TLT->dysarthria];
      $rowTLTArray[$this->getNameArrayForInput()['ignoring']['title']][] = $this->getNameArrayForInput()['ignoring']['options'][$TLT->ignoring];
      $rowTLTArray['Общий балл'][] = $TLT->total;
      $rowTLTArray[$this->getNameArrayForInput()['adSist']['title']][] = $TLT->adSist;
      $rowTLTArray[$this->getNameArrayForInput()['adDias']['title']][] = $TLT->adDias;
    }

    return  $rowTLTArray;
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @return array
   */
  public function getTLTByPatient(string $hspId, string $patientId): array
  {
    return $this->em->getRepository(TLT::class)->findBy(['hspId' => $hspId, 'patientId' => $patientId], ['period' => 'ASC']);
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @param string $period
   * @return bool
   */
  private function isTLTExist(string $hspId, string $patientId, string $period): bool
  {
    return (bool)$this->getTLTByPeriod($hspId, $patientId, $period);
  }

  /**
   * @param string $hspId
   * @param string $patientId
   * @param string $period
   * @return bool
   */
  private function isTLTCompleted(string $hspId, string $patientId, string $period): bool
  {
    $TLT = $this->getTLTByPeriod($hspId, $patientId, $period);

    $result = true;

    foreach ($TLT as $item)
    {
      if ($TLT->period !== 5 && $TLT->period !== 6 && $TLT->period !== 7 && $TLT->period !== 9 && $TLT->period !== 11 && $TLT->period !== 13 && $TLT->period !== 15 && $TLT->period !== 17 && $TLT->period !== 19 ) {
        if ($item === null || $item === '') {
          $result = false;
          break;
        }
      } else {
        if ( $TLT->adDias === null || $TLT->adSist === null || $TLT->inputAt === '') {
          $result = false;
          break;
        }
      }
    }

    return $result;
  }

  /**
   * @param TLT $tlt
   * @param Request $request
   * @return void
   */
  private function saveTLTAttributes(TLT $tlt, Request $request): void
  {
    $tlt->inputAt = $request->get('inputAt');
    $tlt->dateBeginTLT = $request->get('period') == 0 ? $request->get('dateBeginTLT') : 'plug';
    $tlt->timeBeginTLT = $request->get('period') == 0 ? $request->get('timeBeginTLT') : 'plug';
    $tlt->period = $request->get('period');

    $tlt->consciousness = $request->get('consciousness');
    $tlt->questions = $request->get('questions');
    $tlt->commands = $request->get('commands');
    $tlt->eyeballs = $request->get('eyeballs');
    $tlt->fieldsView = $request->get('fieldsView');
    $tlt->facialMuscles = $request->get('facialMuscles');

    $tlt->leftHand = $request->get('leftHand');
    $tlt->rightHand = $request->get('rightHand');
    $tlt->leftLeg = $request->get('leftLeg');
    $tlt->rightLeg = $request->get('rightLeg');
    $tlt->ataxia = $request->get('ataxia');
    $tlt->sensitivity = $request->get('sensitivity');

    $tlt->aphasia = $request->get('aphasia');
    $tlt->dysarthria = $request->get('dysarthria');
    $tlt->ignoring = $request->get('ignoring');
    $tlt->total = $this->getTotalTLTAttributes($request);
    $tlt->adSist = $request->get('adSist');
    $tlt->adDias = $request->get('adDias');

    $this->em->persist($tlt);
    $this->em->flush();
  }

  /**
   * @param Request $request
   * @return int|null
   */
  private function getTotalTLTAttributes(Request $request): ?int
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];

    if (!$this->getPeriodNameArray($hspId, $patientId)[$request->get('period')]['onlyAd']) {

      return $request->get('consciousness')
        + $request->get('questions')
        + $request->get('commands')
        + $request->get('eyeballs')
        + $request->get('fieldsView')
        + $request->get('facialMuscles')
        + $request->get('leftHand')
        + $request->get('rightHand')
        + $request->get('leftLeg')
        + $request->get('rightLeg')
        + $request->get('ataxia')
        + $request->get('sensitivity')
        + $request->get('aphasia')
        + $request->get('dysarthria')
        + $request->get('ignoring');
    }

    return null;
  }
}

