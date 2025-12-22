<?php

namespace App\Controllers\Web;

use App\Repositories\GradeRepository;
use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;

class GradeController extends AbstractController
{
  protected GradeRepository $gradeRepo;

  public function __construct()
  {
    parent::__construct();
    $this->gradeRepo = new GradeRepository();
  }

  public function index(): Response
  {
    $grades = $this->gradeRepo->getAll();

    return $this->render('grades/index.html.twig', [
      'grades' => $grades,
      'page_title' => 'Grados Académicos',
      'active_menu' => 'grades'
    ]);
  }

  public function createForm(): Response
  {
    $levels = $this->gradeRepo->getLevels();

    return $this->render('grades/create.html.twig', [
      'page_title' => 'Crear Grado',
      'active_menu' => 'grades',
      'levels' => $levels
    ]);
  }

  public function store(): Response
  {
    $levels = $this->gradeRepo->getLevels();

    $name = trim($_POST['name'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $grade_order = trim($_POST['grade_order'] ?? '');

    if ($name === '' || $level === '' || $grade_order === '') {
      return $this->renderWithFlash('grades/create.html.twig', [
        'error' => 'Los campos son obligatorios',
        'old' => $_POST,
        'levels' => $levels
      ]);
    }

    $this->gradeRepo->create([
      'name' => $name,
      'level' => $level,
      'grade_order' => $grade_order
    ]);

    return $this->success([], 'Grado creado.', 200, '/grades');
  }

  public function editForm(int $id): Response
  {
    $grade = $this->gradeRepo->FindById($id);
    $levels = $this->gradeRepo->getLevels();
    if (!$grade) {
      return $this->renderWithFlash('grades/edit.html.twig', [
        'error' => 'Grado no encontrado'
      ], 404);
    }

    return $this->render('grades/edit.html.twig', [
      'grade' => $grade,
      'page_title' => 'Editar Grado',
      'active_menu' => 'grades',
      'levels' => $levels
    ]);
  }

  public function update(int $id): Response
  {
    $levels = $this->gradeRepo->getLevels();

    $name = trim($_POST['name'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $grade_order = trim($_POST['grade_order'] ?? '');

    if ($name === '' || $level === '' || $grade_order === '') {
      return $this->renderWithFlash('grades/edit.html.twig', [
        'error' => 'Los campos son obligatorios',
        'old' => $_POST,
        'levels' => $levels
      ]);
    }

    $this->gradeRepo->update($id, [
      'name' => $name,
      'level' => $level,
      'grade_order' => $grade_order
    ]);

    return $this->success([], 'Grado actualizado.', 200, '/grades');
  }

  public function destroy(int $id): Response
  {
    $grade = $this->gradeRepo->destroy($id);
    if (!$grade) {
      return $this->renderWithFlash('grades/edit.html.twig', [
        'error' => 'Grado no encontrado'
      ], 404);
    }

    return $this->redirect('/grades');
  }
}
