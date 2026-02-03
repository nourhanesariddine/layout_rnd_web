<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Department;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $perPage = config('search.per_page', 5);
        return view('search.index', compact('departments', 'perPage'));
    }

    public function search(Request $request)
    {
        $name = $request->get('name', '');
        $phone = $request->get('phone', '');
        $departmentId = $request->get('department_id', '');

        $query = Contact::with('departments');

        if (!empty($name)) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        if (!empty($phone)) {
            $query->where('phone', 'like', "%{$phone}%");
        }

        if (!empty($departmentId)) {
            $query->whereHas('departments', function ($q) use ($departmentId) {
                $q->where('departments.id', $departmentId);
            });
        }

        $perPage = $request->get('per_page', config('search.per_page', 5));
        $page = $request->get('page', 1);
        $contacts = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        if ($request->ajax()) {
            $contacts->load('departments');

            return response()->json([
                'success' => true,
                'contacts' => $contacts->items(),
                'pagination' => [
                    'current_page' => $contacts->currentPage(),
                    'last_page' => $contacts->lastPage(),
                    'per_page' => $contacts->perPage(),
                    'total' => $contacts->total(),
                    'has_more' => $contacts->hasMorePages(),
                ],
                'html' => view('search.partials.contacts_list', compact('contacts'))->render()
            ]);
        }

        return view('search.index', compact('contacts'));
    }
}
