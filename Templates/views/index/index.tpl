<table>
<thead>
<tr>
	<th>Name</th>
	<th>Anzahl</th>
	<th>Umsatz</th>
	<th>Min</th>
	<th>Max</th>
	<th>Schnitt</th>
	<th>Abweichung</th>
	<th>Region</th>
	<th>Realm</th>
	<th>AH</th>
</tr>
</thead>
<tbody>
{% for row in data %}
	{% include "index/ah_row.html" %}
{% endfor %}
</tbody>
</table>
