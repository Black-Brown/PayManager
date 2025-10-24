<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\PaymentConcept;
use App\Models\Payment;

class PaymentConceptController extends AbstractController
{
    /**
     * Listar todos los conceptos de pago
     */
    public function index(): Response
    {
        $concepts = PaymentConcept::all();

        return $this->render('payment-concepts/index.html.twig', [
            'concepts' => $concepts,
            'page_title' => 'Lista de Conceptos de Pago',
            'active_menu' => 'paymentConcepts'
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function createForm(): Response
    {
        return $this->render('payment-concepts/create.html.twig', [
            'page_title' => 'Registrar Concepto de Pago',
            'active_menu' => 'paymentConceptsCreate'
        ]);
    }

    /**
     * Guardar un nuevo concepto de pago
     */
    public function store(): Response
    {
        $data = $this->request->getAllPost();

        // Validaciones básicas
        if (empty($data['name']) || empty($data['type']) || empty($data['amount'])) {
            return $this->renderWithFlash('payment-concepts/create.html.twig', [
                'error' => 'Nombre, tipo y monto son obligatorios.',
                'old' => $data
            ]);
        }

        $concept = PaymentConcept::create($data);

        return $this->success([
            'id' => $concept->id
        ], 'Concepto de pago registrado correctamente.', 201, '/payment-concepts');
    }

    /**
     * Mostrar formulario de edición
     */
    public function editForm(int $id): Response
    {
        $concept = PaymentConcept::find($id);
        if (!$concept) {
            return $this->error('Concepto de pago no encontrado', 404);
        }

        return $this->render('payment-concepts/edit.html.twig', [
            'concept' => $concept,
            'page_title' => 'Editar Concepto de Pago',
            'active_menu' => 'paymentConcepts'
        ]);
    }

    /**
     * Actualizar un concepto de pago existente
     */
    public function update(int $id): Response
    {
        $concept = PaymentConcept::find($id);
        if (!$concept) {
            return $this->error('Concepto de pago no encontrado', 404);
        }

        $data = $this->request->getAllPost();

        if (empty($data['name']) || empty($data['type']) || empty($data['amount'])) {
            return $this->renderWithFlash('payment-concepts/edit.html.twig', [
                'error' => 'Nombre, tipo y monto son obligatorios.',
                'concept' => $concept
            ]);
        }

        $concept->update($data);

        return $this->success([], 'Concepto de pago actualizado correctamente.', 200, '/payment-concepts');
    }

    /**
     * Eliminar un concepto de pago
     */
    public function destroy(int $id): Response
    {
        $concept = PaymentConcept::find($id);
        if (!$concept) {
            return $this->error('Concepto de pago no encontrado', 404);
        }

        $concept->delete($id);

        return $this->success([], 'Concepto de pago eliminado correctamente.', 200, '/payment-concepts');
    }

    /**
     * Mostrar detalle de un concepto
     */
    public function show(int $id): Response
    {
        $concept = PaymentConcept::find($id);
        if (!$concept) {
            return $this->error('Concepto de pago no encontrado', 404);
        }

        // Obtener pagos directamente como array
        $payments = Payment::where('concept_id', $concept->id);

        $totalPayments = count($payments);
        $totalAmount = 0;

        foreach ($payments as $payment) {
            $discounts = $payment->discounts();
            $totalDiscounts = array_sum(array_column($discounts, 'discount_amount'));
            $netAmount = $payment->amount - $totalDiscounts;

            $totalAmount += $netAmount;
        }


        return $this->render('payment-concepts/show.html.twig', [
            'concept' => $concept,
            'totalPayments' => $totalPayments,
            'totalAmount' => $totalAmount,
            'page_title' => 'Detalle del Concepto de Pago',
            'active_menu' => 'paymentConcepts'
        ]);
    }

}
