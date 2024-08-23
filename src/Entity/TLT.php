<?php

namespace App\Entity;

use App\Util\DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @package App\Entity\TLT
 * @ORM\Entity
 * @ORM\Table(name="TLT")
 */

class TLT
{
  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer", name="id")
   * @var int
   */
  public $id;

  /**
   * @ORM\Column(type="integer", name="id_hsp")
   * @var int
   */
  public $hspId;

  /**
   * @ORM\Column(type="integer", name="id_pat")
   * @var int
   */
  public $patientId;

  /**
   * @ORM\Column(type="smallint", name="measure_period")
   * @var int
   */
  public $period;

  /**
   * @ORM\Column(type="integer", name="id_user_create", nullable=true)
   * @var int
   */
  public $userCreate;

  /**
   * @ORM\Column(type="integer", name="id_user_update", nullable=true)
   * @var int
   */
  public $userUpdate;

  /**
   * @ORM\Column(type="string", name="input_at", nullable=true)
   * @var string
   */
  public $inputAt;

  /**
   * @ORM\Column(type="datetime_immutable", name="created_at", nullable=true)
   * @Gedmo\Timestampable(on="create")
   * @var DateTimeImmutable
   */
  public $createdAt;

  /**
   * @ORM\Column(type="datetime_immutable", name="updated_at", nullable=true)
   * @Gedmo\Timestampable(on="update")
   * @var DateTimeImmutable
   */
  public $updatedAt;

  /**
   * parameter 1
   * @ORM\Column(type="smallint", name="consciousness", nullable=true)
   * @var int|null
   */
  public $consciousness;

  /**
   * parameter 2
   * @ORM\Column(type="smallint", name="questions", nullable=true)
   * @var int|null
   */
  public $questions;

  /**
   * parameter 3
   * @ORM\Column(type="smallint", name="commands", nullable=true)
   * @var int|null
   */
  public $commands;

  /**
   * parameter 4
   * @ORM\Column(type="smallint", name="eyeballs", nullable=true)
   * @var int|null
   */
  public $eyeballs;

  /**
   * parameter 5
   * @ORM\Column(type="smallint", name="fields_view", nullable=true)
   * @var int|null
   */
  public $fieldsView;

  /**
   * parameter 6
   * @ORM\Column(type="smallint", name="facial_muscles", nullable=true)
   * @var int|null
   */
  public $facialMuscles;

  /**
   * parameter 7
   * @ORM\Column(type="smallint", name="left_hand", nullable=true)
   * @var int|null
   */
  public $leftHand;

  /**
   * parameter 8
   * @ORM\Column(type="smallint", name="right_hand", nullable=true)
   * @var int|null
   */
  public $rightHand;

  /**
   * parameter 9
   * @ORM\Column(type="smallint", name="left_leg", nullable=true)
   * @var int|null
   */
  public $leftLeg;

  /**
   * parameter 10
   * @ORM\Column(type="smallint", name="right_leg", nullable=true)
   * @var int|null
   */
  public $rightLeg;

  /**
   * parameter 11
   * @ORM\Column(type="smallint", name="ataxia", nullable=true)
   * @var int|null
   */
  public $ataxia;

  /**
   * parameter 12
   * @ORM\Column(type="smallint", name="sensitivity", nullable=true)
   * @var int|null
   */
  public $sensitivity;

  /**
   * parameter 13
   * @ORM\Column(type="smallint", name="aphasia", nullable=true)
   * @var int|null
   */
  public $aphasia;

  /**
   * parameter 14
   * @ORM\Column(type="smallint", name="dysarthria", nullable=true)
   * @var int|null
   */
  public $dysarthria;

  /**
   * parameter 15
   * @ORM\Column(type="smallint", name="ignoring", nullable=true)
   * @var int|null
   */
  public $ignoring;

  /**
   * parameter 16
   * @ORM\Column(type="smallint", name="total", nullable=true)
   * @var int|null
   */
  public $total;

  /**
   * parameter 17
   * @ORM\Column(type="smallint", name="ad_sist", nullable=true)
   * @var int|null
   */
  public $adSist;

  /**
   * parameter 18
   * @ORM\Column(type="smallint", name="ad_dias", nullable=true)
   * @var int|null
   */
  public $adDias;

  /**
   * @ORM\Column(type="string", name="time_begin_tlt", nullable=true)
   * @var string
   */
  public $timeBeginTLT;

  /**
   * @ORM\Column(type="string", name="date_begin_tlt", nullable=true)
   * @var string
   */
  public $dateBeginTLT;
}




