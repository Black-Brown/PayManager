<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use JosueIsOffline\Framework\Database\DB;
use App\Models\Payment;
use App\Models\Student;
use App\Models\PaymentConcept;
use App\Models\User;

class PaymentController extends AbstractController
{
    public function index(): Response
    {
        $payments = Payment::all();

        foreach ($payments as &$payment) {
            $student = Student::find($payment['student_id']);
            $concept = PaymentConcept::find($payment['concept_id']);
            $user = User::find($payment['registered_by_user_id']);

            $payment['student_name'] = $student ? $student->first_name . ' ' . $student->last_name : 'Sin estudiante';
            $payment['concept_name'] = $concept ? $concept->name : 'Sin concepto';
            $payment['registered_by'] = $user ? $user->name : 'Sistema';
        }

        return $this->render('payments/index.html.twig', [
            'payments' => $payments,
            'page_title' => 'Lista de Pagos',
            'active_menu' => 'payments'
        ]);
    }

    public function createForm(): Response
    {
        $students = Student::all();
        $concepts = PaymentConcept::all();

        return $this->render('payments/create.html.twig', [
            'students' => $students,
            'concepts' => $concepts,
            'page_title' => 'Registrar Pago',
            'active_menu' => 'payments'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        if (empty($data['student_id']) || empty($data['concept_id']) || empty($data['amount']) || empty($data['payment_date'])) {
            $students = Student::all();
            $concepts = PaymentConcept::all();
            return $this->renderWithFlash('payments/create.html.twig', [
                'error' => 'Estudiante, concepto, monto y fecha de pago son obligatorios.',
                'old' => $data,
                'students' => $students,
                'concepts' => $concepts
            ]);
        }

        $data['registered_by_user_id'] = $this->request->getUserId(); // Asumiendo que el framework tiene esta función

        $payment = Payment::create($data);

        return $this->success([
            'id' => $payment->id
        ], 'Pago registrado correctamente.', 201, '/payments');
    }

    public function editForm(int $id): Response
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return $this->error('Pago no encontrado', 404);
        }

        $students = Student::all();
        $concepts = PaymentConcept::all();

        return $this->render('payments/edit.html.twig', [
            'payment' => $payment,
            'students' => $students,
            'concepts' => $concepts,
            'page_title' => 'Editar Pago',
            'active_menu' => 'payments'
        ]);
    }

    public function update(int $id): Response
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return $this->error('Pago no encontrado', 404);
        }

        $data = $this->request->getAllPost();

        if (empty($data['student_id']) || empty($data['concept_id']) || empty($data['amount']) || empty($data['payment_date'])) {
            $students = Student::all();
            $concepts = PaymentConcept::all();
            return $this->renderWithFlash('payments/edit.html.twig', [
                'error' => 'Estudiante, concepto, monto y fecha de pago son obligatorios.',
                'payment' => $payment,
                'students' => $students,
                'concepts' => $concepts
            ]);
        }

        $payment->update($data);

        return $this->success([], 'Pago actualizado correctamente.', 200, '/payments');
    }

    public function destroy(int $id): Response
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return $this->error('Pago no encontrado', 404);
        }

        $payment->delete($id);
        return $this->success([], 'Pago eliminado.', 200, '/payments');
    }
}
 