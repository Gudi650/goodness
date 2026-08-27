@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ isset($message) ? $message->embed(public_path('favicon.png')) : url('/favicon.png') }}" class="logo" alt="Goodness Group">
</a>
</td>
</tr>
