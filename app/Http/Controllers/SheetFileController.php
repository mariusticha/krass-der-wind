<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SheetFileController extends Controller
{
    public function show(Sheet $sheet): StreamedResponse
    {
        abort_unless(Storage::exists($sheet->file_path), 404);

        return Storage::response($sheet->file_path);
    }
}
