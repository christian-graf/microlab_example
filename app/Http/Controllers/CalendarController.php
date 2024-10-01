<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Controller;
use App\Models\Reminder;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Response as InertiaResponse;
use App\Http\Requests\ReminderCreateRequest;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\ReminderUpdateRequest;

class CalendarController extends Controller
{
    /**
     * Get a collection of all available reminders saved by the current logged in user.
     *
     * @return Collection|Reminder[]
     */
    private function getRemindersForCurrentUser(): Collection
    {
        return Reminder::query()
            ->select([
                'id',
                'day',
                'month',
                'description',
                'interval',
            ])
            ->where('created_by', '=', (int) Auth::id())
            ->orderBy('month', 'asc')
            ->orderBy('day', 'asc')
            ->get()
        ;
    }

    /**
     * Loading the index page of the calendar module.
     *
     * @return \Inertia\Response
     */
    public function index(): InertiaResponse
    {
        return Inertia::render('Calendar', [
            'reminders' => $this->getRemindersForCurrentUser()->toArray(),
        ]);
    }

    /**
     * Add a new reminder entry to the users list.
     *
     * @param \App\Http\Requests\ReminderCreateRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReminder(ReminderCreateRequest $request): JsonResponse
    {
        $reminder = new Reminder($request->all());
        $reminder->setAttribute('created_by', (int) Auth::id());
        $reminder->save();

        return new JsonResponse($this->getRemindersForCurrentUser()->toArray());
    }

    /**
     * Update a reminder entry of the current user.
     *
     * @param \App\Http\Requests\ReminderUpdateRequest $request
     * @param int                                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateReminder(ReminderUpdateRequest $request, int $id): JsonResponse
    {
        $reminder = Reminder::find($id);
        if ($reminder === null) {
            abort(Response::HTTP_NOT_FOUND, 'Reminder not found!');
        }

        if ($reminder->id !== (int) $request->get('id')) {
            abort(Response::HTTP_BAD_REQUEST, 'Data identifier mismatch!');
        }

        if ($reminder->created_by !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'Do do not have the permission to delete the designated reminder.');
        }

        $reminder->fill($request->all());
        $reminder->save();

        return new JsonResponse($this->getRemindersForCurrentUser()->toArray());
    }

    /**
     * Deletes a reminder entry of the current logged in user list.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReminder(int $id): JsonResponse
    {
        $reminder = Reminder::find($id);
        if ($reminder === null) {
            abort(Response::HTTP_NOT_FOUND, 'Reminder not found!');
        }

        if ($reminder->created_by !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'Do do not have the permission to delete the designated reminder.');
        }

        $reminder->delete();

        return new JsonResponse($this->getRemindersForCurrentUser()->toArray());
    }
}
