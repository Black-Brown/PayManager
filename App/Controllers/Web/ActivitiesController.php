<?php

namespace App\Controllers\Web;

use JosueIsOffline\Framework\Controllers\AbstractController;
use JosueIsOffline\Framework\Http\Response;
use App\Models\Activity; 

class ActivitiesController extends AbstractController
{
    public function index(): Response
    {
        $activities = Activity::all();
        return $this->render('activities/index.html.twig', [
            'activities' => $activities,
            'page_title' => 'Actividades Extraescolares',
            'active_menu' => 'activities'
        ]);
    }

    public function createForm(): Response
    {
        return $this->render('activities/create.html.twig', [
            'page_title' => 'Crear Actividad',
            'active_menu' => 'activities'
        ]);
    }

    public function store(): Response
    {
        $data = $this->request->getAllPost();

        if (
            empty($data['name']) ||
            empty($data['monthly_cost'])
        ) {
            return $this->renderWithFlash('activities/create.html.twig', [
                'error' => 'Los campos nombre y costo mensual son obligatorios.',
                'old' => $data
            ]);
        }

        $data['active'] = isset($data['active']) ? (int) $data['active'] : 1;

        Activity::create($data);
        return $this->success([], 'Actividad creada correctamente.', 201, '/activities');
    }

    public function editForm(int $id): Response
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return $this->error('Actividad no encontrada', 404);
        }

        return $this->render('activities/edit.html.twig', [
            'activity' => $activity,
            'page_title' => 'Editar Actividad',
            'active_menu' => 'activities'
        ]);
    }

    public function update(int $id): Response
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return $this->error('Actividad no encontrada', 404);
        }

        $data = $this->request->getAllPost();
        $data['active'] = isset($data['active']) ? (int) $data['active'] : 1;

        $activity->update($data);
        return $this->success([], 'Actividad actualizada.', 200, '/activities');
    }

    public function destroy(int $id): Response
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return $this->error('Actividad no encontrada', 404);
        }

        $activity->delete($id);
        return $this->success([], 'Actividad eliminada.', 200, '/activities');
    }
}
