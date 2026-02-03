<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::whereNull('deleted_at')->orderBy('created_at', 'desc')->get();
        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'birthdate' => 'nullable|date|before:today',
            'city' => 'nullable|string|max:255',
        ]);

        $contact = new Contact();
        $contact->first_name = $validated['first_name'];
        $contact->last_name = $validated['last_name'];
        $contact->phone = $validated['phone'];
        $contact->birthdate = $validated['birthdate'] ?? null;
        $contact->city = $validated['city'] ?? null;
        $contact->save();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    public function show(Contact $contact)
    {
        $contact->load('departments');
        $departments = \App\Models\Department::orderBy('name')->get();
        return view('contacts.show', compact('contact', 'departments'));
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
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

        $contact->save();
        return redirect()->route('contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    public function updateDepartments(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $departmentIds = $validated['department_ids'] ?? [];
        $contact->departments()->sync($departmentIds);

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Departments updated successfully.');
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

        if (!file_exists($file->getRealPath())) {
            return redirect()->route('contacts.index')
                ->with('error', 'Unable to read the uploaded file.');
        }

        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return redirect()->route('contacts.index')
                ->with('error', 'Unable to open the CSV file.');
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            return redirect()->route('contacts.index')
                ->with('error', 'The CSV file appears to be empty or invalid.');
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];
        $rowNumber = 1;
        $csvContacts = [];
        $seenInCsv = [];

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (empty(array_filter($row))) {
                $skipped++;
                continue;
            }

            if (count($row) < 3) {
                $errors[] = "Row {$rowNumber}: Insufficient columns. Required: first_name, last_name, phone";
                continue;
            }

            try {
                $first_name = trim($row[0] ?? '');
                $last_name = trim($row[1] ?? '');
                $phone = !empty($row[2]) ? trim($row[2]) : null;
                $birthdate = !empty($row[3]) ? trim($row[3]) : null;
                $city = !empty($row[4]) ? trim($row[4]) : null;

                if (empty($first_name)) {
                    $errors[] = "Row {$rowNumber}: First name is required";
                    continue;
                }

                if (empty($last_name)) {
                    $errors[] = "Row {$rowNumber}: Last name is required";
                    continue;
                }

                if (empty($phone)) {
                    $errors[] = "Row {$rowNumber}: Phone number is required";
                    continue;
                }

                $phone = trim($phone);
                $phoneKey = $phone;
                $nameKey = strtolower(trim($first_name) . '|' . trim($last_name));
                $namePhoneKey = $nameKey . '|' . $phoneKey;

                if (isset($seenInCsv[$namePhoneKey])) {
                    $errors[] = "Row {$rowNumber}: Duplicate contact - '{$first_name} {$last_name}' with phone '{$phoneKey}' already appears in row {$seenInCsv[$namePhoneKey]}";
                    continue;
                }

                $seenInCsv[$namePhoneKey] = $rowNumber;

                $formattedBirthdate = null;
                if (!empty($birthdate)) {
                    try {
                        $birthdate = trim($birthdate);
                        $parsedDate = null;

                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $birthdate)) {
                            $parsedDate = \Carbon\Carbon::createFromFormat('d/m/Y', $birthdate);
                        } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
                            $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $birthdate);
                        } elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $birthdate)) {
                            $parsedDate = \Carbon\Carbon::createFromFormat('d-m-Y', $birthdate);
                        } else {
                            $parsedDate = \Carbon\Carbon::parse($birthdate);
                        }

                        if ($parsedDate && $parsedDate->isFuture()) {
                            $errors[] = "Row {$rowNumber}: Birthdate cannot be in the future";
                            continue;
                        }

                        if ($parsedDate) {
                            $formattedBirthdate = $parsedDate->format('Y-m-d');
                        } else {
                            throw new \Exception('Unable to parse date');
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: Invalid birthdate format '{$birthdate}'. Supported formats: DD/MM/YYYY, YYYY-MM-DD, or DD-MM-YYYY";
                        continue;
                    }
                }

                if (strlen($phone) > 255) {
                    $errors[] = "Row {$rowNumber}: Phone number is too long (max 255 characters)";
                    continue;
                }

                if (!empty($city) && strlen($city) > 255) {
                    $errors[] = "Row {$rowNumber}: City name is too long (max 255 characters)";
                    continue;
                }

                $existingContact = Contact::whereNull('deleted_at')
                    ->whereRaw('LOWER(first_name) = ?', [strtolower(trim($first_name))])
                    ->whereRaw('LOWER(last_name) = ?', [strtolower(trim($last_name))])
                    ->where('phone', $phone)
                    ->first();

                if ($existingContact) {
                    $errors[] = "Row {$rowNumber}: Duplicate contact - '{$first_name} {$last_name}' with phone '{$phone}' already exists in database (found: '{$existingContact->first_name} {$existingContact->last_name}' with phone: {$existingContact->phone})";
                    continue;
                }

                $csvContacts[] = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'birthdate' => $formattedBirthdate,
                    'city' => $city,
                ];
            } catch (\Illuminate\Database\QueryException $e) {
                $errors[] = "Row {$rowNumber}: Database error - " . $e->getMessage();
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);

        foreach ($csvContacts as $contactData) {
            try {
                $contact = new Contact();
                $contact->first_name = $contactData['first_name'];
                $contact->last_name = $contactData['last_name'];
                $contact->phone = $contactData['phone'];
                $contact->birthdate = $contactData['birthdate'];
                $contact->city = $contactData['city'];
                $contact->save();
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Failed to import '{$contactData['first_name']} {$contactData['last_name']}': " . $e->getMessage();
            }
        }

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

        $messageType = !empty($errors) ? 'warning' : ($imported > 0 ? 'success' : 'error');

        return redirect()->route('contacts.index')
            ->with($messageType, $message)
            ->with('import_errors', $errors)
            ->with('imported_count', $imported);
    }
}
