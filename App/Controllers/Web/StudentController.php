<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\Grade;

class StudentController extends AbstractController
{
    public function index(): Response
    {
        $students = Student::all();
        return $this->render('students/index.html.twig', [
            'students' => $students,
            'page_title' => 'Lista de Estudiantes',
            'active_menu' => 'students'
        ]);
    }

    public function createForm(): Response
    {
        return $this->render('students/create.html.twig', [
            'page_title' => 'Crear Estudiante',
            'active_menu' => 'students'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        if (empty($data['name']) || empty($data['level']) || empty($data['grade_order'])) {
            return $this->renderWithFlash('students/create.html.twig', [
                'error' => 'Todos los campos son obligatorios.',
                'old' => $data
            ]);
        }

        Student::create($data);
        return $this->success([], 'Estudiante creado correctamente.', 201, '/students');
    }

    public function editForm(int $id): Response
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->error('Estudiante no encontrado', 404);
        }

        return $this->render('students/edit.html.twig', [
            'student' => $student,
            'page_title' => 'Editar Estudiante',
            'active_menu' => 'students'
        ]);
    }

    public function update(int $id): Response
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->error('Estudiante no encontrado', 404);
        }

        $data = $this->request->getAllPost();
        $student->update($data);

        return $this->success([], 'Estudiante actualizado.', 200, '/students');
    }

    public function destroy(int $id): Response
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->error('Estudiante no encontrado', 404);
        }

        $student->delete($id);
        return $this->success([], 'Estudiante eliminado.', 200, '/students');
    }

}
