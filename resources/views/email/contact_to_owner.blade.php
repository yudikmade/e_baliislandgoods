<h3>Hi,</h3>
<p> {{$opening}}</p>

<p>
@foreach($data_form as $key => $value)
	<strong>{{$key}}</strong> : {{$value}}<br/>
@endforeach
</p>

<p>Segara</p>