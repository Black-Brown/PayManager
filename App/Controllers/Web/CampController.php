<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\Camp;

class CampController extends AbstractController
{
    public function index(): Response
    {
        $camps = Camp::all();
        return $this->render('camps/index.html.twig', [
            'camps' => $camps,
            'page_title' => 'Campamentos',
            'active_menu' => 'camps'
        ]);
    }

    public function createForm(): Response
    {
        return $this->render('camps/create.html.twig', [
            'page_title' => 'Crear Campamento',
            'active_menu' => 'camps'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        if (
            empty($data['name']) ||
            empty($data['start_date']) ||
            empty($data['end_date']) ||
            empty($data['cost'])
        ) {
            return $this->renderWithFlash('camps/create.html.twig', [
                'error' => 'Los campos nombre, fechas y costo son obligatorios.',
                'old' => $data
            ]);
        }

        // Asegurar que 'active' tenga un valor válido
        $data['active'] = isset($data['active']) ? (int) $data['active'] : 1;

        Camp::create($data);
        return $this->success([], 'Campamento creado correctamente.', 201, '/camps');
    }

    public function editForm(int $id): Response
    {
        $camp = Camp::find($id);
        if (!$camp) {
            return $this->error('Campamento no encontrado', 404);
        }

        return $this->render('camps/edit.html.twig', [
            'camp' => $camp,
            'page_title' => 'Editar Campamento',
            'active_menu' => 'camps'
        ]);
    }

    public function update(int $id): Response
    {
        $camp = Camp::find($id);
        if (!$camp) {
            return $this->error('Campamento no encontrado', 404);
        }

        $data = $this->request->getAllPost();
        $data['active'] = isset($data['active']) ? (int) $data['active'] : 1;

        $camp->update($data);
        return $this->success([], 'Campamento actualizado.', 200, '/camps');
    }

    public function destroy(int $id): Response
    {
        $camp = Camp::find($id);
        if (!$camp) {
            return $this->error('Campamento no encontrado', 404);
        }

        $camp->delete($id);
        return $this->success([], 'Campamento eliminado.', 200, '/camps');
    }
}
