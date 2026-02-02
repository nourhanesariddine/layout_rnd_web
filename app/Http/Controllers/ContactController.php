<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::whereNull('deleted_at')->orderBy('created_at', 'desc')->paginate(10);
        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'birthdate' => 'nullable|date|before:today',
            'city' => 'nullable|string|max:255',
        ]);

        $contact = new Contact();
        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->phone = $validated['phone'] ?? null;
        $contact->birthdate = $validated['birthdate'] ?? null;
        $contact->city = $validated['city'] ?? null;
        $contact->save();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }


    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    // 
    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }


    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'city' => 'nullable|string|max:255',
        ]);


        if (isset($validated['first_name'])) {
            $contact->first_name = $validated['first_name'];
        }
        if (isset($validated['last_name'])) {
            $contact->last_name = $validated['last_name'];
        }
        if (isset($validated['phone'])) {
            $contact->phone = $validated['phone'];
        }
        if (isset($validated['birthdate'])) {
            $contact->birthdate = $validated['birthdate'];
        }
        if (isset($validated['city'])) {
            $contact->city = $validated['city'];
        }


        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }


    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }


    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel',
                'max:2048',
            ],
        ]);

        $file = $request->file('csv_file');

        // Check if file can be opened
        if (!file_exists($file->getRealPath())) {
            return redirect()->route('contacts.index')
                ->with('error', 'Unable to read the uploaded file.');
        }

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->route('contacts.index')
                ->with('error', 'Unable to open the CSV file.');
        }

        // Skip header row
        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return redirect()->route('contacts.index')
                ->with('error', 'The CSV file appears to be empty or invalid.');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNumber = 1; // Start from 1 since we skipped header

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            // Skip empty rows
            if (empty(array_filter($row))) {
                $skipped++;
                continue;
            }

            // Validate minimum required columns (first_name, last_name)
            if (count($row) < 2) {
                $errors[] = "Row {$rowNumber}: Insufficient columns. Required: first_name, last_name";
                continue;
            }

            try {
                // Extract and trim data
                $first_name = trim($row[0] ?? '');
                $last_name = trim($row[1] ?? '');
                $phone = !empty($row[2]) ? trim($row[2]) : null;
                $birthdate = !empty($row[3]) ? trim($row[3]) : null;
                $city = !empty($row[4]) ? trim($row[4]) : null;

                // Validate required fields
                if (empty($first_name)) {
                    $errors[] = "Row {$rowNumber}: First name is required";
                    continue;
                }

                if (empty($last_name)) {
                    $errors[] = "Row {$rowNumber}: Last name is required";
                    continue;
                }

                // Validate and format birthdate if provided
                $formattedBirthdate = null;
                if (!empty($birthdate)) {
                    try {
                        $parsedDate = \Carbon\Carbon::parse($birthdate);
                        if ($parsedDate->isFuture()) {
                            $errors[] = "Row {$rowNumber}: Birthdate cannot be in the future";
                            continue;
                        }
                        $formattedBirthdate = $parsedDate->format('Y-m-d');
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: Invalid birthdate format '{$birthdate}'. Use YYYY-MM-DD format";
                        continue;
                    }
                }

                // Validate phone length if provided
                if (!empty($phone) && strlen($phone) > 255) {
                    $errors[] = "Row {$rowNumber}: Phone number is too long (max 255 characters)";
                    continue;
                }

                // Validate city length if provided
                if (!empty($city) && strlen($city) > 255) {
                    $errors[] = "Row {$rowNumber}: City name is too long (max 255 characters)";
                    continue;
                }

                // Create contact
                $contact = new Contact();
                $contact->first_name = $first_name;
                $contact->last_name = $last_name;
                $contact->phone = $phone;
                $contact->birthdate = $formattedBirthdate;
                $contact->city = $city;
                $contact->save();

                $imported++;
            } catch (\Illuminate\Database\QueryException $e) {
                $errors[] = "Row {$rowNumber}: Database error - " . $e->getMessage();
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);

        // Build response message
        $messages = [];

        if ($imported > 0) {
            $messages[] = "Successfully imported {$imported} contact(s)";
        }

        if ($skipped > 0) {
            $messages[] = "Skipped {$skipped} empty row(s)";
        }

        if (!empty($errors)) {
            $messages[] = count($errors) . " error(s) occurred";
        }

        $message = !empty($messages) ? implode('. ', $messages) . '.' : 'No contacts were imported.';

        // Determine message type
        $messageType = !empty($errors) ? 'warning' : ($imported > 0 ? 'success' : 'error');

        return redirect()->route('contacts.index')
            ->with($messageType, $message)
            ->with('import_errors', $errors)
            ->with('imported_count', $imported);
    }
}
