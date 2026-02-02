<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Department;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display the search page.
     */
    public function index(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        return view('search.index', compact('departments'));
    }

    /**
     * Handle AJAX search requests.
     */
    public function search(Request $request)
    {
        $name = $request->get('name', '');
        $phone = $request->get('phone', '');
        $departmentId = $request->get('department_id', '');

        $query = Contact::with('departments');

        // Search by name (partial match on first_name or last_name)
        if (!empty($name)) {
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                    ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        // Search by phone number
        if (!empty($phone)) {
            $query->where('phone', 'like', "%{$phone}%");
        }

        // Filter by department (single selection, but contacts can belong to multiple)
        if (!empty($departmentId)) {
            $query->whereHas('departments', function ($q) use ($departmentId) {
                $q->where('departments.id', $departmentId);
            });
        }

        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $contacts = $query->latest()->paginate($perPage, ['*'], 'page', $page);

        // Return JSON response for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'contacts' => $contacts->items(),
                'pagination' => [
                    'current_page' => $contacts->currentPage(),
                    'last_page' => $contacts->lastPage(),
                    'per_page' => $contacts->perPage(),
                    'total' => $contacts->total(),
                ],
                'html' => view('search.partials.contacts_list', compact('contacts'))->render()
            ]);
        }

        return view('search.index', compact('contacts'));
    }
}
