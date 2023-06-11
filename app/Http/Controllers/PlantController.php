<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Services\PlantService;
use App\Http\Requests\StorePlantPostRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PlantController extends Controller
{
  private $plant;
  private $plantService;

  public function __construct()
  {
    $this->middleware('auth')->only([
      'store',
      'destroy',
    ]);

    $this->plant = new Plant();
    $this->plantService = new PlantService();
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * 植物(名前、色、生育地、特徴、ファイル)を登録する。
   * @param \App\Http\Requests\StorePlantPostRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(StorePlantPostRequest $request): JsonResponse
  {
    $storedPlant = $this->plantService->storeOnePlant($request);

    return response()->json($storedPlant, Response::HTTP_CREATED);
  }

  /**
   * 1つ植物の情報を取得する。
   * @param \App\Models\Plant $plant
   * @return \Illuminate\Http\JsonResponse
   */
  public function show(Plant $plant): JsonResponse
  {
    $displayPlant = $this->plant->getOnePlant($plant);

    return response()->json($displayPlant, Response::HTTP_OK);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Plant $plant)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Plant $plant)
  {
    $destroyResult = $this->plant->destroyPlantAndRelations($plant->id);

    return response()->json($destroyResult, Response::HTTP_OK);
  }

  /**
   * 植物名を検索する。
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function search(Request $request): JsonResponse
  {
    $searchResults = $this->plantService->searchPlantName($request);

    return response()->json($searchResults, Response::HTTP_OK);
  }

  /**
   * 生育場所で検索し、植物一覧を取得する
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function searchPlaces(Request $request): JsonResponse
  {
    $searchResults = $this->plantService->searchPlantsPlaces($request);

    return response()->json($searchResults, Response::HTTP_OK);
  }

  /**
   * TOP画面「注目の植物たち」で表示する植物
   * @return \Illuminate\Http\JsonResponse
   */
  public function recommendPlants(): JsonResponse
  {
    try {
      $plants = $this->plant->fetchRandomFivePlants();
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
    }

    return response()->json($plants, Response::HTTP_OK);
  }
}
