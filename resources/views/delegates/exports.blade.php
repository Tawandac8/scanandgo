<table>
    <thead>
        <tr>
            <th style="font-weight: bold;">Name</th>
            <th style="font-weight: bold;">Company Name</th>
            <th style="font-weight: bold;">Printed By</th>
            <th style="font-weight: bold;">Printed Copies</th>
            <th style="font-weight: bold;">Serial Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($badges as $badge)
            <tr>
                <td>{{ $badge->name }}</td>
                <td>{{ $badge->company_name }}</td>
                <td>{{ $badge->printed_by }}</td>
                <td>{{ $badge->printed_copies }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>