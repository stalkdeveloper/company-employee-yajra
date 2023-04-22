<x-mail::message>

<h2>Hello {{ $employee['first_name'] }},</h2>

    I hope you are doing well.


<p> <b>{{ $employee['company_name'] }}</b>, {{ $employee['message'] }}</p>

From, <p> {{ $employee['user'] }}</p>
{{-- If you not cancelled the meeting room then please contact our team.!!<br>
<a class="btn btn-primary" href="{{ route('bookinghall.create') }}">Book Now</a> --}}


<br>
<p> Note: This Message is auto generated so please don't replay if you have any concerns contact our team. <br>
Mail Id: support@companyemployeerecord.com </p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
