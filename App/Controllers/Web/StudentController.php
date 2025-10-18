<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use JosueIsOffline\Framework\Database\DB;
use App\Models\Student;
use App\Models\Grade;

class StudentController extends AbstractController
{
    public function index(): Response
    {
        $students = Student::all();

        foreach ($students as &$student) {
            $grade = Grade::find($student['grade_id']);
            $student['grade_name'] = $grade ? $grade->name . ' - ' . $grade->level : 'Sin grado';
        }

        return $this->render('students/index.html.twig', [
            'students' => $students,
            'page_title' => 'Lista de Estudiantes',
            'active_menu' => 'students'
        ]);
    }

    public function createForm(): Response
    {
        $grades = Grade::all();

        return $this->render('students/create.html.twig', [
            'grades' => $grades,
            'page_title' => 'Crear Estudiante',
            'active_menu' => 'students'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        // Validar los campos que realmente vienen del formulario de estudiantes
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['grade_id'])) {
            $grades = Grade::all(); // Necesario para volver a renderizar el select
            return $this->renderWithFlash('students/create.html.twig', [
                'error' => 'Nombre, apellido y grado son obligatorios.',
                'old' => $data,
                'grades' => $grades
            ]);
        }

        $existingStudent = DB::table('students')
            ->where('ministry_id', $data['ministry_id'])
            ->first();

        if ($existingStudent) {
            return $this->renderWithFlash('students/create.html.twig', [
                'error' => 'Ya existe un estudiante con ese ID ministerial.',
                'old' => $data
            ]);
        }

        // Guardar el estudiante
        $student = Student::create($data);

        // Redirigir con éxito
        return $this->success([
            'id' => $student->id
        ], 'Estudiante creado correctamente.', 201, '/students');
    }


    public function editForm(int $id): Response
    {
        $student = Student::find($id);
        if (!$student) {
            return $this->error('Estudiante no encontrado', 404);
        }

        $grades = Grade::all();
        return $this->render('students/edit.html.twig', [
            'grades' => $grades,
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

        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['grade_id'])) {
            $grades = Grade::all();
            return $this->renderWithFlash('students/edit.html.twig', [
                'error' => 'Nombre, apellido y grado son obligatorios.',
                'student' => $student,
                'grades' => $grades
            ]);
        }

        $student->update($data);

        return $this->success([], 'Estudiante actualizado correctamente.', 200, '/students');
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
