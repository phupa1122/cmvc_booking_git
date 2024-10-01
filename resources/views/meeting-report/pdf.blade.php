<html>
<header>
    <title>pdf</title>
    <meta http-equiv="Content-Language" content="th" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
        }
    </style>
</header>

<body>
    <h1>รายงานการประชุม {{ $validated['meeting_title'] }}</h1>
    <p>ครั้งที่: {{ $validated['meeting_number'] }}</p>
    <p>วันที่ประชุม: {{ $validated['meeting_date'] }}</p>
    <p>สถานที่ประชุม: {{ $validated['meeting_location'] }}</p>

    <h2>ผู้เข้าร่วมประชุม</h2>
    <p>{{ implode(', ', $validated['participants']) }}</p>

    <h2>ประเด็นการประชุม</h2>
    @foreach ($validated['issues'] as $issue)
        <p>{{ $issue }}</p>
    @endforeach
</body>

</html>
