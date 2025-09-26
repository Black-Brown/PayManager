<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\Grade;

class GradeController extends AbstractController
{
    public function index(): Response
    {
        $grades = Grade::all();
        return $this->render('grades/index.html.twig', [
            'grades' => $grades,
            'page_title' => 'Grados Académicos',
            'active_menu' => 'grades'
        ]);
    }

    public function createForm(): Response
    {
        return $this->render('grades/create.html.twig', [
            'page_title' => 'Crear Grado',
            'active_menu' => 'grades',
            'niveles' => ['Preescolar', 'Primaria', 'Secundaria', 'Bachillerato']
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        if (empty($data['name']) || empty($data['level']) || empty($data['grade_order'])) {
            return $this->renderWithFlash('grades/create.html.twig', [
                'error' => 'Todos los campos son obligatorios.',
                'old' => $data
            ]);
        }

        Grade::create($data);
        return $this->success([], 'Grado creado correctamente.', 201, '/grades');
    }

    public function editForm(int $id): Response
    {
        $grade = Grade::find($id);
        if (!$grade) {
            return $this->error('Grado no encontrado', 404);
        }

        return $this->render('grades/edit.html.twig', [
            'grade' => $grade,
            'page_title' => 'Editar Grado',
            'active_menu' => 'grades',
            'niveles' => ['Preescolar', 'Primaria', 'Secundaria', 'Bachillerato']
        ]);
    }

    public function update(int $id): Response
    {
        $grade = Grade::find($id);
        if (!$grade) {
            return $this->error('Grado no encontrado', 404);
        }

        $data = $this->request->getAllPost();
        $grade->update($data);

        return $this->success([], 'Grado actualizado.', 200, '/grades');
    }

    public function destroy(int $id): Response
    {
        $grade = Grade::find($id);
        if (!$grade) {
            return $this->error('Grado no encontrado', 404);
        }

        $grade->delete($id);
        return $this->success([], 'Grado eliminado.', 200, '/grades');
    }

}
