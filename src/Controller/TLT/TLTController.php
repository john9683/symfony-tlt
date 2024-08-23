<?php

namespace App\Controller\TLT;

use App\Service\TLT\TLTService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tlt")
 */
class TLTController extends AbstractController
{
  /**
   * @Route("/period-list", methods={"GET"})
   * @param TLTService $service
   * @return Response
   */
  public function indexPeriodList(TLTService $service): Response
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];

    $template = $this->render('TLT/TLT_period_list.html.twig',[
      'baseUrl' => $_SERVER['BASE_URL'],
      'periodArray' => $service->getPeriodNameArray($hspId, $patientId),
      'dateTimeBeginTLT' => $service->getDateTimeBeginTLT($hspId, $patientId)
    ]);

    $response = new Response($template->getContent());
    $response->headers->set('Content-Type', 'text/html; charset=UTF-8');

    return $response;
  }

  /**
   * @Route("/form", methods={"GET"})
   * @param TLTService $service
   * @return Response
   */
  public function indexCreateForm(TLTService $service, Request $request): Response
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];
    $period = $request->get('period');
    $tlt = $service->getTLTByPeriod($hspId, $patientId, $period);

    $template = $this->render('TLT/TLT_form.html.twig',[
      'baseUrl' => $_SERVER['BASE_URL'],
      'period' => $request->get('period'),
      'title' => $tlt !== null ? 'Обновление данных для периода:' : 'Ввод данных для периода:',
      'btnText' => $tlt !== null ? 'Обновить' : 'Сохранить',
      'periodName' =>$service->getPeriodNameArray($hspId, $patientId)[$request->get('period')]['title'],
      'inputNameArray' => $service->getNameArrayForInput(),
      'onlyAd' => (bool)$service->getPeriodNameArray($hspId, $patientId)[$request->get('period')]['onlyAd'],
      'isTLTExists' => (bool)$tlt,
      'TLT' => json_decode(json_encode($tlt), true),
    ]);

    $response = new Response($template->getContent());
    $response->headers->set('Content-Type', 'text/html; charset=UTF-8');

    return $response;
  }

  /**
   * @Route("/create", methods={"POST"})
   * @param TLTService $service
   * @param Request $request
   * @return response
   */
  public function createTLT(TLTService $service, Request $request): response
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];
    $userId = $_SESSION['cur_user'];
    $period = $request->get('period');

    $service->createTLT($request, $hspId, $patientId, $userId, $period);

    return $this->redirect('/seven/tlt/period-list');
  }

  /**
   * @Route("/tlt/json", methods={"GET"})
   * @param TLTService $service
   * @return JsonResponse
   */
  public function indexTLTJson(TLTService $service): JsonResponse
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];

    return $this->json($service->getRowTLTByPatientForTable($hspId, $patientId));
  }

  /**
   * @Route("/tlt", methods={"GET"})
   * @param TLTService $service
   * @return JsonResponse
   */
  public function indexTLT(TLTService $service): Response
  {
    $hspId = $_SESSION['cur_hsp'];
    $patientId = $_SESSION['cur_pat'];

    $template = $this->render('TLT/TLT_table.html.twig',[
      'baseUrl' => $_SERVER['BASE_URL'],
      'data' => $service->getRowTLTByPatientForTable( $hspId, $patientId),
    ]);

    $response = new Response($template->getContent());
    $response->headers->set('Content-Type', 'text/html; charset=UTF-8');

    return $response;
  }

}
