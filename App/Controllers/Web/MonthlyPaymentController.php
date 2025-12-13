<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use JosueIsOffline\Framework\Database\DB;
use App\Models\Payment;
use App\Models\Student;
use App\Models\PaymentConcept;

class MonthlyPaymentController extends AbstractController
{
    public function index(): Response
    {
        $year = $this->request->get('year') ?? date('Y');
        $students = Student::all();
        
        // Definir los meses del ciclo escolar (ej. Septiembre a Junio)
        // O simplemente los 12 meses del año
        $months = [
            '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', 
            '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
            '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', 
            '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
        ];

        // Obtener todos los pagos de tipo 'Monthly' para el año seleccionado
        // Asumimos que corresponding_month tiene formato YYYY-MM
        $payments = DB::table('payments')
            ->join('payment_concepts', 'payments.concept_id', '=', 'payment_concepts.id')
            ->where('payment_concepts.type', 'Monthly')
            ->where('payments.corresponding_month', 'LIKE', "$year-%")
            ->get();

        // Organizar pagos por estudiante y mes
        $paymentMatrix = [];
        foreach ($payments as $payment) {
            $month = substr($payment['corresponding_month'], 5, 2); // Extraer MM de YYYY-MM
            $paymentMatrix[$payment['student_id']][$month] = $payment;
        }

        return $this->render('payments/monthly_control.html.twig', [
            'students' => $students,
            'months' => $months,
            'paymentMatrix' => $paymentMatrix,
            'year' => $year,
            'page_title' => 'Control de Pagos Mensuales',
            'active_menu' => 'monthlyControl'
        ]);
    }

    public function create(int $studentId, string $month): Response
    {
        $student = Student::find($studentId);
        if (!$student) {
            return $this->error('Estudiante no encontrado', 404);
        }

        // Buscar conceptos de mensualidad
        $concepts = PaymentConcept::monthlyConcepts();
        
        // Pre-seleccionar datos para la vista de creación
        return $this->render('payments/create.html.twig', [
            'students' => Student::all(),
            'concepts' => PaymentConcept::all(),
            'preselected_student_id' => $studentId,
            'preselected_month' => $month, // Formato YYYY-MM
            'preselected_concept_type' => 'Monthly',
            'page_title' => 'Registrar Mensualidad',
            'active_menu' => 'paymentsCreate'
        ]);
    }
}
