<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;

class MeetingReportController extends Controller
{
    public function create()
    {
        return view('meeting-report.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'meeting_title' => 'required|string|max:255',
            'meeting_number' => 'required',
            'meeting_date' => 'required|date',
            'meeting_location' => 'required|string|max:255',
            'participants' => 'required|array',
            'issues' => 'required|array',
        ]);
        return view('meeting-report.pdf', compact('validated'));
        // สร้าง mpdf พร้อมตั้งค่าให้รองรับภาษาไทย
        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $html = view('meeting-report.pdf', compact('validated'))->render();
        $mpdf = new \Mpdf\Mpdf([
            'fontDir' => array_merge($fontDirs, [
                storage_path('fonts/'),
            ]),
            'fontdata' => $fontData + [
                'sarabun_new' => [
                    'R' => 'THSarabunNew.ttf',
                    'I' => 'THSarabunNew-Italic.ttf',
                    'B' => 'THSarabunNew-Bold.ttf',
                ],
            ],
            'default_font' => 'sarabun_new',
        ]);
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        return $mpdf->Output();
    }
}
