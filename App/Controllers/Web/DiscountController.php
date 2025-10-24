<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\Discount;
use App\Models\Payment;
use App\Models\PaymentConcept;

class DiscountController extends AbstractController
{
    public function store(int $paymentId): Response
    {
        $payment = Payment::find($paymentId);
        if (!$payment) {
            return $this->error('Pago no encontrado', 404);
        }

        $concept = PaymentConcept::find($payment->concept_id);
        if (!$concept || !$concept->allowsDiscount()) {
            return $this->error('Este concepto de pago no permite descuentos.', 400);
        }

        $data = $this->request->getAllPost();

        if (empty($data['discount_amount']) || empty($data['reason'])) {
            return $this->error('Monto del descuento y razón son obligatorios.', 400, [], "/payments/{$paymentId}");
        }

        if ($data['discount_amount'] <= 0) {
            return $this->error('El monto del descuento debe ser mayor a cero.', 400, [], "/payments/{$paymentId}");
        }

        $totalDiscounts = array_sum(array_column($payment->discounts(), 'discount_amount'));
        $newTotal = $totalDiscounts + $data['discount_amount'];

        if ($newTotal > $payment->amount) {
            $available = $payment->amount - $totalDiscounts;
            return $this->error("El descuento excede el monto disponible. Máximo disponible: $" . number_format($available, 2), 400, [], "/payments/{$paymentId}");
        }

        $data['payment_id'] = $paymentId;
        $data['applied_by_user_id'] = $this->user()->id;

        try {
            Discount::create($data);
            return $this->success([], 'Descuento aplicado correctamente.', 200, "/payments/{$paymentId}");
        } catch (\Exception $e) {
            return $this->error('Error al aplicar el descuento: ' . $e->getMessage(), 500, [], "/payments/{$paymentId}");
        }
    }

    public function destroy(int $paymentId, int $discountId): Response
    {
        $discount = Discount::find($discountId);

        if (!$discount || $discount->payment_id != $paymentId) {
            return $this->error('Descuento no encontrado o no pertenece a este pago.', 404, [], "/payments/{$paymentId}");
        }

        try {
            $discount->delete($discount->id); // ← corrección aquí
            return $this->success([], 'Descuento eliminado correctamente.', 200, "/payments/{$paymentId}");
        } catch (\Exception $e) {
            return $this->error('Error al eliminar el descuento: ' . $e->getMessage(), 500, [], "/payments/{$paymentId}");
        }
    }


    public function edit(int $paymentId, int $discountId): Response
    {
        $discount = Discount::find($discountId);
        $payment = Payment::find($paymentId);

        if (!$discount || $discount->payment_id != $paymentId || !$payment) {
            return $this->error('Descuento o pago no encontrado.', 404);
        }

        return $this->render('discounts/edit.html.twig', [
            'discount' => $discount,
            'payment' => $payment,
            'page_title' => 'Editar Descuento',
            'active_menu' => 'payments'
        ]);
    }

    public function update(int $paymentId, int $discountId): Response
    {
        $discount = Discount::find($discountId);
        $payment = Payment::find($paymentId);

        if (!$discount || $discount->payment_id != $paymentId || !$payment) {
            return $this->error('Descuento o pago no encontrado.', 404);
        }

        $data = $this->request->getAllPost();

        if (empty($data['discount_amount']) || empty($data['reason'])) {
            return $this->error('Monto del descuento y razón son obligatorios.', 400, [], "/payments/{$paymentId}/discount/{$discountId}/edit");
        }

        if ($data['discount_amount'] <= 0) {
            return $this->error('El monto del descuento debe ser mayor a cero.', 400, [], "/payments/{$paymentId}/discount/{$discountId}/edit");
        }

        $otherDiscounts = array_filter(
            $payment->discounts(),
            fn($d) => $d['id'] != $discountId
        );
        $totalOtherDiscounts = array_sum(array_column($otherDiscounts, 'discount_amount'));

        if (($totalOtherDiscounts + $data['discount_amount']) > $payment->amount) {
            $available = $payment->amount - $totalOtherDiscounts;
            return $this->error("El descuento excede el monto disponible. Máximo disponible: $" . number_format($available, 2), 400, [], "/payments/{$paymentId}/discount/{$discountId}/edit");
        }

        try {
            $discount->update($data);
            return $this->success([], 'Descuento actualizado correctamente.', 200, "/payments/{$paymentId}");
        } catch (\Exception $e) {
            return $this->error('Error al actualizar el descuento: ' . $e->getMessage(), 500, [], "/payments/{$paymentId}/discount/{$discountId}/edit");
        }
    }

    public function index(int $paymentId): Response
    {
        $payment = Payment::find($paymentId);
        if (!$payment) {
            return $this->error('Pago no encontrado', 404);
        }

        $discounts = $payment->discounts();
        
        // Calcular monto total manualmente
        $totalDiscounts = array_sum(array_column($discounts, 'discount_amount'));
        $payment->total_amount = $payment->amount - $totalDiscounts;

        return $this->render('discounts/index.html.twig', [
            'payment' => $payment,
            'discounts' => $discounts,
            'page_title' => 'Descuentos del Pago',
            'active_menu' => 'payments'
        ]);
    }
}
